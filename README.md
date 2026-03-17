# Business Management REST API

A **production-ready Business Management REST API** built with **Laravel** and **Laravel Sanctum**, following the **Repository + Service Pattern** with full API Resource transformation and FormRequest validation.

Modules: **Admin Auth**, **Admin Management**, **Company Auth & Management**, **Branch Management**, **Feature Management**, **Department Management**, **Department Features**, **System Settings**, **Roles**, **Branch Users**.

---

## 🚀 Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 10 |
| Language | PHP 8.1+ |
| Database | MySQL |
| Authentication | Laravel Sanctum (Bearer Token) |
| Architecture | Repository + Service Pattern |
| Response Format | API Resources (JSON) |
| Validation | Laravel FormRequest |

---

## 👥 Authentication System

| Guard | Login Endpoint | Who Uses It | Accesses |
|---|---|---|---|
| `sanctum` (admin) | `POST /api/admin/login` | Platform admins | `/api/admins/*`, `/api/admin/companies/*`, `/api/companies/*` |
| `company` | `POST /api/company/login` | Company accounts | `/api/company/*` (branches, roles, etc.) |
| `sanctum` (branch admin)| `POST /api/branch-admin/login` | Branch admins | `/api/branch-admin/*` |

> All protected routes require: `Authorization: Bearer {token}`

---

## ⚙️ Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
DB_DATABASE=business_management_api
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan migrate --seed
php artisan serve
```

API base URL: `http://localhost:8000/api`

---

## 📋 Default Admin Credentials

| Field | Value |
|---|---|
| Email | admin@example.com |
| Password | password123 |

---

## 📡 Complete API Reference

### Base URL: `http://localhost:8000/api`

---

## 🔓 PUBLIC ROUTES

---

### 1. Admin Login

**`POST /api/admin/login`**

```json
// Request
{ "login": "admin@example.com", "password": "password123" }
// OR
{ "login": "admin", "password": "password123" }
```

```json
// 200 Success
{
    "success": true,
    "message": "Login successful.",
    "data": {
        "token": "1|abc123token",
        "admin": {
            "slug": "system-admin", "name": "System Admin",
            "email": "admin@example.com", "username": "admin",
            "status": "active"
        }
    }
}
```

```json
// 401 Error
{ "success": false, "message": "Invalid credentials or account is not active." }
```

---

### 2. Company Self-Registration

**`POST /api/register-company`**

```json
// Request
{
    "name": "ABC Pvt Ltd",
    "email": "admin@abc.com",
    "phone": "9876543210",
    "password": "secret123",
    "password_confirmation": "secret123",
    "logo": "https://cdn.example.com/logo.png",
    "address": "123 Main St, Delhi",
    "website": "https://abc.com"
}
```

| Field | Rules |
|---|---|
| `name` | required, string, max:150 |
| `email` | required, email, unique:companies |
| `phone` | required, string, max:20 |
| `password` | required, min:6, confirmed |
| `logo` | nullable, string |
| `address` | nullable, string |
| `website` | nullable, url |

```json
// 201 Success
{
    "success": true,
    "message": "Company registered successfully.",
    "data": {
        "id": 1, "slug": "abc-pvt-ltd", "name": "ABC Pvt Ltd",
        "email": "admin@abc.com", "phone": "9876543210", "is_active": true
    }
}
```

---

### 3. Company Login (via companies table)

**`POST /api/company/login`**

```json
// Request
{ "email": "admin@abc.com", "password": "secret123" }
```

```json
// 200 Success
{
    "success": true, "message": "Login successful.",
    "data": {
        "company": "ABC Pvt Ltd", "email": "admin@abc.com",
        "token": "2|xyz789token",
        "profile": { "id": 1, "slug": "abc-pvt-ltd", "name": "ABC Pvt Ltd", "is_active": true }
    }
}
```

```json
// 401 Error
{ "success": false, "message": "Invalid credentials or account is not active." }
```

---

---

### 4. Branch Admin Login

**`POST /api/branch-admin/login`**

```json
// Request
{ "email": "branch_admin@abc.com", "password": "secret123" }
```

```json
// 200 Success
{
  "status": true,
  "message": "Login successful",
  "token": "3|ghi789token",
  "user": { ... }
}
```

```json
// 404/401 Error
{ "status": false, "message": "Invalid password." }
```

---

## 🛡️ PROTECTED ROUTES — ADMIN

> Require header: `Authorization: Bearer {admin_token}`

---

### 5. Admin Logout

**`POST /api/admin/logout`**

```json
// 200 Success
{ "success": true, "message": "Logged out successfully." }
```

---

### 6. Admin Profile

**`GET /api/admin/profile`**

```json
// 200 Success
{
    "success": true, "message": "Profile retrieved successfully.",
    "data": {
        "slug": "system-admin", "name": "System Admin",
        "email": "admin@example.com", "phone": null, "username": "admin",
        "status": "active", "last_login_at": "2026-03-11 09:00:00",
        "last_login_ip": "127.0.0.1"
    }
}
```

---

### 7. Update Admin Profile

**`PUT /api/admin/profile`**

```json
// Request (all optional)
{
    "name": "Super Admin", "phone": "9999999999",
    "current_password": "password123",
    "password": "newpass456", "password_confirmation": "newpass456"
}
```

```json
// 200 Success
{ "success": true, "message": "Profile updated successfully.", "data": { ... } }
```

---

### 8. List Admins

**`GET /api/admins`**

| Query Param | Type | Description |
|---|---|---|
| `search` | string | Search name, email, username |
| `status` | string | `active`, `inactive`, `blocked` |
| `per_page` | int | Default: 10 |

```
GET /api/admins
GET /api/admins?search=john&status=active&per_page=5
```

