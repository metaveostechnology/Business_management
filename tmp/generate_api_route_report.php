<?php

declare(strict_types=1);

use App\Models\Admin;
use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
RateLimiter::for('api', fn (Request $request) => Limit::perMinute(10000)->by($request->user()?->id ?: $request->ip()));

/** @var HttpKernel $httpKernel */
$httpKernel = $app->make(HttpKernel::class);

$runId = now()->format('Ymd_His') . '_' . Str::lower(Str::random(6));
$reportPath = base_path('api_route_test_report.md');

function prettyJson(mixed $value): string
{
    return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?: 'null';
}

function responseBodySummary(string $content): mixed
{
    if ($content === '') {
        return ['raw' => ''];
    }

    $decoded = json_decode($content, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        return $decoded;
    }

    return ['raw' => $content];
}

function normalizeRouteUri(string $uri): string
{
    $patterns = [
        '#^/api/admins/[^/]+/restore$#' => '/api/admins/{slug}/restore',
        '#^/api/admins/[^/]+$#' => '/api/admins/{slug}',
        '#^/api/admin/companies/[^/]+$#' => '/api/admin/companies/{slug}',
        '#^/api/companies/[^/]+$#' => '/api/companies/{slug}',
        '#^/api/company/branches/[^/]+$#' => '/api/company/branches/{slug}',
        '#^/api/company/features/[^/]+$#' => '/api/company/features/{slug}',
        '#^/api/company/departments/[^/]+$#' => '/api/company/departments/{slug}',
        '#^/api/company/department-features/[^/]+$#' => '/api/company/department-features/{slug}',
        '#^/api/company/settings/[^/]+$#' => '/api/company/settings/{slug}',
        '#^/api/company/roles/[^/]+$#' => '/api/company/roles/{slug}',
        '#^/api/company/branch-users/[^/]+/change-password$#' => '/api/company/branch-users/{slug}/change-password',
        '#^/api/company/branch-users/[^/]+$#' => '/api/company/branch-users/{slug}',
        '#^/api/branch/employees/[^/]+$#' => '/api/branch/employees/{slug}',
        '#^/api/dept/employees/[^/]+$#' => '/api/dept/employees/{slug}',
    ];

    foreach ($patterns as $pattern => $replacement) {
        if (preg_match($pattern, $uri) === 1) {
            return $replacement;
        }
    }

    return $uri;
}

