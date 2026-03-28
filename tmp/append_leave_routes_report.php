<?php

declare(strict_types=1);

use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\Company;
use App\Models\Department;
use App\Models\Leave;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$reportPath = __DIR__ . '/../API_ROUTE_RESPONSES.md';
$tag = 'leave-' . now()->format('Ymd-His');

function uniqueText(string $prefix): string
{
    return $prefix . '-' . Str::lower(Str::random(6));
}

function dispatchApi(string $method, string $uri, array $data = [], ?string $token = null): array
{
    global $app;

    /** @var HttpKernel $kernel */
    $kernel = $app->make(HttpKernel::class);

    $request = Request::create($uri, $method, $data);
    $request->headers->set('Accept', 'application/json');
    $request->headers->set('User-Agent', 'Codex Leave Route Tester');

    if ($token) {
        $request->headers->set('Authorization', 'Bearer ' . $token);
    }

    $response = $kernel->handle($request);
    $content = $response->getContent();
    $kernel->terminate($request, $response);

    $decoded = json_decode($content, true);

    return [
        'status' => $response->getStatusCode(),
        'body' => json_last_error() === JSON_ERROR_NONE ? $decoded : $content,
    ];
}

function createDepartmentForLeave(string $tag, ?int $companyId = null, ?int $branchId = null): Department
{
    $data = [
        'slug' => uniqueText($tag . '-department'),
        'code' => strtoupper(Str::substr(Str::replace('-', '', uniqueText('DPT')), 0, 10)),
        'name' => 'Leave Department ' . Str::upper(Str::random(4)),
        'description' => 'Department for leave route testing',
        'approval_mode' => 'hierarchical',
        'escalation_mode' => 'full_chain',
        'can_create_tasks' => true,
        'can_receive_tasks' => true,
        'is_system_default' => false,
        'is_active' => true,
    ];

    if (Schema::hasColumn('departments', 'company_id')) {
        $data['company_id'] = $companyId;
    }

    if (Schema::hasColumn('departments', 'branch_id')) {
        $data['branch_id'] = $branchId;
    }

    return Department::unguarded(static fn() => Department::create($data));
}

$company = Company::create([
    'slug' => uniqueText($tag . '-company'),
    'code' => strtoupper(Str::substr(Str::replace('-', '', uniqueText('CMP')), 0, 10)),
    'name' => 'Leave Company ' . Str::upper(Str::random(4)),
    'email' => uniqueText('leave-company') . '@example.com',
    'phone' => '9555555555',
    'password' => 'password123',
    'currency_code' => 'INR',
    'timezone' => 'Asia/Calcutta',
    'is_active' => true,
    'is_delete' => false,
]);

$branch = Branch::create([
    'company_id' => $company->id,
    'code' => strtoupper(Str::substr(Str::replace('-', '', uniqueText('BR')), 0, 10)),
    'name' => 'Leave Branch ' . Str::upper(Str::random(4)),
    'slug' => uniqueText($tag . '-branch'),
    'email' => uniqueText('leave-branch') . '@example.com',
    'phone' => '9666666666',
    'address_line1' => 'Leave branch address',
    'city' => 'Kolkata',
    'state' => 'West Bengal',
    'country' => 'India',
    'postal_code' => '700001',
    'is_head_office' => true,
    'is_active' => true,
]);

$department = createDepartmentForLeave($tag, $company->id, $branch->id);

$employee = BranchUser::create([
    'company_id' => $company->id,
    'branch_id' => $branch->id,
    'dept_id' => $department->id,
    'emp_id' => 'LEV-' . random_int(10000000, 99999999),
    'name' => 'Leave Employee',
    'email' => uniqueText('leave-employee') . '@example.com',
    'password' => 'password123',
    'phone' => '9777777777',
    'slug' => uniqueText($tag . '-employee'),
    'is_dept_admin' => false,
    'is_branch_admin' => false,
    'is_active' => true,
    'is_delete' => false,
    'created_by' => $company->id,
]);

$login = dispatchApi('POST', '/api/employee/login', [
    'email' => $employee->email,
    'password' => 'password123',
]);

$token = $login['body']['token'] ?? null;