```json
// 200 Paginated Response
{
    "success": true, "message": "Admins retrieved successfully.",
    "data": [{ "slug": "john-doe", "name": "John Doe", "email": "john@example.com", "status": "active" }],
    "meta": { "current_page": 1, "per_page": 10, "total": 20, "last_page": 2 },
    "links": { "first": "...", "last": "...", "prev": null, "next": "..." }
}
```

---

### 9. Create Admin

**`POST /api/admins`** — Slug auto-generated from name.

```json
// Request
{
    "name": "John Doe", "email": "john@example.com",
    "phone": "9876543210", "username": "john",
    "password": "password123", "password_confirmation": "password123",
    "status": "active"
}
```

| Field | Rules |
|---|---|
| `name` | required, string, max:150 |
| `email` | required, email, unique |
| `username` | nullable, string, unique, alpha_dash |
| `password` | required, min:8, confirmed |
| `status` | required, in:active,inactive,blocked |

```json
// 201 Success
{ "success": true, "message": "Admin created successfully.", "data": { "slug": "john-doe", ... } }
```

---

### 10. Get Admin by Slug

**`GET /api/admins/{slug}`**

```
GET /api/admins/john-doe
```

```json
// 200 Success
{ "success": true, "message": "Admin retrieved successfully.", "data": { "slug": "john-doe", ... } }
```

```json
// 404 Error
{ "success": false, "message": "Admin not found." }
```

---

### 11. Update Admin

**`PUT /api/admins/{slug}`** — All fields optional.

> For file/form-data: use **`POST /api/admins/{slug}`** instead.

```json
// Request
{ "name": "John Updated", "status": "inactive" }
```

```json
// 200 Success
{ "success": true, "message": "Admin updated successfully.", "data": { ... } }
```

> Slug is **auto-regenerated** if name changes.

---

### 12. Delete Admin (Soft Delete)

**`DELETE /api/admins/{slug}`**

> ⚠️ Cannot delete your own account (returns 403).

```json
// 200 Success
{ "success": true, "message": "Admin deleted successfully." }
```

```json
// 403 Self-Delete Error
{ "success": false, "message": "You cannot delete your own account." }
```

---

### 13. Restore Soft-Deleted Admin

**`POST /api/admins/{slug}/restore`**

```json
// 200 Success
{ "success": true, "message": "Admin restored successfully.", "data": { ... } }
```

---

### 14–18. Admin Company CRUD

> Admin can create, view, update, and soft-delete companies.

#### `GET /api/admin/companies`

| Query Param | Type | Description |
|---|---|---|
| `search` | string | Search name, email, phone |
| `is_active` | boolean | `1` or `0` |
| `per_page` | int | Default: 10 |

```json
// 200 Paginated Response
{
    "success": true, "message": "Companies retrieved successfully.",
    "data": [{ "id": 1, "slug": "abc-pvt-ltd", "name": "ABC Pvt Ltd", "is_active": true }],
    "meta": { "current_page": 1, "total": 5 }
}
```

#### `POST /api/admin/companies` — Slug auto-generated. Password hashed.

```json
// Request
{
    "name": "XYZ Corp", "email": "admin@xyz.com", "phone": "9000000000",
    "password": "secret123", "password_confirmation": "secret123",
    "legal_name": "XYZ Corp Pvt Ltd", "website": "https://xyz.com",
    "currency_code": "INR", "timezone": "Asia/Kolkata", "is_active": true
}
```

| Field | Rules |
|---|---|
| `name` | required, string, max:150 |
| `email` | required, email, unique:companies |
| `phone` | required, string, max:20 |
| `password` | required, min:6, confirmed |
| `legal_name` | nullable, string, max:200 |
| `website` | nullable, url |
| `currency_code` | nullable, string, max:10 |
| `timezone` | nullable, string |
| `is_active` | boolean |

```json
// 201 Success
{ "success": true, "message": "Company created successfully.", "data": { "slug": "xyz-corp", ... } }
```

#### `GET /api/admin/companies/{slug}`

```json
// 200 Success
{ "success": true, "message": "Company retrieved successfully.", "data": { ... } }
```

#### `PUT /api/admin/companies/{slug}` — All fields optional.

> If `name` changes, slug is regenerated. If `password` is sent, it is re-hashed.

```json
// Request
{ "name": "XYZ Global Ltd", "is_active": false }
```

```json
// 200 Success
{ "success": true, "message": "Company updated successfully.", "data": { "slug": "xyz-global-ltd", ... } }
```

#### `DELETE /api/admin/companies/{slug}` — Soft delete (sets `is_delete=true`, `is_active=false`).

```json
// 200 Success
{ "success": true, "message": "Company deactivated and deleted successfully." }
```

---

### 19–24. Companies (Shared Admin+Company Access)

> Both Admin and Company tokens can access these routes.

#### `GET /api/companies`

| Query Param | Type | Description |
|---|---|---|
| `search` | string | Search name, email, phone |
| `is_active` | string | `active`, `inactive`, `blocked` |
| `per_page` | int | Default: 10 |

```json
// 200 Success
{ "success": true, "data": [...], "meta": { "total": 10 } }
```

#### `POST /api/companies`

> Accepts `multipart/form-data` for logo file upload.

```json
{ "name": "...", "email": "...", "phone": "...", ... }
```

Logo validation (file upload):

| Field | Rules |
|---|---|
| `logo` | nullable, image, mimes: jpeg,png,jpg,gif,webp, **max: 5120 KB (5 MB)** |

#### `GET /api/companies/{slug}`

#### `PUT /api/companies/{slug}` — JSON update (no file upload).

#### `POST /api/companies/{slug}` — Form-data update (for logo file upload, max **5 MB**).

