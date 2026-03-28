<?php

declare(strict_types=1);

use App\Models\Admin;
use App\Models\Attendance;
use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\Company;
use App\Models\Department;
use App\Models\DepartmentFeature;
use App\Models\Feature;
use App\Models\Role;
use App\Models\SystemSetting;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$reportPath = __DIR__ . '/../API_ROUTE_RESPONSES.md';
$tag = 'codex-' . now()->format('Ymd-His');

function uniqueValue(string $prefix): string
{
    return $prefix . '-' . Str::lower(Str::random(6));
}

function ensureAdminFixture(): Admin
{
    Admin::withTrashed()->where('email', 'admin@example.com')->restore();

    return Admin::updateOrCreate(
        ['email' => 'admin@example.com'],
        [
            'slug' => 'system-admin',
            'name' => 'System Admin',
            'username' => 'admin',
            'phone' => '9999999999',
            'password' => 'password123',
            'status' => 'active',
            'deleted_at' => null,
        ]
    );
}

function createDepartmentFixture(string $tag, ?int $companyId = null, ?int $branchId = null): Department
{
    $data = [
        'slug' => uniqueValue($tag . '-department'),
        'code' => strtoupper(Str::substr(Str::replace('-', '', uniqueValue('DPT')), 0, 10)),
        'name' => 'Codex Department ' . Str::upper(Str::random(4)),
        'description' => 'API route test department',
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

function createBranchUserFixture(array $overrides = []): BranchUser
{
    $company = $overrides['company'] ?? null;
    $branch = $overrides['branch'] ?? null;
    $department = $overrides['department'] ?? null;

    unset($overrides['company'], $overrides['branch'], $overrides['department']);

    $name = $overrides['name'] ?? ('Codex User ' . Str::upper(Str::random(4)));

    $data = array_merge([
        'company_id' => $company?->id,
        'branch_id' => $branch?->id,
        'dept_id' => $department?->id,
        'emp_id' => 'EMP-' . random_int(10000000, 99999999),
        'name' => $name,
        'email' => uniqueValue('user') . '@example.com',
        'password' => $overrides['password'] ?? 'password123',
        'phone' => '9876543210',
        'slug' => Str::slug($name) . '-' . Str::lower(Str::random(5)),
        'is_dept_admin' => false,
        'is_branch_admin' => false,
        'is_active' => true,
        'is_delete' => false,
        'created_by' => $company?->id,
    ], $overrides);

    return BranchUser::create($data);
}

function createDisposableBranch(Company $company, string $tag): Branch
{
    return Branch::create([
        'company_id' => $company->id,
        'code' => strtoupper(Str::substr(Str::replace('-', '', uniqueValue('BR')), 0, 10)),
        'name' => 'Disposable Branch ' . Str::upper(Str::random(4)),
        'slug' => uniqueValue($tag . '-branch'),
        'email' => uniqueValue('branch') . '@example.com',
        'phone' => '8888888888',
        'address_line1' => 'Disposable branch address',
        'city' => 'Kolkata',
        'state' => 'West Bengal',
        'country' => 'India',
        'postal_code' => '700001',
        'is_head_office' => false,
        'is_active' => true,
    ]);
}

function decodeResponseBody(string $content)
{
    if ($content === '') {
        return null;
    }

    $decoded = json_decode($content, true);

    return json_last_error() === JSON_ERROR_NONE ? $decoded : $content;
}

function dispatchRequest(string $method, string $uri, array $data = [], ?string $token = null): array
{
    global $app;

    /** @var HttpKernel $kernel */
    $kernel = $app->make(HttpKernel::class);

    $request = Request::create($uri, $method, $data);
    $request->headers->set('Accept', 'application/json');
    $request->headers->set('User-Agent', 'Codex API Route Tester');

    if ($token) {
        $request->headers->set('Authorization', 'Bearer ' . $token);
    }

    $response = $kernel->handle($request);
    $content = $response->getContent();
    $kernel->terminate($request, $response);

    return [
        'status' => $response->getStatusCode(),
        'body' => decodeResponseBody($content),
    ];
}

$results = [];
$tokens = [];
$state = [];
$routeTemplates = [];

$setTemplate = function (string $uri, string $template) use (&$routeTemplates): void {
    if ($uri !== '') {
        $routeTemplates[$uri] = $template;
    }
};

$record = function (string $label, string $method, string $uri, array $data = [], ?string $token = null) use (&$results): array {
    $response = dispatchRequest($method, $uri, $data, $token);
    $results[] = [
        'label' => $label,
        'method' => $method,
        'uri' => $uri,
        'request' => $data,
        'status' => $response['status'],
        'response' => $response['body'],
    ];

    return $response;
};

ensureAdminFixture();

$adminLogin = $record('Admin login', 'POST', '/api/admin/login', [
    'login' => 'admin@example.com',
    'password' => 'password123',
]);
$tokens['admin'] = $adminLogin['body']['data']['token'] ?? null;

$companyRegisterPayload = [
    'name' => 'Codex Company ' . Str::upper(Str::random(4)),
    'email' => uniqueValue('company') . '@example.com',
    'phone' => '9000000001',
    'password' => 'secret123',
    'password_confirmation' => 'secret123',
    'address' => '123 Codex Street',
    'website' => 'https://example.com',
];
$companyRegister = $record('Register company', 'POST', '/api/register-company', $companyRegisterPayload);
$state['company_slug'] = $companyRegister['body']['data']['slug'] ?? null;

$companyLogin = $record('Company login', 'POST', '/api/company/login', [
    'email' => $companyRegisterPayload['email'],
    'password' => 'secret123',
]);
$tokens['company'] = $companyLogin['body']['data']['token'] ?? null;
$company = Company::where('email', $companyRegisterPayload['email'])->first();

if (!$company) {
    throw new RuntimeException('Primary company fixture could not be created.');
}

$primaryBranchCreate = $record('Create branch', 'POST', '/api/company/branches', [
    'code' => strtoupper(Str::substr(Str::replace('-', '', uniqueValue('BR')), 0, 10)),
    'name' => 'Main Branch ' . Str::upper(Str::random(4)),
    'email' => uniqueValue('main-branch') . '@example.com',
    'phone' => '9000000002',
    'address_line1' => 'Main branch address',
    'city' => 'Kolkata',
    'state' => 'West Bengal',
    'country' => 'India',
    'postal_code' => '700001',
    'is_head_office' => true,
    'is_active' => true,
], $tokens['company']);
$state['primary_branch_slug'] = $primaryBranchCreate['body']['data']['slug'] ?? null;
$primaryBranch = $state['primary_branch_slug']
    ? Branch::where('slug', $state['primary_branch_slug'])->first()
    : null;

if (!$primaryBranch) {
    $primaryBranch = createDisposableBranch($company, $tag . '-primary');
    $state['primary_branch_slug'] = $primaryBranch->slug;
}

$department = createDepartmentFixture($tag, $company->id, $primaryBranch->id);
$state['department_slug'] = $department->slug;

$primaryFeatureCreate = $record('Create feature', 'POST', '/api/company/features', [
    'code' => strtoupper(Str::substr(Str::replace('-', '', uniqueValue('FT')), 0, 10)),
    'name' => 'Primary Feature ' . Str::upper(Str::random(4)),
    'category' => 'operations',
    'description' => 'Primary feature for API tests',
    'icon' => 'settings',
    'sort_order' => 1,
    'is_system' => false,
    'is_active' => true,
], $tokens['company']);
$state['feature_slug'] = $primaryFeatureCreate['body']['data']['slug'] ?? null;
$feature = $state['feature_slug']
    ? Feature::where('slug', $state['feature_slug'])->first()
    : null;

if (!$feature) {
    $feature = Feature::create([
        'code' => strtoupper(Str::substr(Str::replace('-', '', uniqueValue('FT')), 0, 10)),
        'name' => 'Fallback Feature ' . Str::upper(Str::random(4)),
        'category' => 'operations',
        'description' => 'Fallback feature',
        'slug' => uniqueValue($tag . '-feature'),
        'icon' => 'settings',
        'sort_order' => 2,
        'is_system' => false,
        'is_active' => true,
    ]);
    $state['feature_slug'] = $feature->slug;
}

$roleCreate = $record('Create role', 'POST', '/api/company/roles', [
    'name' => 'Supervisor ' . Str::upper(Str::random(4)),
    'description' => 'Role for route testing',
    'is_active' => true,
], $tokens['company']);
$state['role_slug'] = $roleCreate['body']['data']['slug'] ?? null;
$role = $state['role_slug']
    ? Role::where('slug', $state['role_slug'])->first()
    : null;

if (!$role) {
    $role = Role::create([
        'company_id' => $company->id,
        'name' => 'Fallback Role ' . Str::upper(Str::random(4)),
        'slug' => uniqueValue($tag . '-role'),
        'description' => 'Fallback role',
        'is_active' => true,
    ]);
    $state['role_slug'] = $role->slug;
}

$settingCreate = $record('Create system setting', 'POST', '/api/company/settings', [
    'setting_group' => 'general',
    'setting_key' => uniqueValue('timezone'),
    'setting_value' => 'Asia/Calcutta',
    'value_type' => 'string',
    'branch_id' => $primaryBranch->id,
    'is_public' => true,
], $tokens['company']);
$state['setting_slug'] = $settingCreate['body']['data']['slug'] ?? null;

if (!$state['setting_slug'] && class_exists(SystemSetting::class)) {
    try {
        $setting = SystemSetting::create([
            'company_id' => $company->id,
            'branch_id' => $primaryBranch->id,
            'setting_group' => 'general',
            'setting_key' => uniqueValue('fallback-key'),
            'setting_value' => 'fallback',
            'value_type' => 'string',
            'slug' => uniqueValue($tag . '-setting'),
            'is_public' => true,
        ]);
        $state['setting_slug'] = $setting->slug;
    } catch (Throwable) {
    }
}

$departmentFeatureCreate = $record('Create department feature mapping', 'POST', '/api/company/department-features', [
    'department_id' => $department->id,
    'feature_id' => $feature->id,
    'access_level' => 'full',
    'is_enabled' => true,
], $tokens['company']);
$state['department_feature_slug'] = $departmentFeatureCreate['body']['data']['slug'] ?? null;

if (!$state['department_feature_slug']) {
    try {
        $mapping = DepartmentFeature::create([
            'department_id' => $department->id,
            'feature_id' => $feature->id,
            'slug' => uniqueValue($tag . '-mapping'),
            'access_level' => 'full',
            'is_enabled' => true,
            'assigned_by' => $company->id,
            'assigned_at' => now(),
        ]);
        $state['department_feature_slug'] = $mapping->slug;
    } catch (Throwable) {
    }
}

$companyRouteCreate = $record('Company CRUD create via /api/companies', 'POST', '/api/companies', [
    'name' => 'Managed Company ' . Str::upper(Str::random(4)),
    'code' => strtoupper(Str::substr(Str::replace('-', '', uniqueValue('CMP')), 0, 12)),
    'email' => uniqueValue('managed-company') . '@example.com',
    'password' => 'password123',
    'phone' => '9123456789',
    'currency_code' => 'INR',
    'timezone' => 'Asia/Calcutta',
    'address_line1' => 'Managed company address',
    'city' => 'Kolkata',
    'state' => 'West Bengal',
    'country' => 'India',
    'postal_code' => '700001',
    'is_active' => true,
], $tokens['company']);
$state['managed_company_slug'] = $companyRouteCreate['body']['data']['slug'] ?? null;
$setTemplate('/api/companies/' . ($state['managed_company_slug'] ?? ''), '/api/companies/{slug}');

$adminCompanyCreate = $record('Admin company CRUD create via /api/admin/companies', 'POST', '/api/admin/companies', [
    'name' => 'Admin Managed ' . Str::upper(Str::random(4)),
    'email' => uniqueValue('admin-company') . '@example.com',
    'phone' => '9234567890',
    'password' => 'secret123',
    'password_confirmation' => 'secret123',
    'website' => 'https://example.org',
    'currency_code' => 'INR',
    'timezone' => 'Asia/Calcutta',
    'is_active' => true,
], $tokens['admin']);
$state['admin_company_slug'] = $adminCompanyCreate['body']['data']['slug'] ?? null;
$setTemplate('/api/admin/companies/' . ($state['admin_company_slug'] ?? ''), '/api/admin/companies/{slug}');

$branchAdminUser = createBranchUserFixture([
    'company' => $company,
    'branch' => $primaryBranch,
    'department' => $department,
    'name' => 'Codex Branch Admin',
    'email' => uniqueValue('branch-admin') . '@example.com',
    'password' => 'password123',
    'is_branch_admin' => true,
]);

$deptAdminUser = createBranchUserFixture([
    'company' => $company,
    'branch' => $primaryBranch,
    'department' => $department,
    'name' => 'Codex Dept Admin',
    'email' => uniqueValue('dept-admin') . '@example.com',
    'password' => 'password123',
    'is_dept_admin' => true,
]);

$employeeUser = createBranchUserFixture([
    'company' => $company,
    'branch' => $primaryBranch,
    'department' => $department,
    'name' => 'Codex Employee',
    'email' => uniqueValue('employee') . '@example.com',
    'password' => 'password123',
]);

$branchUserCrudTarget = createBranchUserFixture([
    'company' => $company,
    'branch' => $primaryBranch,
    'department' => $department,
    'name' => 'Codex Branch User Target',
    'email' => uniqueValue('branch-user-target') . '@example.com',
    'password' => 'password123',
]);
$state['branch_user_slug'] = $branchUserCrudTarget->slug;
$setTemplate('/api/company/branch-users/' . $state['branch_user_slug'], '/api/company/branch-users/{slug}');
$setTemplate('/api/company/branch-users/' . $state['branch_user_slug'] . '/change-password', '/api/company/branch-users/{slug}/change-password');

$deptEmployeeSelf = createBranchUserFixture([
    'company' => $company,
    'branch' => $primaryBranch,
    'department' => $department,
    'name' => 'Codex Dept Employee',
    'email' => uniqueValue('dept-employee') . '@example.com',
    'password' => 'password123',
]);
$setTemplate('/api/company/branches/' . $state['primary_branch_slug'], '/api/company/branches/{slug}');
$setTemplate('/api/company/features/' . $state['feature_slug'], '/api/company/features/{slug}');
$setTemplate('/api/company/departments/' . $state['department_slug'], '/api/company/departments/{slug}');
$setTemplate('/api/company/department-features/' . $state['department_feature_slug'], '/api/company/department-features/{slug}');
$setTemplate('/api/company/settings/' . ($state['setting_slug'] ?? ''), '/api/company/settings/{slug}');
$setTemplate('/api/company/roles/' . $state['role_slug'], '/api/company/roles/{slug}');

$record('Admin profile', 'GET', '/api/admin/profile', [], $tokens['admin']);
$record('Admin update profile', 'PUT', '/api/admin/profile', [
    'phone' => '9999990000',
], $tokens['admin']);
$adminStore = $record('Create admin', 'POST', '/api/admins', [
    'name' => 'Codex Secondary Admin',
    'email' => uniqueValue('secondary-admin') . '@example.com',
    'phone' => '9012345678',
    'username' => uniqueValue('secondary_admin'),
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'status' => 'active',
], $tokens['admin']);
$state['secondary_admin_slug'] = $adminStore['body']['data']['slug'] ?? null;
$setTemplate('/api/admins/' . ($state['secondary_admin_slug'] ?? ''), '/api/admins/{slug}');
$setTemplate('/api/admins/' . ($state['secondary_admin_slug'] ?? '') . '/restore', '/api/admins/{slug}/restore');
$record('List admins', 'GET', '/api/admins', [], $tokens['admin']);
$record('Show admin', 'GET', '/api/admins/' . $state['secondary_admin_slug'], [], $tokens['admin']);
$record('Update admin (PUT)', 'PUT', '/api/admins/' . $state['secondary_admin_slug'], [
    'name' => 'Codex Secondary Admin Updated',
], $tokens['admin']);
$record('Update admin (POST)', 'POST', '/api/admins/' . $state['secondary_admin_slug'], [
    'name' => 'Codex Secondary Admin Post Updated',
], $tokens['admin']);
$record('Delete admin', 'DELETE', '/api/admins/' . $state['secondary_admin_slug'], [], $tokens['admin']);
$record('Restore admin', 'POST', '/api/admins/' . $state['secondary_admin_slug'] . '/restore', [], $tokens['admin']);

$record('List admin companies', 'GET', '/api/admin/companies', [], $tokens['admin']);
$record('Show admin company', 'GET', '/api/admin/companies/' . $state['admin_company_slug'], [], $tokens['admin']);
$record('Update admin company', 'PUT', '/api/admin/companies/' . $state['admin_company_slug'], [
    'name' => 'Admin Managed Updated',
    'phone' => '9345678901',
], $tokens['admin']);
$record('Delete admin company', 'DELETE', '/api/admin/companies/' . $state['admin_company_slug'], [], $tokens['admin']);

$record('Company profile', 'GET', '/api/company/profile', [], $tokens['company']);
$record('Update company profile', 'PUT', '/api/company/profile', [
    'name' => 'Codex Company Updated',
    'phone' => '9000000011',
], $tokens['company']);
$record('Change company password', 'POST', '/api/company/change-password', [
    'current_password' => 'secret123',
    'new_password' => 'secret456',
    'new_password_confirmation' => 'secret456',
], $tokens['company']);
$record('List companies', 'GET', '/api/companies', [], $tokens['company']);
$record('Show company', 'GET', '/api/companies/' . $state['managed_company_slug'], [], $tokens['company']);
$record('Update company (PUT)', 'PUT', '/api/companies/' . $state['managed_company_slug'], [
    'name' => 'Managed Company Updated',
    'currency_code' => 'USD',
    'timezone' => 'UTC',
], $tokens['company']);
$record('Update company (POST)', 'POST', '/api/companies/' . $state['managed_company_slug'], [
    'name' => 'Managed Company Post Updated',
    'currency_code' => 'INR',
    'timezone' => 'Asia/Calcutta',
], $tokens['company']);
$record('Delete company', 'DELETE', '/api/companies/' . $state['managed_company_slug'], [], $tokens['company']);

$record('List branches', 'GET', '/api/company/branches', [], $tokens['company']);
$record('Show branch', 'GET', '/api/company/branches/' . $state['primary_branch_slug'], [], $tokens['company']);
$record('Update branch', 'PUT', '/api/company/branches/' . $state['primary_branch_slug'], [
    'name' => 'Main Branch Updated',
], $tokens['company']);
$disposableBranch = createDisposableBranch($company, $tag . '-disposable');
$setTemplate('/api/company/branches/' . $disposableBranch->slug, '/api/company/branches/{slug}');
$record('Delete branch', 'DELETE', '/api/company/branches/' . $disposableBranch->slug, [], $tokens['company']);

$record('List features', 'GET', '/api/company/features', [], $tokens['company']);
$record('Show feature', 'GET', '/api/company/features/' . $state['feature_slug'], [], $tokens['company']);
$record('Update feature', 'PUT', '/api/company/features/' . $state['feature_slug'], [
    'name' => 'Primary Feature Updated',
    'category' => 'updated-operations',
], $tokens['company']);
$record('List company departments', 'GET', '/api/company/departments', [], $tokens['company']);
$record('Show company department', 'GET', '/api/company/departments/' . $state['department_slug'], [], $tokens['company']);

$record('List department features', 'GET', '/api/company/department-features', [], $tokens['company']);
$record('Show department feature', 'GET', '/api/company/department-features/' . $state['department_feature_slug'], [], $tokens['company']);
$record('Update department feature', 'PUT', '/api/company/department-features/' . $state['department_feature_slug'], [
    'access_level' => 'approve',
    'is_enabled' => true,
], $tokens['company']);
$record('Delete department feature', 'DELETE', '/api/company/department-features/' . $state['department_feature_slug'], [], $tokens['company']);

$record('List settings', 'GET', '/api/company/settings', [], $tokens['company']);
$record('Show setting', 'GET', '/api/company/settings/' . $state['setting_slug'], [], $tokens['company']);
$record('Update setting', 'PUT', '/api/company/settings/' . $state['setting_slug'], [
    'setting_value' => 'Asia/Kolkata',
    'value_type' => 'string',
    'is_public' => false,
], $tokens['company']);
$record('Delete setting', 'DELETE', '/api/company/settings/' . $state['setting_slug'], [], $tokens['company']);

$record('List roles', 'GET', '/api/company/roles', [], $tokens['company']);
$record('Show role', 'GET', '/api/company/roles/' . $state['role_slug'], [], $tokens['company']);
$record('Update role', 'PUT', '/api/company/roles/' . $state['role_slug'], [
    'name' => 'Supervisor Updated',
], $tokens['company']);
$record('Delete role', 'DELETE', '/api/company/roles/' . $state['role_slug'], [], $tokens['company']);

$record('Create branch user', 'POST', '/api/company/branch-users', [
    'branch_id' => $primaryBranch->id,
    'dept_id' => $department->id,
    'name' => 'Codex Route Branch User',
    'email' => uniqueValue('route-branch-user') . '@example.com',
    'password' => 'password123',
    'phone' => '9111111111',
    'is_active' => true,
], $tokens['company']);
$record('List branch users', 'GET', '/api/company/branch-users', [], $tokens['company']);
$record('Show branch user', 'GET', '/api/company/branch-users/' . $state['branch_user_slug'], [], $tokens['company']);
$record('Update branch user', 'PUT', '/api/company/branch-users/' . $state['branch_user_slug'], [
    'name' => 'Codex Branch User Target Updated',
], $tokens['company']);
$record('Change branch user password', 'POST', '/api/company/branch-users/' . $state['branch_user_slug'] . '/change-password', [
    'current_password' => 'password123',
    'new_password' => 'password456',
    'confirm_password' => 'password456',
], $tokens['company']);
$record('Delete branch user', 'DELETE', '/api/company/branch-users/' . $state['branch_user_slug'], [], $tokens['company']);

$branchAdminLogin = $record('Branch admin login', 'POST', '/api/branch-admin/login', [
    'email' => $branchAdminUser->email,
    'password' => 'password123',
]);
$tokens['branch_admin'] = $branchAdminLogin['body']['token'] ?? null;
$record('Branch admin profile', 'GET', '/api/branch-admin/profile', [], $tokens['branch_admin']);
$record('Branch admin departments list', 'GET', '/api/departments', [], $tokens['branch_admin']);
$branchEmployeeCreate = $record('Create branch employee', 'POST', '/api/branch/employees', [
    'name' => 'Branch Employee Route',
    'email' => uniqueValue('branch-employee') . '@example.com',
    'password' => 'password123',
    'phone' => '9222222222',
    'dept_id' => $department->id,
], $tokens['branch_admin']);
$state['branch_employee_slug'] = $branchEmployeeCreate['body']['data']['slug'] ?? null;
$setTemplate('/api/branch/employees/' . ($state['branch_employee_slug'] ?? ''), '/api/branch/employees/{slug}');
$record('List branch employees', 'GET', '/api/branch/employees', [], $tokens['branch_admin']);
$record('Show branch employee', 'GET', '/api/branch/employees/' . $state['branch_employee_slug'], [], $tokens['branch_admin']);
$record('Update branch employee (PUT)', 'PUT', '/api/branch/employees/' . $state['branch_employee_slug'], [
    'name' => 'Branch Employee Route Updated',
    'dept_id' => $department->id,
], $tokens['branch_admin']);
$record('Update branch employee (POST)', 'POST', '/api/branch/employees/' . $state['branch_employee_slug'], [
    'name' => 'Branch Employee Route Post Updated',
    'dept_id' => $department->id,
], $tokens['branch_admin']);
$record('Delete branch employee', 'DELETE', '/api/branch/employees/' . $state['branch_employee_slug'], [], $tokens['branch_admin']);
$record('Branch admin logout', 'POST', '/api/branch-admin/logout', [], $tokens['branch_admin']);

$deptAdminLogin = $record('Dept admin login', 'POST', '/api/dept-admin/login', [
    'email' => $deptAdminUser->email,
    'password' => 'password123',
]);
$tokens['dept_admin'] = $deptAdminLogin['body']['token'] ?? null;
$record('Dept admin profile', 'GET', '/api/dept-admin/profile', [], $tokens['dept_admin']);
$deptEmployeeCreate = $record('Create dept employee', 'POST', '/api/dept/employees', [
    'name' => 'Dept Employee Route',
    'email' => uniqueValue('dept-employee-route') . '@example.com',
    'password' => 'password123',
    'phone' => '9333333333',
], $tokens['dept_admin']);
$state['dept_employee_slug'] = $deptEmployeeCreate['body']['data']['slug'] ?? null;
$setTemplate('/api/dept/employees/' . ($state['dept_employee_slug'] ?? ''), '/api/dept/employees/{slug}');
$record('List dept employees', 'GET', '/api/dept/employees', [], $tokens['dept_admin']);
$record('Show dept employee', 'GET', '/api/dept/employees/' . $state['dept_employee_slug'], [], $tokens['dept_admin']);
$record('Update dept employee', 'PUT', '/api/dept/employees/' . $state['dept_employee_slug'], [
    'name' => 'Dept Employee Route Updated',
], $tokens['dept_admin']);
$record('Delete dept employee', 'DELETE', '/api/dept/employees/' . $state['dept_employee_slug'], [], $tokens['dept_admin']);
$record('Dept admin logout', 'POST', '/api/dept-admin/logout', [], $tokens['dept_admin']);

$deptEmployeeLogin = $record('Dept employee login', 'POST', '/api/dept-employee/login', [
    'email' => $deptEmployeeSelf->email,
    'password' => 'password123',
]);
$tokens['dept_employee'] = $deptEmployeeLogin['body']['token'] ?? null;
$record('Dept employee profile', 'GET', '/api/dept-employee/profile', [], $tokens['dept_employee']);
$record('Dept employee change password', 'POST', '/api/dept-employee/change-password', [
    'current_password' => 'password123',
    'new_password' => 'newpassword123',
    'new_password_confirmation' => 'newpassword123',
], $tokens['dept_employee']);
$deptEmployeeReLogin = $record('Dept employee re-login after password change', 'POST', '/api/dept-employee/login', [
    'email' => $deptEmployeeSelf->email,
    'password' => 'newpassword123',
]);
$tokens['dept_employee_fresh'] = $deptEmployeeReLogin['body']['token'] ?? null;
$record('Dept employee logout', 'POST', '/api/dept-employee/logout', [], $tokens['dept_employee_fresh']);

$record('Delete feature', 'DELETE', '/api/company/features/' . $state['feature_slug'], [], $tokens['company']);

$employeeLogin = $record('Employee login', 'POST', '/api/employee/login', [
    'email' => $employeeUser->email,
    'password' => 'password123',
]);
$tokens['employee'] = $employeeLogin['body']['token'] ?? null;
$record('Employee profile', 'GET', '/api/employee/profile', [], $tokens['employee']);
$attendanceIndex = $record('Employee attendance index', 'GET', '/api/employee/attendance', [], $tokens['employee']);
$attendanceId = $attendanceIndex['body']['data']['data'][0]['id'] ?? Attendance::where('branch_user_id', $employeeUser->id)->latest('id')->value('id');
$setTemplate('/api/employee/attendance/' . $attendanceId, '/api/employee/attendance/{id}');
$record('Employee attendance show', 'GET', '/api/employee/attendance/' . $attendanceId, [], $tokens['employee']);
$record('Employee logout', 'POST', '/api/employee/logout', [], $tokens['employee']);

$record('Company logout', 'POST', '/api/company/logout', [], $tokens['company']);
$record('Admin logout', 'POST', '/api/admin/logout', [], $tokens['admin']);

$total = count($results);
$passed = count(array_filter($results, static fn(array $result): bool => $result['status'] < 400));
$failed = $total - $passed;

$lines = [];
$lines[] = '# API Route Response Report';
$lines[] = '';
$lines[] = '- Generated at: `' . now()->toDateTimeString() . '`';
$lines[] = '- Workspace: `D:\\wamp_server\\www\\projects\\Business_Management\\Business Management`';
$lines[] = '- Total requests executed: `' . $total . '`';
$lines[] = '- Successful responses (`< 400`): `' . $passed . '`';
$lines[] = '- Non-success responses (`>= 400`): `' . $failed . '`';
$lines[] = '';
$lines[] = '## Summary';
$lines[] = '';
$lines[] = '| # | Route | Status |';
$lines[] = '| --- | --- | --- |';

foreach ($results as $index => $result) {
    $displayUri = $routeTemplates[$result['uri']] ?? $result['uri'];
    $lines[] = '| ' . ($index + 1) . ' | `' . $result['method'] . ' ' . $displayUri . '` | `' . $result['status'] . '` |';
}

$lines[] = '';
$lines[] = '## Detailed Responses';
$lines[] = '';

foreach ($results as $index => $result) {
    $displayUri = $routeTemplates[$result['uri']] ?? $result['uri'];
    $lines[] = '### ' . ($index + 1) . '. ' . $result['label'];
    $lines[] = '';
    $lines[] = '- Route: `' . $result['method'] . ' ' . $displayUri . '`';
    if ($displayUri !== $result['uri']) {
        $lines[] = '- Tested URI: `' . $result['uri'] . '`';
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

file_put_contents($reportPath, implode(PHP_EOL, $lines) . PHP_EOL);

echo 'Report written to: ' . $reportPath . PHP_EOL;
echo 'Requests: ' . $total . ', Passed: ' . $passed . ', Failed: ' . $failed . PHP_EOL;