if (!$token) {
    throw new RuntimeException('Employee login failed; leave routes cannot be tested.');
}

$results = [];

$record = function (string $label, string $method, string $displayRoute, string $uri, array $payload = []) use (&$results, $token): array {
    $response = dispatchApi($method, $uri, $payload, $token);
    $results[] = [
        'label' => $label,
        'method' => $method,
        'route' => $displayRoute,
        'tested_uri' => $uri,
        'request' => $payload,
        'status' => $response['status'],
        'response' => $response['body'],
    ];

    return $response;
};

$fromDate = now()->addDay()->format('Y-m-d');
$toDate = now()->addDays(2)->format('Y-m-d');

$apply = $record(
    'Apply leave',
    'POST',
    '/api/employee/leave/apply',
    '/api/employee/leave/apply',
    [
        'leave_type' => 'casual',
        'from_date' => $fromDate,
        'to_date' => $toDate,
        'reason' => 'Route test leave request',
    ]
);

$leaveId = $apply['body']['data']['id'] ?? null;

if (!$leaveId) {
    $leave = Leave::create([
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'dept_id' => $department->id,
        'branch_user_id' => $employee->id,
        'leave_type' => 'casual',
        'from_date' => $fromDate,
        'to_date' => $toDate,
        'total_days' => 2,
        'reason' => 'Fallback leave for route testing',
        'status' => 'pending',
    ]);
    $leaveId = $leave->id;
}

$record(
    'List leaves',
    'GET',
    '/api/employee/leaves',
    '/api/employee/leaves'
);

$record(
    'Show leave',
    'GET',
    '/api/employee/leaves/{id}',
    '/api/employee/leaves/' . $leaveId
);

$record(
    'Leave balance',
    'GET',
    '/api/employee/leave-balance',
    '/api/employee/leave-balance'
);

$record(
    'Cancel leave',
    'DELETE',
    '/api/employee/leaves/{id}',
    '/api/employee/leaves/' . $leaveId
);

$lines = [];
$lines[] = '## Leave Routes';
$lines[] = '';
$lines[] = '- Generated at: `' . now()->toDateTimeString() . '`';
$lines[] = '- Employee login used for auth setup only.';
$lines[] = '';
$lines[] = '| # | Route | Status |';
$lines[] = '| --- | --- | --- |';

foreach ($results as $index => $result) {
    $lines[] = '| ' . ($index + 1) . ' | `' . $result['method'] . ' ' . $result['route'] . '` | `' . $result['status'] . '` |';
}

$lines[] = '';
$lines[] = '### Detailed Leave Responses';
$lines[] = '';

foreach ($results as $index => $result) {
    $lines[] = '#### ' . ($index + 1) . '. ' . $result['label'];
    $lines[] = '';
    $lines[] = '- Route: `' . $result['method'] . ' ' . $result['route'] . '`';
    if ($result['route'] !== $result['tested_uri']) {
        $lines[] = '- Tested URI: `' . $result['tested_uri'] . '`';
    }
    $lines[] = '- Status: `' . $result['status'] . '`';
    $lines[] = '';
    $lines[] = '**Request Payload**';
    $lines[] = '';
    $lines[] = '```json';
    $lines[] = json_encode($result['request'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $lines[] = '```';
    $lines[] = '';
    $lines[] = '**Response Body**';
    $lines[] = '';
    $lines[] = '```json';
    $lines[] = json_encode($result['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $lines[] = '```';
    $lines[] = '';
}

$section = implode(PHP_EOL, $lines) . PHP_EOL;
$existing = file_exists($reportPath) ? file_get_contents($reportPath) : '';

if ($existing !== false && preg_match('/^## Leave Routes\b/ms', $existing) === 1) {
    $updated = preg_replace('/^## Leave Routes\b[\s\S]*$/m', $section, $existing);
    file_put_contents($reportPath, $updated);
} else {
    $prefix = $existing !== '' && !str_ends_with($existing, PHP_EOL) ? PHP_EOL . PHP_EOL : PHP_EOL;
    file_put_contents($reportPath, $existing . $prefix . $section);
}

echo 'Leave route results updated in: ' . $reportPath . PHP_EOL;