> Use `POST` with `_method=PUT` in body, or just `POST`, when uploading a logo file.

#### `DELETE /api/companies/{slug}`

---

## 🏢 PROTECTED ROUTES — COMPANY AUTH

> Require header: `Authorization: Bearer {company_token}` (from `POST /api/company/login`)

---

### 25. Company Logout

**`POST /api/company/logout`**

```json
// 200 Success
{ "success": true, "message": "Logged out successfully." }
```

---

### 26. Get Company Profile

**`GET /api/company/profile`**

```json
// 200 Success
{
    "success": true, "message": "Profile retrieved successfully.",
    "data": {
        "id": 1, "slug": "abc-pvt-ltd", "name": "ABC Pvt Ltd",
        "email": "admin@abc.com", "phone": "9876543210",
        "website": "https://abc.com", "logo": null,
        "address": "123 Main St, Delhi",
        "currency_code": "INR", "timezone": "Asia/Kolkata",
        "is_active": true, "is_delete": false,
        "created_at": "2026-03-11 09:00:00"
    }
}
```

---

### 27. Update Company Profile

**`PUT /api/company/profile`**

```json
// Request (all optional)
{
    "name": "ABC Enterprises", "phone": "9000000001",
    "email": "new@abc.com", "address": "New Address",  
    "logo": "https://cdn.example.com/logo.png",
    "website": "https://abc-new.com"
}
```

| Field | Rules |
|---|---|
| `name` | sometimes, required, string, max:150 |
| `phone` | sometimes, required, string, max:20 |
| `email` | sometimes, required, email, unique (ignore self) |
| `address` | nullable, string |
| `logo` | nullable, string, max:255 |
| `website` | nullable, url |

```json
// 200 Success
{ "success": true, "message": "Profile updated successfully.", "data": { "slug": "abc-enterprises", ... } }
```

> Slug is regenerated if `name` changes.

---

### 28. Change Company Password

**`POST /api/company/change-password`**

```json
// Request
{
    "current_password": "secret123",
    "new_password": "newpass456",
    "new_password_confirmation": "newpass456"
}
```

| Field | Rules |
|---|---|
| `current_password` | required, string (verified via `Hash::check`) |
| `new_password` | required, min:6, confirmed |
| `new_password_confirmation` | required |

```json
// 200 Success
{ "success": true, "message": "Password updated successfully." }
```

```json
// 422 Wrong Password
{ "success": false, "message": "The current password is incorrect." }
```

---

## 🏢 PROTECTED ROUTES — BRANCH ADMIN

> Require header: `Authorization: Bearer {branch_admin_token}` (from `POST /api/branch-admin/login`)

---

### 28b. Branch Admin Logout

**`POST /api/branch-admin/logout`**

```json
// 200 Success
{
  "status": true,
  "message": "Logout successful"
}
```

---

### 28c. Managing Branch Employees by Branch Admin

---

#### 1. List Branch Employees

**`GET /api/branch/employees`** 

List employees belonging to the same company and branch (excluding branch admins and deleted users).

```json
// 200 Success
{
  "status": true,
  "message": "Employees retrieved successfully",
  "data": [
    {
      "id": 1,
      "company_id": 1,
      "branch_id": 1,
      "dept_id": 2,
      "emp_id": "COM-00000001",
      "name": "Employee One",
      "email": "employee@abc.com",
      "phone": "9876543210",
      "profile_image": "profile_images/somehash.jpg",
      "slug": "employee-one",
      "is_dept_admin": false,
      "is_branch_admin": false,
      "is_active": true,
      "is_delete": false
    }
  ]
}
```

---

#### 2. Create Branch Employee

**`POST /api/branch/employees`**

`emp_id`, `company_id`, and `branch_id` are auto-configured from the authenticated branch admin. `profile_image` up to 5MB is supported.

```json
// Request (form-data or json if no image)
{
    "name": "Employee One",
    "email": "employee@abc.com",
    "password": "secretpassword",
    "phone": "9876543210",
    "dept_id": 2,
    "profile_image": (file - optional)
}
```

| Field | Rules |
|---|---|
| `name` | required, string, max:191 |
| `email` | required, email, unique:branch_users |
| `password` | required, min:6 |
| `phone` | nullable, string, max:20 |
| `dept_id` | required, exists:departments,id |
| `profile_image` | nullable, image, mimes:jpg,jpeg,png,webp, max:5120 |

```json
// 201 Success
{
  "status": true,
  "message": "Employee created successfully",
  "data": {
    "emp_id": "COM-00000001",
    "slug": "employee-one",
    // ... employee object
  }
}
```

---

#### 3. Get Specific Employee

**`GET /api/branch/employees/{id}`**

```json
// 200 Success
{
  "status": true,
  "message": "Employee retrieved successfully",
  "data": { ... }
}
```

---

#### 4. Update Employee

**`PUT /api/branch/employees/{id}`**
*(Use `POST` with `_method=PUT` if uploading `profile_image`)*

```json
// Request (all optional)
{
    "name": "Employee One Updated",
    "phone": "9000000001",
    "dept_id": 3,
    "is_active": false,
    "profile_image": (file - optional)
}
```

```json
// 200 Success
{
  "status": true,
  "message": "Employee updated successfully",
  "data": { ... }
}
```

---

#### 5. Soft Delete Employee

**`DELETE /api/branch/employees/{id}`**

Sets `is_delete = 1` and `is_active = 0`.

```json
// 200 Success
{
  "status": true,
  "message": "Employee deleted successfully",
  "data": {}
}
```

---

## 🏢 PROTECTED ROUTES — BRANCHES

> Scoped to authenticated company. Company users cannot access branches of another company.

---

### 29. List Branches

**`GET /api/company/branches`**