function sendRequest(HttpKernel $kernel, string $method, string $uri, array $data = [], ?string $token = null, bool $json = true): array
{
    static $requestCounter = 0;
    $requestCounter++;
    $lastOctet = ($requestCounter % 250) + 1;

    $server = [
        'HTTP_ACCEPT' => 'application/json',
        'REMOTE_ADDR' => '10.0.0.' . $lastOctet,
    ];

    if ($token !== null) {
        $server['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
    }

    $content = null;
    $parameters = $data;

    if ($json && in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
        $server['CONTENT_TYPE'] = 'application/json';
        $content = json_encode($data);
        $parameters = [];
    }

    $request = Request::create($uri, $method, $parameters, [], [], $server, $content);
    $response = $kernel->handle($request);
    $body = $response->getContent();
    $status = $response->getStatusCode();
    $kernel->terminate($request, $response);

    return [
        'status' => $status,
        'body' => responseBodySummary($body),
        'raw' => $body,
    ];
}

function recordResult(array &$results, string $method, string $uri, array $response, ?array $requestData = null, ?string $note = null): void
{
    $results[] = [
        'method' => $method,
        'uri' => $uri,
        'display_uri' => normalizeRouteUri($uri),
        'request' => $requestData,
        'status' => $response['status'],
        'body' => $response['body'],
        'note' => $note,
    ];
}

function ensureAdmin(string $runId): Admin
{
    return Admin::query()->create([
        'slug' => 'apitest-admin-' . Str::lower(Str::random(8)),
        'name' => 'API Test Admin ' . Str::upper(Str::substr($runId, -4)),
        'email' => "apitest.admin.$runId@example.com",
        'phone' => '9000000001',
        'username' => 'apitestadmin' . Str::lower(Str::random(5)),
        'password' => Hash::make('Password123!'),
        'status' => 'active',
    ]);
}

function ensureCompany(string $runId): Company
{
    return Company::query()->create([
        'slug' => 'apitest-company-' . Str::lower(Str::random(8)),
        'code' => 'CMP-' . Str::upper(Str::random(5)),
        'name' => 'API Test Company ' . Str::upper(Str::substr($runId, -4)),
        'email' => "apitest.company.$runId@example.com",
        'phone' => '9000000002',
        'password' => Hash::make('Password123!'),
        'website' => 'https://example.test',
        'address' => 'Initial Address',
        'currency_code' => 'INR',
        'timezone' => 'Asia/Kolkata',
        'is_active' => true,
        'is_delete' => false,
    ]);
}

function ensureBranch(Company $company, string $runId): Branch
{
    return Branch::query()->create([
        'company_id' => $company->id,
        'code' => 'API-' . Str::upper(Str::random(4)),
        'name' => 'API Test Branch ' . Str::upper(Str::substr($runId, -4)),
        'slug' => 'apitest-branch-' . Str::lower(Str::random(8)),
        'email' => "branch.$runId@example.com",
        'phone' => '9000000003',
        'address_line1' => 'Line 1',
        'city' => 'Kolkata',
        'state' => 'West Bengal',
        'country' => 'India',
        'postal_code' => '700001',
        'google_map_link' => 'https://maps.google.com/?q=22.5726,88.3639',
        'is_head_office' => true,
        'is_active' => true,
    ]);
}

function ensureDepartment(string $runId): Department
{
    return Department::query()->create([
        'slug' => 'apitest-department-' . Str::lower(Str::random(8)),
        'code' => 'DPT-' . Str::upper(Str::random(4)),
        'name' => 'API Test Department ' . Str::upper(Str::substr($runId, -4)),
        'description' => 'Created by automated API route report',
        'level_no' => 1,
        'approval_mode' => 'single',
        'escalation_mode' => 'none',
        'can_create_tasks' => true,
        'can_receive_tasks' => true,
        'is_system_default' => false,
        'is_active' => true,
    ]);
}

function ensureBranchUser(Company $company, Branch $branch, Department $department, string $name, string $email, string $password, bool $isBranchAdmin, bool $isDeptAdmin): BranchUser
{
    return BranchUser::query()->create([
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'dept_id' => $department->id,
        'emp_id' => 'EMP-' . random_int(10000000, 99999999),
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'phone' => '9000000004',
        'slug' => Str::slug($name) . '-' . Str::lower(Str::random(6)),
        'is_dept_admin' => $isDeptAdmin,
        'is_branch_admin' => $isBranchAdmin,
        'is_active' => true,
        'is_delete' => false,
    ]);
}

$results = [];

$admin = ensureAdmin($runId);
$company = ensureCompany($runId);
$branch = ensureBranch($company, $runId);
$department = ensureDepartment($runId);

$branchAdmin = ensureBranchUser($company, $branch, $department, 'API Test Branch Admin', "branch.admin.$runId@example.com", 'Password123!', true, false);
$deptAdmin = ensureBranchUser($company, $branch, $department, 'API Test Dept Admin', "dept.admin.$runId@example.com", 'Password123!', false, true);

$adminLogin = sendRequest($httpKernel, 'POST', '/api/admin/login', [
    'login' => $admin->email,
    'password' => 'Password123!',
]);
recordResult($results, 'POST', '/api/admin/login', $adminLogin, [
    'login' => $admin->email,
    'password' => 'Password123!',
]);
$adminToken = $adminLogin['body']['data']['token'] ?? null;

$registerCompany = sendRequest($httpKernel, 'POST', '/api/register-company', [
    'name' => 'Self Register ' . Str::upper(Str::substr($runId, -4)),
    'email' => "register.company.$runId@example.com",
    'phone' => '9000001000',
    'password' => 'Password123!',
    'password_confirmation' => 'Password123!',
    'address' => 'Registration Address',
    'website' => 'https://register.example.test',
]);
recordResult($results, 'POST', '/api/register-company', $registerCompany, [
    'name' => 'Self Register ' . Str::upper(Str::substr($runId, -4)),
    'email' => "register.company.$runId@example.com",
]);

$companyLogin = sendRequest($httpKernel, 'POST', '/api/company/login', [
    'email' => $company->email,
    'password' => 'Password123!',
]);
recordResult($results, 'POST', '/api/company/login', $companyLogin, [
    'email' => $company->email,
    'password' => 'Password123!',
]);
$companyToken = $companyLogin['body']['data']['token'] ?? null;

$branchAdminLogin = sendRequest($httpKernel, 'POST', '/api/branch-admin/login', [
    'email' => $branchAdmin->email,
    'password' => 'Password123!',
]);
recordResult($results, 'POST', '/api/branch-admin/login', $branchAdminLogin, [
    'email' => $branchAdmin->email,
    'password' => 'Password123!',
]);
$branchAdminToken = $branchAdminLogin['body']['token'] ?? null;

$deptAdminLogin = sendRequest($httpKernel, 'POST', '/api/dept-admin/login', [
    'email' => $deptAdmin->email,
    'password' => 'Password123!',
]);
recordResult($results, 'POST', '/api/dept-admin/login', $deptAdminLogin, [
    'email' => $deptAdmin->email,
    'password' => 'Password123!',
]);
$deptAdminToken = $deptAdminLogin['body']['token'] ?? null;

$adminProfile = sendRequest($httpKernel, 'GET', '/api/admin/profile', [], $adminToken);
recordResult($results, 'GET', '/api/admin/profile', $adminProfile);

$adminProfileUpdate = sendRequest($httpKernel, 'PUT', '/api/admin/profile', [
    'phone' => '9000000101',
], $adminToken);
recordResult($results, 'PUT', '/api/admin/profile', $adminProfileUpdate, ['phone' => '9000000101']);

$adminsIndex = sendRequest($httpKernel, 'GET', '/api/admins', [], $adminToken);
recordResult($results, 'GET', '/api/admins', $adminsIndex);

$adminCreate = sendRequest($httpKernel, 'POST', '/api/admins', [
    'name' => 'Managed Admin ' . Str::upper(Str::substr($runId, -4)),
    'email' => "managed.admin.$runId@example.com",
    'phone' => '9000000102',
    'username' => 'managed' . Str::lower(Str::random(5)),
    'password' => 'Password123!',
    'password_confirmation' => 'Password123!',
    'status' => 'active',
], $adminToken);
recordResult($results, 'POST', '/api/admins', $adminCreate, [
    'email' => "managed.admin.$runId@example.com",
    'status' => 'active',
]);
$managedAdminSlug = $adminCreate['body']['data']['slug'] ?? 'missing-admin-slug';

$adminShow = sendRequest($httpKernel, 'GET', "/api/admins/{$managedAdminSlug}", [], $adminToken);
recordResult($results, 'GET', "/api/admins/{$managedAdminSlug}", $adminShow);

$adminUpdate = sendRequest($httpKernel, 'PUT', "/api/admins/{$managedAdminSlug}", [
    'name' => 'Managed Admin Updated ' . Str::upper(Str::substr($runId, -4)),
    'status' => 'inactive',
], $adminToken);
recordResult($results, 'PUT', "/api/admins/{$managedAdminSlug}", $adminUpdate, [
    'name' => 'Managed Admin Updated',
    'status' => 'inactive',
]);
$managedAdminSlug = $adminUpdate['body']['data']['slug'] ?? $managedAdminSlug;

$adminUpdatePost = sendRequest($httpKernel, 'POST', "/api/admins/{$managedAdminSlug}", [
    'phone' => '9000000103',
    'status' => 'active',
], $adminToken, false);
recordResult($results, 'POST', "/api/admins/{$managedAdminSlug}", $adminUpdatePost, [
    'phone' => '9000000103',
    'status' => 'active',
], 'POST variant for admin update');
$managedAdminSlug = $adminUpdatePost['body']['data']['slug'] ?? $managedAdminSlug;

$adminDelete = sendRequest($httpKernel, 'DELETE', "/api/admins/{$managedAdminSlug}", [], $adminToken);
recordResult($results, 'DELETE', "/api/admins/{$managedAdminSlug}", $adminDelete);

$adminRestore = sendRequest($httpKernel, 'POST', "/api/admins/{$managedAdminSlug}/restore", [], $adminToken);
recordResult($results, 'POST', "/api/admins/{$managedAdminSlug}/restore", $adminRestore);

$adminCompaniesIndex = sendRequest($httpKernel, 'GET', '/api/admin/companies', [], $adminToken);
recordResult($results, 'GET', '/api/admin/companies', $adminCompaniesIndex);

$adminCompanyCreate = sendRequest($httpKernel, 'POST', '/api/admin/companies', [
    'name' => 'Admin Managed Company ' . Str::upper(Str::substr($runId, -4)),
    'email' => "admin.company.$runId@example.com",
    'phone' => '9000000201',
    'password' => 'Password123!',
    'password_confirmation' => 'Password123!',
    'legal_name' => 'Admin Managed Company Pvt Ltd',
    'website' => 'https://admin-company.example.test',
    'currency_code' => 'INR',
    'timezone' => 'Asia/Kolkata',
    'is_active' => true,
], $adminToken);
recordResult($results, 'POST', '/api/admin/companies', $adminCompanyCreate, [
    'email' => "admin.company.$runId@example.com",
]);
$adminCompanySlug = $adminCompanyCreate['body']['data']['slug'] ?? 'missing-admin-company-slug';

$adminCompanyShow = sendRequest($httpKernel, 'GET', "/api/admin/companies/{$adminCompanySlug}", [], $adminToken);
recordResult($results, 'GET', "/api/admin/companies/{$adminCompanySlug}", $adminCompanyShow);

$adminCompanyUpdate = sendRequest($httpKernel, 'PUT', "/api/admin/companies/{$adminCompanySlug}", [
    'name' => 'Admin Company Updated ' . Str::upper(Str::substr($runId, -4)),
    'is_active' => false,
], $adminToken);
recordResult($results, 'PUT', "/api/admin/companies/{$adminCompanySlug}", $adminCompanyUpdate, [
    'name' => 'Admin Company Updated',
    'is_active' => false,
]);
$adminCompanySlug = $adminCompanyUpdate['body']['data']['slug'] ?? $adminCompanySlug;

$adminCompanyDelete = sendRequest($httpKernel, 'DELETE', "/api/admin/companies/{$adminCompanySlug}", [], $adminToken);
recordResult($results, 'DELETE', "/api/admin/companies/{$adminCompanySlug}", $adminCompanyDelete);

$companiesIndex = sendRequest($httpKernel, 'GET', '/api/companies', [], $adminToken);
recordResult($results, 'GET', '/api/companies', $companiesIndex, null, 'Shared route tested with admin token');

$sharedCompanyCreate = sendRequest($httpKernel, 'POST', '/api/companies', [
    'code' => 'SHR-' . Str::upper(Str::random(5)),
    'name' => 'Shared Company ' . Str::upper(Str::substr($runId, -4)),
    'email' => "shared.company.$runId@example.com",
    'phone' => '9000000202',
    'password' => 'Password123!',
    'password_confirmation' => 'Password123!',
    'website' => 'https://shared.example.test',
    'currency_code' => 'INR',
    'timezone' => 'Asia/Kolkata',
    'is_active' => true,
], $adminToken);
recordResult($results, 'POST', '/api/companies', $sharedCompanyCreate, [
    'email' => "shared.company.$runId@example.com",
], 'Shared route tested with admin token');
$sharedCompanySlug = $sharedCompanyCreate['body']['data']['slug'] ?? 'missing-shared-company-slug';

$sharedCompanyShow = sendRequest($httpKernel, 'GET', "/api/companies/{$sharedCompanySlug}", [], $adminToken);
recordResult($results, 'GET', "/api/companies/{$sharedCompanySlug}", $sharedCompanyShow);

$sharedCompanyUpdate = sendRequest($httpKernel, 'PUT', "/api/companies/{$sharedCompanySlug}", [
    'name' => 'Shared Company Updated ' . Str::upper(Str::substr($runId, -4)),
    'phone' => '9000000203',
], $adminToken);
recordResult($results, 'PUT', "/api/companies/{$sharedCompanySlug}", $sharedCompanyUpdate, [
    'name' => 'Shared Company Updated',
    'phone' => '9000000203',
]);
$sharedCompanySlug = $sharedCompanyUpdate['body']['data']['slug'] ?? $sharedCompanySlug;

$sharedCompanyUpdatePost = sendRequest($httpKernel, 'POST', "/api/companies/{$sharedCompanySlug}", [
    'phone' => '9000000204',
], $adminToken, false);
recordResult($results, 'POST', "/api/companies/{$sharedCompanySlug}", $sharedCompanyUpdatePost, [
    'phone' => '9000000204',
], 'POST variant for shared company update');
$sharedCompanySlug = $sharedCompanyUpdatePost['body']['data']['slug'] ?? $sharedCompanySlug;

$sharedCompanyDelete = sendRequest($httpKernel, 'DELETE', "/api/companies/{$sharedCompanySlug}", [], $adminToken);
recordResult($results, 'DELETE', "/api/companies/{$sharedCompanySlug}", $sharedCompanyDelete);

$companyProfile = sendRequest($httpKernel, 'GET', '/api/company/profile', [], $companyToken);
recordResult($results, 'GET', '/api/company/profile', $companyProfile);

$companyProfileUpdate = sendRequest($httpKernel, 'PUT', '/api/company/profile', [
    'phone' => '9000000301',
    'address' => 'Updated Address',
], $companyToken);
recordResult($results, 'PUT', '/api/company/profile', $companyProfileUpdate, [
    'phone' => '9000000301',
    'address' => 'Updated Address',
]);

$branchesIndex = sendRequest($httpKernel, 'GET', '/api/company/branches', [], $companyToken);
recordResult($results, 'GET', '/api/company/branches', $branchesIndex);

$branchCreate = sendRequest($httpKernel, 'POST', '/api/company/branches', [
    'code' => 'BR-' . Str::upper(Str::random(4)),
    'name' => 'Managed Branch ' . Str::upper(Str::substr($runId, -4)),
    'email' => "managed.branch.$runId@example.com",
    'phone' => '9000000302',
    'address_line1' => 'Branch Street',
    'city' => 'Kolkata',
    'state' => 'West Bengal',
    'country' => 'India',
    'postal_code' => '700002',
    'google_map_link' => 'https://maps.google.com/?q=22.57,88.36',
    'is_head_office' => false,
    'is_active' => true,
], $companyToken);
recordResult($results, 'POST', '/api/company/branches', $branchCreate, [
    'name' => 'Managed Branch',
]);
$managedBranchSlug = $branchCreate['body']['data']['slug'] ?? 'missing-branch-slug';

$branchShow = sendRequest($httpKernel, 'GET', "/api/company/branches/{$managedBranchSlug}", [], $companyToken);
recordResult($results, 'GET', "/api/company/branches/{$managedBranchSlug}", $branchShow);

$branchUpdate = sendRequest($httpKernel, 'PUT', "/api/company/branches/{$managedBranchSlug}", [
    'name' => 'Managed Branch Updated ' . Str::upper(Str::substr($runId, -4)),
    'is_active' => false,
], $companyToken);
recordResult($results, 'PUT', "/api/company/branches/{$managedBranchSlug}", $branchUpdate, [
    'name' => 'Managed Branch Updated',
    'is_active' => false,
]);
$managedBranchSlug = $branchUpdate['body']['data']['slug'] ?? $managedBranchSlug;

$branchDelete = sendRequest($httpKernel, 'DELETE', "/api/company/branches/{$managedBranchSlug}", [], $companyToken);
recordResult($results, 'DELETE', "/api/company/branches/{$managedBranchSlug}", $branchDelete);

$featuresIndex = sendRequest($httpKernel, 'GET', '/api/company/features', [], $companyToken);
recordResult($results, 'GET', '/api/company/features', $featuresIndex);

$featureCreate = sendRequest($httpKernel, 'POST', '/api/company/features', [
    'code' => 'FEAT-' . Str::upper(Str::random(4)),
    'name' => 'Managed Feature ' . Str::upper(Str::substr($runId, -4)),
    'category' => 'operations',
    'description' => 'Feature for route report',
    'icon' => 'settings',
    'sort_order' => 1,
    'is_system' => false,
    'is_active' => true,
], $companyToken);
recordResult($results, 'POST', '/api/company/features', $featureCreate, [
    'category' => 'operations',
]);
$featureSlug = $featureCreate['body']['data']['slug'] ?? 'missing-feature-slug';
$featureId = $featureCreate['body']['data']['id'] ?? null;

$featureShow = sendRequest($httpKernel, 'GET', "/api/company/features/{$featureSlug}", [], $companyToken);
recordResult($results, 'GET', "/api/company/features/{$featureSlug}", $featureShow);

$featureUpdate = sendRequest($httpKernel, 'PUT', "/api/company/features/{$featureSlug}", [
    'name' => 'Managed Feature Updated ' . Str::upper(Str::substr($runId, -4)),
    'sort_order' => 2,
], $companyToken);
recordResult($results, 'PUT', "/api/company/features/{$featureSlug}", $featureUpdate, [
    'name' => 'Managed Feature Updated',
    'sort_order' => 2,
]);
$featureSlug = $featureUpdate['body']['data']['slug'] ?? $featureSlug;

$companyDepartmentsIndex = sendRequest($httpKernel, 'GET', '/api/company/departments', [], $companyToken);
recordResult($results, 'GET', '/api/company/departments', $companyDepartmentsIndex);

$companyDepartmentShow = sendRequest($httpKernel, 'GET', "/api/company/departments/{$department->slug}", [], $companyToken);
recordResult($results, 'GET', "/api/company/departments/{$department->slug}", $companyDepartmentShow);

$departmentFeaturesIndex = sendRequest($httpKernel, 'GET', '/api/company/department-features', [], $companyToken);
recordResult($results, 'GET', '/api/company/department-features', $departmentFeaturesIndex);

$departmentFeatureCreate = sendRequest($httpKernel, 'POST', '/api/company/department-features', [
    'department_id' => $department->id,
    'feature_id' => $featureId,
    'access_level' => 'full',
    'is_enabled' => true,
], $companyToken);
recordResult($results, 'POST', '/api/company/department-features', $departmentFeatureCreate, [
    'department_id' => $department->id,
    'feature_id' => $featureId,
]);
$departmentFeatureSlug = $departmentFeatureCreate['body']['data']['slug'] ?? 'missing-dept-feature-slug';

$departmentFeatureShow = sendRequest($httpKernel, 'GET', "/api/company/department-features/{$departmentFeatureSlug}", [], $companyToken);
recordResult($results, 'GET', "/api/company/department-features/{$departmentFeatureSlug}", $departmentFeatureShow);

$departmentFeatureUpdate = sendRequest($httpKernel, 'PUT', "/api/company/department-features/{$departmentFeatureSlug}", [
    'access_level' => 'approve',
    'is_enabled' => false,
], $companyToken);
recordResult($results, 'PUT', "/api/company/department-features/{$departmentFeatureSlug}", $departmentFeatureUpdate, [
    'access_level' => 'approve',
    'is_enabled' => false,
]);
$departmentFeatureSlug = $departmentFeatureUpdate['body']['data']['slug'] ?? $departmentFeatureSlug;

$settingsIndex = sendRequest($httpKernel, 'GET', '/api/company/settings', [], $companyToken);
recordResult($results, 'GET', '/api/company/settings', $settingsIndex);

$settingCreate = sendRequest($httpKernel, 'POST', '/api/company/settings', [
    'setting_group' => 'company',
    'setting_key' => 'timezone-' . Str::lower(Str::random(4)),
    'setting_value' => 'Asia/Kolkata',
    'value_type' => 'string',
    'branch_id' => $branch->id,
    'is_public' => false,
], $companyToken);
recordResult($results, 'POST', '/api/company/settings', $settingCreate, [
    'setting_group' => 'company',
]);
$settingSlug = $settingCreate['body']['data']['slug'] ?? 'missing-setting-slug';

$settingShow = sendRequest($httpKernel, 'GET', "/api/company/settings/{$settingSlug}", [], $companyToken);
recordResult($results, 'GET', "/api/company/settings/{$settingSlug}", $settingShow);

$settingUpdate = sendRequest($httpKernel, 'PUT', "/api/company/settings/{$settingSlug}", [
    'setting_value' => 'Asia/Calcutta',
    'is_public' => true,
], $companyToken);
recordResult($results, 'PUT', "/api/company/settings/{$settingSlug}", $settingUpdate, [
    'setting_value' => 'Asia/Calcutta',
    'is_public' => true,
]);
$settingSlug = $settingUpdate['body']['data']['slug'] ?? $settingSlug;

$rolesIndex = sendRequest($httpKernel, 'GET', '/api/company/roles', [], $companyToken);
recordResult($results, 'GET', '/api/company/roles', $rolesIndex);

$roleCreate = sendRequest($httpKernel, 'POST', '/api/company/roles', [
    'name' => 'Managed Role ' . Str::upper(Str::substr($runId, -4)),
    'description' => 'Role for API route report',
    'is_active' => true,
], $companyToken);
recordResult($results, 'POST', '/api/company/roles', $roleCreate, [
    'name' => 'Managed Role',
]);
$roleSlug = $roleCreate['body']['data']['slug'] ?? 'missing-role-slug';

$roleShow = sendRequest($httpKernel, 'GET', "/api/company/roles/{$roleSlug}", [], $companyToken);
recordResult($results, 'GET', "/api/company/roles/{$roleSlug}", $roleShow);

$roleUpdate = sendRequest($httpKernel, 'PUT', "/api/company/roles/{$roleSlug}", [
    'name' => 'Managed Role Updated ' . Str::upper(Str::substr($runId, -4)),
    'is_active' => false,
], $companyToken);
recordResult($results, 'PUT', "/api/company/roles/{$roleSlug}", $roleUpdate, [
    'name' => 'Managed Role Updated',
    'is_active' => false,
]);
$roleSlug = $roleUpdate['body']['data']['slug'] ?? $roleSlug;

$branchUsersIndex = sendRequest($httpKernel, 'GET', '/api/company/branch-users', [], $companyToken);
recordResult($results, 'GET', '/api/company/branch-users', $branchUsersIndex);

$branchUserCreate = sendRequest($httpKernel, 'POST', '/api/company/branch-users', [
    'branch_id' => $branch->id,
    'dept_id' => $department->id,
    'name' => 'Managed Branch User ' . Str::upper(Str::substr($runId, -4)),
    'email' => "managed.branchuser.$runId@example.com",
    'password' => 'Password123!',
    'phone' => '9000000401',
    'is_active' => true,
    'is_dept_admin' => false,
    'is_branch_admin' => false,
], $companyToken);
recordResult($results, 'POST', '/api/company/branch-users', $branchUserCreate, [
    'email' => "managed.branchuser.$runId@example.com",
]);
$branchUserSlug = $branchUserCreate['body']['data']['slug'] ?? 'missing-branch-user-slug';

$branchUserShow = sendRequest($httpKernel, 'GET', "/api/company/branch-users/{$branchUserSlug}", [], $companyToken);
recordResult($results, 'GET', "/api/company/branch-users/{$branchUserSlug}", $branchUserShow);

$branchUserUpdate = sendRequest($httpKernel, 'PUT', "/api/company/branch-users/{$branchUserSlug}", [
    'name' => 'Managed Branch User Updated ' . Str::upper(Str::substr($runId, -4)),
    'phone' => '9000000402',
    'is_active' => false,
], $companyToken);
recordResult($results, 'PUT', "/api/company/branch-users/{$branchUserSlug}", $branchUserUpdate, [
    'name' => 'Managed Branch User Updated',
    'phone' => '9000000402',
]);
$branchUserSlug = $branchUserUpdate['body']['data']['slug'] ?? $branchUserSlug;

$branchUserPasswordChange = sendRequest($httpKernel, 'POST', "/api/company/branch-users/{$branchUserSlug}/change-password", [
    'new_password' => 'Password456!',
    'confirm_password' => 'Password456!',
], $companyToken);
recordResult($results, 'POST', "/api/company/branch-users/{$branchUserSlug}/change-password", $branchUserPasswordChange, [
    'new_password' => 'Password456!',
]);

$branchAdminDepartments = sendRequest($httpKernel, 'GET', '/api/departments', [], $branchAdminToken);
recordResult($results, 'GET', '/api/departments', $branchAdminDepartments, null, 'Branch admin route');

$branchEmployeesCreate = sendRequest($httpKernel, 'POST', '/api/branch/employees', [
    'name' => 'Branch Employee ' . Str::upper(Str::substr($runId, -4)),
    'email' => "branch.employee.$runId@example.com",
    'password' => 'Password123!',
    'phone' => '9000000501',
    'dept_id' => $department->id,
], $branchAdminToken);
recordResult($results, 'POST', '/api/branch/employees', $branchEmployeesCreate, [
    'email' => "branch.employee.$runId@example.com",
]);
$branchEmployeeSlug = $branchEmployeesCreate['body']['data']['slug'] ?? 'missing-branch-employee-slug';

$branchEmployeesIndex = sendRequest($httpKernel, 'GET', '/api/branch/employees', [], $branchAdminToken);
recordResult($results, 'GET', '/api/branch/employees', $branchEmployeesIndex);

$branchEmployeeShow = sendRequest($httpKernel, 'GET', "/api/branch/employees/{$branchEmployeeSlug}", [], $branchAdminToken);
recordResult($results, 'GET', "/api/branch/employees/{$branchEmployeeSlug}", $branchEmployeeShow);

$branchEmployeeUpdate = sendRequest($httpKernel, 'PUT', "/api/branch/employees/{$branchEmployeeSlug}", [
    'name' => 'Branch Employee Updated ' . Str::upper(Str::substr($runId, -4)),
    'phone' => '9000000502',
    'is_active' => false,
], $branchAdminToken);
recordResult($results, 'PUT', "/api/branch/employees/{$branchEmployeeSlug}", $branchEmployeeUpdate, [
    'name' => 'Branch Employee Updated',
    'phone' => '9000000502',
]);
$branchEmployeeSlug = $branchEmployeeUpdate['body']['data']['slug'] ?? $branchEmployeeSlug;

$branchEmployeeUpdatePost = sendRequest($httpKernel, 'POST', "/api/branch/employees/{$branchEmployeeSlug}", [
    'phone' => '9000000503',
    'is_active' => true,
], $branchAdminToken, false);
recordResult($results, 'POST', "/api/branch/employees/{$branchEmployeeSlug}", $branchEmployeeUpdatePost, [
    'phone' => '9000000503',
    'is_active' => true,
], 'POST variant for branch employee update');
$branchEmployeeSlug = $branchEmployeeUpdatePost['body']['data']['slug'] ?? $branchEmployeeSlug;

$deptEmployeesCreate = sendRequest($httpKernel, 'POST', '/api/dept/employees', [
    'name' => 'Dept Employee ' . Str::upper(Str::substr($runId, -4)),
    'email' => "dept.employee.$runId@example.com",
    'password' => 'Password123!',
    'phone' => '9000000601',
], $deptAdminToken);
recordResult($results, 'POST', '/api/dept/employees', $deptEmployeesCreate, [
    'email' => "dept.employee.$runId@example.com",
]);
$deptEmployeeSlug = $deptEmployeesCreate['body']['data']['slug'] ?? 'missing-dept-employee-slug';

$deptEmployeesIndex = sendRequest($httpKernel, 'GET', '/api/dept/employees', [], $deptAdminToken);
recordResult($results, 'GET', '/api/dept/employees', $deptEmployeesIndex);

$deptEmployeeShow = sendRequest($httpKernel, 'GET', "/api/dept/employees/{$deptEmployeeSlug}", [], $deptAdminToken);
recordResult($results, 'GET', "/api/dept/employees/{$deptEmployeeSlug}", $deptEmployeeShow);

$deptEmployeeUpdate = sendRequest($httpKernel, 'PUT', "/api/dept/employees/{$deptEmployeeSlug}", [
    'name' => 'Dept Employee Updated ' . Str::upper(Str::substr($runId, -4)),
    'phone' => '9000000602',
    'is_active' => true,
], $deptAdminToken);
recordResult($results, 'PUT', "/api/dept/employees/{$deptEmployeeSlug}", $deptEmployeeUpdate, [
    'name' => 'Dept Employee Updated',
    'phone' => '9000000602',
]);
$deptEmployeeSlug = $deptEmployeeUpdate['body']['data']['slug'] ?? $deptEmployeeSlug;

$deptEmployeeLogin = sendRequest($httpKernel, 'POST', '/api/dept-employee/login', [
    'email' => "dept.employee.$runId@example.com",
    'password' => 'Password123!',
]);
recordResult($results, 'POST', '/api/dept-employee/login', $deptEmployeeLogin, [
    'email' => "dept.employee.$runId@example.com",
]);
$deptEmployeeToken = $deptEmployeeLogin['body']['token'] ?? null;

$deptEmployeePasswordChange = sendRequest($httpKernel, 'POST', '/api/dept-employee/change-password', [
    'current_password' => 'Password123!',
    'new_password' => 'Password456!',
    'new_password_confirmation' => 'Password456!',
], $deptEmployeeToken);
recordResult($results, 'POST', '/api/dept-employee/change-password', $deptEmployeePasswordChange, [
    'current_password' => 'Password123!',
]);

$deptEmployeeLogout = sendRequest($httpKernel, 'POST', '/api/dept-employee/logout', [], $deptEmployeeToken);
recordResult($results, 'POST', '/api/dept-employee/logout', $deptEmployeeLogout);

$deptEmployeeDelete = sendRequest($httpKernel, 'DELETE', "/api/dept/employees/{$deptEmployeeSlug}", [], $deptAdminToken);
recordResult($results, 'DELETE', "/api/dept/employees/{$deptEmployeeSlug}", $deptEmployeeDelete);

$deptAdminLogout = sendRequest($httpKernel, 'POST', '/api/dept-admin/logout', [], $deptAdminToken);
recordResult($results, 'POST', '/api/dept-admin/logout', $deptAdminLogout);

$branchEmployeeDelete = sendRequest($httpKernel, 'DELETE', "/api/branch/employees/{$branchEmployeeSlug}", [], $branchAdminToken);
recordResult($results, 'DELETE', "/api/branch/employees/{$branchEmployeeSlug}", $branchEmployeeDelete);

$branchAdminLogout = sendRequest($httpKernel, 'POST', '/api/branch-admin/logout', [], $branchAdminToken);
recordResult($results, 'POST', '/api/branch-admin/logout', $branchAdminLogout);

$departmentFeatureDelete = sendRequest($httpKernel, 'DELETE', "/api/company/department-features/{$departmentFeatureSlug}", [], $companyToken);
recordResult($results, 'DELETE', "/api/company/department-features/{$departmentFeatureSlug}", $departmentFeatureDelete);

$featureDelete = sendRequest($httpKernel, 'DELETE', "/api/company/features/{$featureSlug}", [], $companyToken);
recordResult($results, 'DELETE', "/api/company/features/{$featureSlug}", $featureDelete);

$settingDelete = sendRequest($httpKernel, 'DELETE', "/api/company/settings/{$settingSlug}", [], $companyToken);
recordResult($results, 'DELETE', "/api/company/settings/{$settingSlug}", $settingDelete);

$roleDelete = sendRequest($httpKernel, 'DELETE', "/api/company/roles/{$roleSlug}", [], $companyToken);
recordResult($results, 'DELETE', "/api/company/roles/{$roleSlug}", $roleDelete);

$branchUserDelete = sendRequest($httpKernel, 'DELETE', "/api/company/branch-users/{$branchUserSlug}", [], $companyToken);
recordResult($results, 'DELETE', "/api/company/branch-users/{$branchUserSlug}", $branchUserDelete);

$companyPasswordChange = sendRequest($httpKernel, 'POST', '/api/company/change-password', [
    'current_password' => 'Password123!',
    'new_password' => 'Password456!',
    'new_password_confirmation' => 'Password456!',
], $companyToken);
recordResult($results, 'POST', '/api/company/change-password', $companyPasswordChange, [
    'current_password' => 'Password123!',
]);

$companyLogout = sendRequest($httpKernel, 'POST', '/api/company/logout', [], $companyToken);
recordResult($results, 'POST', '/api/company/logout', $companyLogout);

$adminLogout = sendRequest($httpKernel, 'POST', '/api/admin/logout', [], $adminToken);
recordResult($results, 'POST', '/api/admin/logout', $adminLogout);

$totalRoutes = count($results);
$successCount = count(array_filter($results, fn (array $result): bool => $result['status'] >= 200 && $result['status'] < 300));

$markdown = [];
$markdown[] = '# API Route Test Report';
$markdown[] = '';
$markdown[] = '- Generated at: ' . now()->toDateTimeString();
$markdown[] = '- Run ID: `' . $runId . '`';
$markdown[] = '- Total route calls captured: `' . $totalRoutes . '`';
$markdown[] = '- 2xx responses: `' . $successCount . '`';
$markdown[] = '';
$markdown[] = '## Summary';
$markdown[] = '';
$markdown[] = '| # | Method | URI | Status |';
$markdown[] = '|---|---|---|---|';

foreach ($results as $index => $result) {
    $markdown[] = '| ' . ($index + 1) . ' | `' . $result['method'] . '` | `' . $result['display_uri'] . '` | `' . $result['status'] . '` |';
}

foreach ($results as $index => $result) {
    $markdown[] = '';
    $markdown[] = '## ' . ($index + 1) . '. ' . $result['method'] . ' ' . $result['display_uri'];
    $markdown[] = '';
    $markdown[] = '- Status: `' . $result['status'] . '`';

    if ($result['note'] !== null) {
        $markdown[] = '- Note: ' . $result['note'];
    }

    if ($result['request'] !== null) {
        $markdown[] = '- Request payload:';
        $markdown[] = '```json';
        $markdown[] = prettyJson($result['request']);
        $markdown[] = '```';
    }

    $markdown[] = '- Response body:';
    $markdown[] = '```json';
    $markdown[] = prettyJson($result['body']);
    $markdown[] = '```';
}

file_put_contents($reportPath, implode(PHP_EOL, $markdown) . PHP_EOL);

echo "Report written to: {$reportPath}" . PHP_EOL;
echo "Captured {$totalRoutes} route calls." . PHP_EOL;