| Query Param | Type | Description |
|---|---|---|
| `search` | string | Search name, code, email, city |
| `is_active` | boolean | `1` or `0` |
| `per_page` | int | Default: 10 |

```json
// 200 Paginated Response
{
    "success": true, "message": "Branches retrieved successfully.",
    "data": [{
        "id": 1, "company_id": 1, "code": "BHB-001",
        "name": "Bhubaneswar Head Office", "slug": "bhubaneswar-head-office",
        "email": "bhubaneswar@abc.com", "phone": "9876543210",
        "city": "Bhubaneswar", "state": "Odisha", "country": "India",
        "is_head_office": true, "is_active": true
    }],
    "meta": { "current_page": 1, "per_page": 10, "total": 3, "last_page": 1 }
}
```

---

### 30. Create Branch

**`POST /api/company/branches`** — `company_id` auto-set from token. Slug auto-generated.

```json
// Request
{
    "code": "BHB-001", "name": "Bhubaneswar Head Office",
    "email": "bhubaneswar@abc.com", "phone": "9876543210",
    "address_line1": "Plot 12, Saheed Nagar", "city": "Bhubaneswar",
    "state": "Odisha", "country": "India", "postal_code": "751007",
    "google_map_link": "https://maps.google.com/?q=20.29,85.82",
    "is_head_office": true, "is_active": true
}
```

| Field | Rules |
|---|---|
| `code` | required, string, max:50, unique per company |
| `name` | required, string, max:150 |
| `email` | nullable, email |
| `phone` | nullable, string |
| `address_line1` | nullable, string |
| `city` | nullable, string |
| `state` | nullable, string |
| `country` | nullable, string |
| `postal_code` | nullable, string |
| `google_map_link` | nullable, url |
| `is_head_office` | boolean |
| `is_active` | boolean |

```json
// 201 Success
{ "success": true, "message": "Branch created successfully.", "data": { "slug": "bhubaneswar-head-office", ... } }
```

---

### 31. Get Branch

**`GET /api/company/branches/{slug}`**

```json
// 200 Success
{ "success": true, "message": "Branch retrieved successfully.", "data": { ... } }
```

```json
// 404 Error
{ "success": false, "message": "Branch not found." }
```

---

### 32. Update Branch

**`PUT /api/company/branches/{slug}`** — All fields optional. Slug regenerated if name changes.

```json
// Request
{ "name": "Bhubaneswar Main Office", "phone": "9123456789", "is_active": false }
```

```json
// 200 Success
{ "success": true, "message": "Branch updated successfully.", "data": { "slug": "bhubaneswar-main-office", ... } }
```

---

### 33. Delete Branch

**`DELETE /api/company/branches/{slug}`**

```json
// 200 Success
{ "success": true, "message": "Branch deleted successfully." }
```

---

## 🧩 PROTECTED ROUTES — FEATURES

> Global (not company-scoped). System features (`is_system=true`) cannot be deleted.

---

### 34. List Features

**`GET /api/company/features`**

| Query Param | Type | Description |
|---|---|---|
| `search` | string | Search name, code, category |
| `is_active` | boolean | `1` or `0` |

```json
// 200 Success
{
    "success": true, "message": "Features retrieved successfully.",
    "data": [{
        "id": 1, "code": "LIVE_LOCATION", "name": "Live Location Tracking",
        "category": "Tracking", "slug": "live-location-tracking",
        "is_system": false, "is_active": true, "sort_order": 1
    }]
}
```

---

### 35. Create Feature

**`POST /api/company/features`** — Slug auto-generated.

```json
// Request
{
    "code": "LIVE_LOCATION", "name": "Live Location Tracking",
    "category": "Tracking", "description": "Track user location in real time.",
    "icon": "map-pin", "sort_order": 1, "is_system": false, "is_active": true
}
```

| Field | Rules |
|---|---|
| `code` | required, string, max:80, unique:features |
| `name` | required, string, max:150 |
| `category` | required, string, max:80 |
| `description` | nullable, string |
| `icon` | nullable, string |
| `sort_order` | nullable, integer |
| `is_system` | boolean |
| `is_active` | boolean |

```json
// 201 Success
{ "success": true, "message": "Feature created successfully.", "data": { "slug": "live-location-tracking", ... } }
```

---

### 36. Get Feature

**`GET /api/company/features/{slug}`**

```json
{ "success": true, "message": "Feature retrieved successfully.", "data": { ... } }
```

---

### 37. Update Feature

**`PUT /api/company/features/{slug}`** — All fields optional.

```json
// Request
{ "name": "Real-Time Location Tracking", "sort_order": 2, "is_active": false }
```

---

### 38. Delete Feature

**`DELETE /api/company/features/{slug}`**

> ⚠️ System features (`is_system=true`) cannot be deleted — returns 403.

```json
// 200 Success
{ "success": true, "message": "Feature deleted successfully." }
```

```json
// 403 Error
{ "success": false, "message": "System features cannot be deleted." }
```

---

## 🏗️ PROTECTED ROUTES — DEPARTMENTS

> Company-scoped. `company_id` and `created_by` auto-set from token. System-default departments cannot be deleted.

---

### 39. List Departments

**`GET /api/company/departments`**

| Query Param | Type | Description |
|---|---|---|
| `search` | string | Search name, code |
| `is_active` | boolean | `1` or `0` |

```json
// 200 Success
{
    "success": true, "data": [{
        "id": 1, "company_id": 1, "slug": "human-resource-department",
        "code": "HR-001", "name": "Human Resource Department",
        "level_no": 1, "approval_mode": "hierarchical",
        "is_system_default": false, "is_active": true
    }]
}
```

---

### 40. Create Department

**`POST /api/company/departments`** — Slug auto-generated.

```json
// Request
{
    "code": "HR-001", "name": "Human Resource Department",
    "branch_id": 1, "level_no": 1,
    "approval_mode": "hierarchical",
    "escalation_mode": "full_chain",
    "can_create_tasks": true, "can_receive_tasks": true, "is_active": true
}
```

| Field | Rules |
|---|---|
| `code` | required, string, max:50, unique per company |
| `name` | required, string, max:150 |
| `branch_id` | nullable, exists:branches,id |
| `parent_department_id` | nullable, exists:departments,id |
| `reports_to_department_id` | nullable, exists:departments,id |
| `level_no` | nullable, integer, min:1 |
| `approval_mode` | in: single, multi, hierarchical |
| `escalation_mode` | in: none, manager_to_ceo, full_chain, custom |
| `can_create_tasks` | boolean |
| `can_receive_tasks` | boolean |
| `is_active` | boolean |

---

### 41. Get Department

**`GET /api/company/departments/{slug}`**

---

### 42. Update Department

**`PUT /api/company/departments/{slug}`** — All fields optional. Slug regenerated if name changes.

---

### 43. Delete Department

**`DELETE /api/company/departments/{slug}`**

> ⚠️ System-default departments (`is_system_default=true`) cannot be deleted — returns 403.

```json
// 403 Error
{ "success": false, "message": "System default departments cannot be deleted." }
```

---

## 🔗 PROTECTED ROUTES — DEPARTMENT FEATURES

> Assign features to departments. Scoped via department → company. A feature can only be assigned once per department.

---

### 44. List Department Features

**`GET /api/company/department-features`**

| Query Param | Type | Description |
|---|---|---|
| `search` | string | Search department or feature name |

```json
// 200 Success
{
    "success": true, "data": [{
        "id": 1, "slug": "hr-department-employee-management",
        "department": { "id": 1, "name": "Human Resource Department", "code": "HR-001" },
        "feature": { "id": 3, "name": "Employee Management", "code": "EMP_MGMT", "category": "HR" },
        "access_level": "full", "is_enabled": true, "assigned_by": 1,
        "assigned_at": "2026-03-11 10:00:00"
    }]
}
```

---

### 45. Assign Feature to Department

**`POST /api/company/department-features`** — Slug auto-generated as `{dept-slug}-{feature-slug}`.

```json
// Request
{ "department_id": 1, "feature_id": 3, "access_level": "full", "is_enabled": true }
```

| Field | Rules |
|---|---|
| `department_id` | required, exists:departments,id |
| `feature_id` | required, exists:features,id |
| `access_level` | in: view, create, edit, delete, approve, full |
| `is_enabled` | boolean |

```json
// 201 Success
{ "success": true, "message": "Feature assigned to department successfully.", "data": { ... } }
```

```json
// 403 Company Mismatch
{ "success": false, "message": "The selected department does not belong to your company." }
```

```json
// 409 Duplicate
{ "success": false, "message": "This feature is already assigned to the selected department." }
```

---

### 46. Get Department Feature

**`GET /api/company/department-features/{slug}`**

---

### 47. Update Department Feature

**`PUT /api/company/department-features/{slug}`** — `department_id` and `feature_id` cannot be changed.

```json
// Request
{ "access_level": "edit", "is_enabled": false }
```

---

### 48. Remove Department Feature

**`DELETE /api/company/department-features/{slug}`**

```json
// 200 Success
{ "success": true, "message": "Feature removed from department successfully." }
```

---

## ⚙️ PROTECTED ROUTES — SYSTEM SETTINGS

> Company-scoped. `setting_group` and `setting_key` are immutable after creation. Slug = `{group}-{key}`.

---

### 49. List Settings

**`GET /api/company/settings`**

| Query Param | Type | Description |
|---|---|---|
| `search` | string | Search setting_key or setting_group |
| `group` | string | Filter by exact group name |

```json
// 200 Success
{
    "data": [{
        "id": 1, "slug": "company-timezone",
        "setting_group": "company", "setting_key": "timezone",
        "setting_value": "Asia/Kolkata", "casted_value": "Asia/Kolkata",
        "value_type": "string", "is_public": false
    }]
}
```

---

### 50. Create Setting

**`POST /api/company/settings`** — Slug = `{group}-{key}`.

```json
// Request
{
    "setting_group": "company", "setting_key": "timezone",
    "setting_value": "Asia/Kolkata", "value_type": "string",
    "branch_id": null, "is_public": false
}
```

| Field | Rules |
|---|---|
| `setting_group` | required, string, max:80 |
| `setting_key` | required, string, max:100 |
| `setting_value` | nullable |
| `value_type` | in: string, integer, float, boolean, json, text |
| `branch_id` | nullable, exists:branches,id |
| `is_public` | boolean |

```json
// 409 Conflict
{ "success": false, "message": "This setting key already exists for the given group and scope." }
```

---

### 51. Get Setting

**`GET /api/company/settings/{slug}`**

---

### 52. Update Setting

**`PUT /api/company/settings/{slug}`** — `setting_group` and `setting_key` cannot be changed.

```json
// Request
{ "setting_value": "42", "value_type": "integer", "is_public": true }
```

> `casted_value` returns the value cast to its proper PHP type (int, float, bool, array, string).

---

### 53. Delete Setting

**`DELETE /api/company/settings/{slug}`**

---

## 👤 PROTECTED ROUTES — ROLES

> Company-scoped. `company_id` auto-set from token. Slug auto-generated from name.

---

### 54. List Roles

**`GET /api/company/roles`**

| Query Param | Type | Description |
|---|---|---|
| `search` | string | Search name, description |
| `is_active` | boolean | `1` or `0` |
| `per_page` | int | Default: 10 |

```json
// 200 Paginated Response
{
    "success": true, "message": "Roles retrieved successfully.",
    "data": [{ "id": 1, "name": "Branch Manager", "slug": "branch-manager", "is_active": true }],
    "meta": { "current_page": 1, "total": 5 }
}
```

---

### 55. Create Role

**`POST /api/company/roles`**

```json
// Request
{ "name": "Branch Manager", "description": "Manages branch operations.", "is_active": true }
```

| Field | Rules |
|---|---|
| `name` | required, string, max:255 |
| `description` | nullable, string, max:1000 |
| `is_active` | sometimes, boolean |

```json
// 201 Success
{ "success": true, "message": "Role created successfully.", "data": { "slug": "branch-manager", ... } }
```

---

### 56. Get Role

**`GET /api/company/roles/{slug}`**

```json
{ "success": true, "message": "Role retrieved successfully.", "data": { ... } }
```

---

### 57. Update Role

**`PUT /api/company/roles/{slug}`** — All fields optional. Slug regenerated if name changes.

```json
// Request
{ "name": "Senior Branch Manager", "is_active": false }
```

---

### 58. Delete Role

**`DELETE /api/company/roles/{slug}`**

```json
// 200 Success
{ "success": true, "message": "Role deleted successfully." }
```

---

## 👥 PROTECTED ROUTES — BRANCH USERS

> Company-scoped. `company_id` and `created_by` auto-set from token. Soft-deleted via `is_delete=true`. Dynamic `emp_id` generated during creation.

---

### 59. List Branch Users

**`GET /api/company/branch-users`**

| Query Param | Type | Description |
|---|---|---|
| `search` | string | Search name, email, phone |
| `is_active` | boolean | `1` or `0` |
| `per_page` | int | Default: 10 |

```json
// 200 Paginated Response
{
    "data": [{
        "id": 1, "company_id": 1,
        "emp_id": "ABC-00000001",
        "branch": { "id": 1, "name": "Bhubaneswar Head Office", "slug": "bhubaneswar-head-office" },
        "department": { "id": 1, "name": "IT Department", "slug": "it-department" },
        "name": "John Doe", "email": "john@abc.com", "phone": "9876543210",
        "slug": "john-doe", "is_dept_admin": true, "is_branch_admin": false,
        "is_active": true, "is_delete": false
    }]
}
```

---

### 60. Create Branch User

**`POST /api/company/branch-users`** — Slug & `emp_id` auto-generated. Password hashed.

> Returns `403` if `branch_id` does not belong to authenticated company.

```json
// Request
{
    "branch_id": 1, "dept_id": 1,
    "name": "John Doe", "email": "john@abc.com",
    "password": "secret123",
    "phone": "9876543210", "is_active": true,
    "is_dept_admin": true, "is_branch_admin": false
}
```

| Field | Rules |
|---|---|
| `branch_id` | required, integer, exists:branches,id |
| `dept_id` | nullable, integer, exists:departments,id |
| `name` | required, string, max:191 |
| `email` | required, email, unique:branch_users |
| `password` | required, min:6 |
| `phone` | nullable, string, max:20 |
| `is_dept_admin` | sometimes, boolean |
| `is_branch_admin` | sometimes, boolean |
| `is_active` | sometimes, boolean |

```json
// 201 Success
{ "success": true, "message": "Branch user created successfully.", "data": { "emp_id": "ABC-00000001", "slug": "john-doe", ... } }
```

```json
// 403 Branch Mismatch
{ "success": false, "message": "The selected branch does not belong to your company." }
```

---

### 61. Get Branch User

**`GET /api/company/branch-users/{slug}`**

```json
{ "success": true, "message": "Branch user retrieved successfully.", "data": { ... } }
```

---

### 62. Update Branch User

**`PUT /api/company/branch-users/{slug}`** — All fields optional. Slug regenerated if name changes.

```json
// Request
{ "name": "John Smith", "dept_id": 2, "is_branch_admin": true, "phone": "9000000001", "is_active": false }
```

> If `branch_id` changes, new branch must still belong to the company.

---

### 63. Delete Branch User (Soft Delete)

**`DELETE /api/company/branch-users/{slug}`** — Sets `is_delete=true`. Record retained for audit.

```json
// 200 Success
{ "success": true, "message": "Branch user deleted successfully." }
```

---

### 64. Change Branch User Password

**`POST /api/company/branch-users/{slug}/change-password`**

> `current_password` is **optional** — omit for admin reset. If provided, it is verified.

```json
// Request
{
    "current_password": "old_password",
    "new_password": "new_secure_pass",
    "confirm_password": "new_secure_pass"
}
```

| Field | Rules |
|---|---|
| `current_password` | nullable, string (verified if provided) |
| `new_password` | required, string, min:6 |
| `confirm_password` | required, must match `new_password` |

```json
// 200 Success
{ "success": true, "message": "Password updated successfully." }
```

```json
// 422 Wrong Password
{ "success": false, "message": "The current password is incorrect." }
```

---

## 👷 PROTECTED ROUTES — BRANCH EMPLOYEES

> Branch-admin-scoped. Requires `auth:sanctum` + `branch_admin` middleware. `company_id` and `branch_id` are auto-set from the authenticated branch admin token. Slug auto-generated from name.

---

### 65. List Branch Employees

**`GET /api/branch/employees`** — Returns all non-admin employees in the same branch.

```json
// 200 Success
{
    "status": true,
    "message": "Employees retrieved successfully",
    "data": [{
        "id": 5, "company_id": 1, "branch_id": 2,
        "emp_id": "ABC-00000005", "dept_id": 1,
        "name": "Jane Smith", "email": "jane@abc.com",
        "phone": "9876543210", "slug": "jane-smith",
        "is_branch_admin": 0, "is_dept_admin": 0,
        "is_active": 1, "is_delete": 0
    }]
}
```

---

### 66. Create Branch Employee

**`POST /api/branch/employees`** — Slug & `emp_id` auto-generated. `company_id` and `branch_id` inherited from authenticated branch admin.

```json
// Request (multipart/form-data)
{
    "name": "Jane Smith",
    "email": "jane@abc.com",
    "password": "secret123",
    "phone": "9876543210",
    "dept_id": 1,
    "profile_image": "<file>"
}
```

| Field | Rules |
|---|---|
| `name` | required, string, max:191 |
| `email` | required, email, unique:branch_users |
| `password` | required, min:6 |
| `phone` | nullable, string, max:20 |
| `dept_id` | required, exists:departments,id |
| `profile_image` | nullable, image, mimes:jpg,jpeg,png,webp, max:5 MB |

```json
// 201 Success
{
    "status": true,
    "message": "Employee created successfully",
    "data": { "emp_id": "ABC-00000005", "slug": "jane-smith", ... }
}
```

```json
// 422 Validation Error
{ "status": false, "message": "Validation error", "data": { "email": ["The email has already been taken."] } }
```

---

### 67. Get Branch Employee

**`GET /api/branch/employees/{slug}`**

```json
// 200 Success
{ "status": true, "message": "Employee retrieved successfully", "data": { ... } }
```

```json
// 404 Error
{ "status": false, "message": "Employee not found", "data": {} }
```

---

### 68. Update Branch Employee

**`PUT /api/branch/employees/{slug}`** — All fields optional. Slug regenerated if name changes.

```json
// Request (multipart/form-data)
{
    "name": "Jane Williams",
    "phone": "9000000001",
    "dept_id": 2,
    "is_active": 0,
    "profile_image": "<file>"
}
```

| Field | Rules |
|---|---|
| `name` | sometimes, string, max:191 |
| `phone` | nullable, string, max:20 |
| `dept_id` | sometimes, exists:departments,id |
| `is_active` | sometimes, boolean |
| `profile_image` | nullable, image, mimes:jpg,jpeg,png,webp, max:5 MB |

```json
// 200 Success
{ "status": true, "message": "Employee updated successfully", "data": { "slug": "jane-williams", ... } }
```

---

### 69. Delete Branch Employee (Soft Delete)

**`DELETE /api/branch/employees/{slug}`** — Sets `is_delete=1` and `is_active=0`. Record retained for audit.

```json
// 200 Success
{ "status": true, "message": "Employee deleted successfully", "data": {} }
```

---

## 📋 Route Summary Table

| Method | URI | Guard | Controller |
|---|---|---|---|
| POST | `/api/admin/login` | Public | AdminAuthController@login |
| POST | `/api/register-company` | Public | CompanyAuthController@register |
| POST | `/api/company/login` | Public | CompanyAuthController@login |
| POST | `/api/branch-admin/login` | Public | BranchAdminAuthController@login |
| POST | `/api/admin/logout` | admin | AdminAuthController@logout |
| POST | `/api/branch-admin/logout` | branch_admin | BranchAdminAuthController@logout |
| GET | `/api/branch/employees` | branch_admin | BranchEmployeeController@index |
| POST | `/api/branch/employees` | branch_admin | BranchEmployeeController@store |
| GET | `/api/branch/employees/{slug}` | branch_admin | BranchEmployeeController@show |
| PUT | `/api/branch/employees/{slug}` | branch_admin | BranchEmployeeController@update |
| DELETE | `/api/branch/employees/{slug}` | branch_admin | BranchEmployeeController@destroy |
| GET | `/api/admin/profile` | admin | AdminAuthController@profile |
| PUT | `/api/admin/profile` | admin | AdminAuthController@updateProfile |
| GET | `/api/admins` | admin | AdminController@index |
| POST | `/api/admins` | admin | AdminController@store |
| GET | `/api/admins/{slug}` | admin | AdminController@show |
| PUT | `/api/admins/{slug}` | admin | AdminController@update |
| DELETE | `/api/admins/{slug}` | admin | AdminController@destroy |
| POST | `/api/admins/{slug}/restore` | admin | AdminController@restore |
| GET | `/api/admin/companies` | admin | AdminCompanyController@index |
| POST | `/api/admin/companies` | admin | AdminCompanyController@store |
| GET | `/api/admin/companies/{slug}` | admin | AdminCompanyController@show |
| PUT | `/api/admin/companies/{slug}` | admin | AdminCompanyController@update |
| DELETE | `/api/admin/companies/{slug}` | admin | AdminCompanyController@destroy |
| GET | `/api/companies` | admin+company | CompanyController@index |
| POST | `/api/companies` | admin+company | CompanyController@store |
| GET | `/api/companies/{slug}` | admin+company | CompanyController@show |
| PUT | `/api/companies/{slug}` | admin+company | CompanyController@update |
| DELETE | `/api/companies/{slug}` | admin+company | CompanyController@destroy |
| POST | `/api/company/logout` | company | CompanyAuthController@logout |
| GET | `/api/company/profile` | company | CompanyAuthController@profile |
| PUT | `/api/company/profile` | company | CompanyAuthController@updateProfile |
| POST | `/api/company/change-password` | company | CompanyAuthController@changePassword |
| POST | `/api/logout` | company | AuthController@logout |
| GET | `/api/company/branches` | company | BranchController@index |
| POST | `/api/company/branches` | company | BranchController@store |
| GET | `/api/company/branches/{slug}` | company | BranchController@show |
| PUT | `/api/company/branches/{slug}` | company | BranchController@update |
| DELETE | `/api/company/branches/{slug}` | company | BranchController@destroy |
| GET | `/api/company/features` | company | FeatureController@index |
| POST | `/api/company/features` | company | FeatureController@store |
| GET | `/api/company/features/{slug}` | company | FeatureController@show |
| PUT | `/api/company/features/{slug}` | company | FeatureController@update |
| DELETE | `/api/company/features/{slug}` | company | FeatureController@destroy |
| GET | `/api/company/departments` | company | DepartmentController@index |
| POST | `/api/company/departments` | company | DepartmentController@store |
| GET | `/api/company/departments/{slug}` | company | DepartmentController@show |
| PUT | `/api/company/departments/{slug}` | company | DepartmentController@update |
| DELETE | `/api/company/departments/{slug}` | company | DepartmentController@destroy |
| GET | `/api/company/department-features` | company | DepartmentFeatureController@index |
| POST | `/api/company/department-features` | company | DepartmentFeatureController@store |
| GET | `/api/company/department-features/{slug}` | company | DepartmentFeatureController@show |
| PUT | `/api/company/department-features/{slug}` | company | DepartmentFeatureController@update |
| DELETE | `/api/company/department-features/{slug}` | company | DepartmentFeatureController@destroy |
| GET | `/api/company/settings` | company | SystemSettingController@index |
| POST | `/api/company/settings` | company | SystemSettingController@store |
| GET | `/api/company/settings/{slug}` | company | SystemSettingController@show |
| PUT | `/api/company/settings/{slug}` | company | SystemSettingController@update |
| DELETE | `/api/company/settings/{slug}` | company | SystemSettingController@destroy |
| GET | `/api/company/roles` | company | RoleController@index |
| POST | `/api/company/roles` | company | RoleController@store |
| GET | `/api/company/roles/{slug}` | company | RoleController@show |
| PUT | `/api/company/roles/{slug}` | company | RoleController@update |
| DELETE | `/api/company/roles/{slug}` | company | RoleController@destroy |
| GET | `/api/company/branch-users` | company | BranchUserController@index |
| POST | `/api/company/branch-users` | company | BranchUserController@store |
| GET | `/api/company/branch-users/{slug}` | company | BranchUserController@show |
| PUT | `/api/company/branch-users/{slug}` | company | BranchUserController@update |
| DELETE | `/api/company/branch-users/{slug}` | company | BranchUserController@destroy |
| POST | `/api/company/branch-users/{slug}/change-password` | company | BranchUserController@changePassword |

---

## 🔢 Slug Generation

Slugs are auto-generated from the resource name using `Str::slug()` with uniqueness guaranteed (`-2`, `-3` suffix on conflict).

| Resource | Example | Generated Slug |
|---|---|---|
| Admin | John Doe | `john-doe` |
| Company | ABC Pvt Ltd | `abc-pvt-ltd` |
| Branch | Bhubaneswar Head Office | `bhubaneswar-head-office` |
| Feature | Live Location Tracking | `live-location-tracking` |
| Department | Human Resource Department | `human-resource-department` |
| Dept. Feature | HR Dept + Employee Mgmt | `hr-department-employee-management` |
| Setting | group=company, key=timezone | `company-timezone` |
| Role | Branch Manager | `branch-manager` |
| Branch User | John Doe | `john-doe` |
| Branch Employee | Jane Smith | `jane-smith` |

---

## 🛡️ Security Features

- ✅ **Password hashing** via `Hash::make()` (bcrypt)
- ✅ **Sanctum token authentication** — stateless Bearer tokens
- ✅ **Rate limiting** on admin login — 5 attempts/minute
- ✅ **Self-delete protection** — admins cannot delete own account
- ✅ **Company scoping** — branch users/branches always validated to company
- ✅ **Soft deletes** — data retained via `is_delete` flag
- ✅ **System protection** — system features and default departments cannot be deleted

---

## 📁 Project Structure

```
app/
├── Http/Controllers/API/
│   ├── AdminAuthController.php       # Admin login/logout/profile
│   ├── AdminController.php           # Admin CRUD + restore
│   ├── AdminCompanyController.php    # Admin company CRUD
│   ├── BranchAdminAuthController.php # Branch Admin login/logout
│   ├── BranchEmployeeController.php  # Branch Employee CRUD
│   ├── BranchController.php          # Branch CRUD
│   ├── BranchUserController.php      # Branch user CRUD + change-password
│   ├── CompanyAuthController.php     # Company register/login/profile
│   ├── CompanyController.php         # Company CRUD (shared)
│   ├── DepartmentController.php      # Department CRUD
│   ├── DepartmentFeatureController.php
│   ├── FeatureController.php
│   ├── RoleController.php
│   └── SystemSettingController.php
├── Http/Requests/
│   ├── Admin/        CompanyAuth/        Branch/
│   ├── BranchUser/   Company/            Role/
├── Http/Resources/
│   ├── AdminResource.php, BranchResource.php, BranchUserResource.php
│   ├── CompanyResource.php, DepartmentResource.php, DepartmentFeatureResource.php
│   ├── FeatureResource.php, RoleResource.php, SystemSettingResource.php
├── Models/
│   ├── Admin.php, Branch.php, BranchUser.php, Company.php
│   ├── Department.php, DepartmentFeature.php
│   ├── Feature.php, Role.php, User.php
├── Repositories/   # One per model
├── Services/       # One per model
└── Traits/
    └── ApiResponseTrait.php

database/migrations/
├── *_create_admins_table.php
├── *_create_companies_table.php
├── *_create_branches_table.php
├── *_create_roles_table.php
├── *_create_branch_users_table.php
└── *_add_auth_fields_to_companies_table.php

routes/api.php
config/auth.php    # Guards: sanctum (admin), company (Company model)
```

---

## 📄 License

MIT License.
