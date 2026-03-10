# Business Management REST API

A **production-ready Business Management REST API** built with **Laravel** and **Laravel Sanctum**, following the **Repository + Service Pattern** with full API Resource transformation and FormRequest validation.

Modules covered: **Admin Management**, **Company Management**, **Branch Management**, **Feature Management**, **Department Management**, **Department Features**, **System Settings**.

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

## 👥 Two-Tier Authentication System

The API maintains two completely isolated classes of users, each with their own dedicated authentication guards via Laravel Sanctum:

1. **Admins:** Authenticate via `/api/admin/login` (default `web`/`sanctum` guard) to access `/api/admins/*` and `/api/companies/*` routes.
2. **Company Users:** Authenticate via `/api/login` (custom `company` guard) to access `/api/companies/*` and `/api/company/branches/*` routes.

Both **Admins** and **Company Users** are authorized to manage companies. Company Users can only manage branches that **belong to their own company**.

---

## 📋 Prerequisites

- PHP 8.1+
- Composer
- MySQL 5.7+ / MariaDB 10.3+
- PHP extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`

---

## ⚙️ Installation

### 1. Install PHP Dependencies

```bash
composer install
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=business_management_api
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Create Database

```sql
CREATE DATABASE business_management_api CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Run Migrations and Seed Default Admin

```bash
php artisan migrate --seed
```

### 5. Storage Link (optional but good practice)

```bash
php artisan storage:link
```

### 6. Start Development Server

```bash
php artisan serve
```

API will be available at: **`http://localhost:8000/api`**

---

## 🔐 Default Admin Login

| Field    | Value                |
|----------|----------------------|
| Email    | admin@example.com    |
| Password | password123          |
| Slug     | system-admin         |
| Status   | active               |

---

## 🔑 Authentication

This API uses **Laravel Sanctum Bearer Token** authentication.

After login, include the token in every protected request:

```
Authorization: Bearer {your_token_here}
```

> **Note:** The system uses two Sanctum guards. Passing either an **Admin Token** or a **Company Token** to company management endpoints will successfully authenticate you. Admin endpoints strictly require an Admin token.

**Example:**

```bash
curl -X GET http://localhost:8000/api/admin/profile \
     -H "Authorization: Bearer 1|abc123tokenhere" \
     -H "Accept: application/json"
```

---

## 📡 API Endpoints

### Base URL

```
http://localhost:8000/api
```

---

### 🏢 Public Routes (Company Users)

#### `POST /api/register`

Register a new company user.

**Request Body:**

```json
{
    "email": "company@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Success Response (201):**

```json
{
    "success": true,
    "message": "Registration successful"
}
```

---

#### `POST /api/login`

Authenticate a company user.

**Request Body:**

```json
{
    "email": "company@example.com",
    "password": "password123"
}
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "Login successful",
    "token": "1|abc123sanctumtokenhere"
}
```

---

### 🛡️ Public Routes (Admin)

#### `POST /api/admin/login`

Authenticate an admin using **email or username** with password.

**Rate Limited:** 5 attempts per minute.

**Request Body:**

```json
{
    "login": "admin@example.com",
    "password": "password123"
}
```

> You can also use `"login": "admin"` (username) instead of email.

**Success Response (200):**

```json
{
    "success": true,
    "message": "Login successful.",
    "data": {
        "token": "1|abc123sanctumtokenhere",
        "admin": {
            "slug": "system-admin",
            "name": "System Admin",
            "email": "admin@example.com",
            "phone": null,
            "username": "admin",
            "status": "active",
            "last_login_at": "2024-01-01 10:00:00",
            "last_login_ip": "127.0.0.1",
            "created_at": "2024-01-01 00:00:00",
            "updated_at": "2024-01-01 10:00:00"
        }
    }
}
```

**Error Response (401):**

```json
{
    "success": false,
    "message": "Invalid credentials or account is not active."
}
```

**Validation Error (422):**

```json
{
    "success": false,
    "message": "Validation error.",
    "errors": {
        "login": ["Email or username is required."],
        "password": ["Password is required."]
    }
}
```

---

### Protected Routes

> All routes below require the header: `Authorization: Bearer {token}`

---

### 🛡️ Protected Routes (Admin)
*(Require Admin Sanctum Token)*

#### `POST /api/admin/logout`

Revoke the current Sanctum token.

**Success Response (200):**

```json
{
    "success": true,
    "message": "Logged out successfully."
}
```

---

#### `GET /api/admin/profile`

Get the authenticated admin's profile.

**Success Response (200):**

```json
{
    "success": true,
    "message": "Profile retrieved successfully.",
    "data": {
        "slug": "system-admin",
        "name": "System Admin",
        "email": "admin@example.com",
        "phone": null,
        "username": "admin",
        "status": "active",
        "last_login_at": "2024-01-01 10:00:00",
        "last_login_ip": "127.0.0.1",
        "created_at": "2024-01-01 00:00:00",
        "updated_at": "2024-01-01 00:00:00"
    }
}
```

---

#### `PUT /api/admin/profile`

Update the authenticated admin's own profile.

**Request Body (all fields optional):**

```json
{
    "name": "Updated Admin Name",
    "phone": "9876543210",
    "current_password": "password123",
    "password": "newpassword456",
    "password_confirmation": "newpassword456"
}
```

> `current_password` is required only when changing the password.

**Success Response (200):**

```json
{
    "success": true,
    "message": "Profile updated successfully.",
    "data": { ... }
}
```

---

### 🏢 Protected Routes (Company Management)
*(Require Company Sanctum Token OR Admin Sanctum Token)*

#### `GET /api/companies`

List all companies with **pagination**, **search**, and **status filter**.

**Query Parameters:**

| Parameter  | Type   | Description                          |
|------------|--------|--------------------------------------|
| `search`   | string | Search by name, email, username, phone |
| `status`   | string | Filter: `active`, `inactive`, `blocked` |
| `per_page` | int    | Items per page (default: 10)         |

**Examples:**

```
GET /api/admins
GET /api/admins?search=john
GET /api/admins?status=active
GET /api/admins?search=john&status=active&per_page=5
```

**Paginated Response (200):**

```json
{
    "success": true,
    "message": "Admins retrieved successfully.",
    "data": [
        {
            "slug": "system-admin",
            "name": "System Admin",
            "email": "admin@example.com",
            "phone": null,
            "username": "admin",
            "status": "active",
            "last_login_at": "2024-01-01 10:00:00",
            "last_login_ip": "127.0.0.1",
            "created_at": "2024-01-01 00:00:00",
            "updated_at": "2024-01-01 10:00:00"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 10,
        "total": 11,
        "last_page": 2,
        "from": 1,
        "to": 10
    },
    "links": {
        "first": "http://localhost:8000/api/admins?page=1",
        "last": "http://localhost:8000/api/admins?page=2",
        "prev": null,
        "next": "http://localhost:8000/api/admins?page=2"
    }
}
```

---

#### `GET /api/admins/{slug}`

Get a single admin by slug.

**Example:** `GET /api/admins/system-admin`

**Success Response (200):**

```json
{
    "success": true,
    "message": "Admin retrieved successfully.",
    "data": {
        "slug": "system-admin",
        "name": "System Admin",
        "email": "admin@example.com",
        ...
    }
}
```

**Error Response (404):**

```json
{
    "success": false,
    "message": "Admin not found."
}
```

---

#### `POST /api/admins`

Create a new admin. **Slug is auto-generated from name**.

**Request Body:**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "9999999999",
    "username": "john",
    "password": "password123",
    "password_confirmation": "password123",
    "status": "active"
}
```

**Validation Rules:**

| Field    | Rules |
|----------|-------|
| `name`   | required, string, max:150 |
| `email`  | required, email, unique |
| `phone`  | nullable, string, max:30 |
| `username` | nullable, string, max:100, unique, alpha_dash |
| `password` | required, min:8, confirmed |
| `status` | required, in:active,inactive,blocked |

**Success Response (201):**

```json
{
    "success": true,
    "message": "Admin created successfully.",
    "data": {
        "slug": "john-doe",
        "name": "John Doe",
        "email": "john@example.com",
        ...
    }
}
```

---

#### `POST /api/admins/{slug}` ← **Use this in Postman for form-data**
### `PUT  /api/admins/{slug}` ← JSON-only updates

Update an existing admin by slug.

> **Important:** HTTP `PUT` cannot carry `multipart/form-data` in Postman/most clients.  
> If you are using form-data in Postman, use **`POST /api/admins/{slug}`**. If using raw JSON, you can use `PUT`.

**Request Body (all fields optional):**

```json
{
    "name": "John Updated",
    "phone": "8888888888",
    "status": "inactive"
}
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "Admin updated successfully.",
    "data": { ... }
}
```

> If name is changed, slug is **automatically regenerated** with uniqueness guaranteed.

---

#### `DELETE /api/admins/{slug}`

Soft-delete an admin by slug.

> ⚠️ You **cannot delete your own account**. Returns `403 Forbidden`.

**Success Response (200):**

```json
{
    "success": true,
    "message": "Admin deleted successfully."
}
```

**Self-Delete Error (403):**

```json
{
    "success": false,
    "message": "You cannot delete your own account."
}
```

---

#### `POST /api/admins/{slug}/restore`

Restore a soft-deleted admin.

**Example:** `POST /api/admins/john-doe/restore`

**Success Response (200):**

```json
{
    "success": true,
    "message": "Admin restored successfully.",
    "data": { ... }
}
```

**Error Response (404):**

```json
{
    "success": false,
    "message": "Admin not found or is not deleted."
}
```

---

### 🏢 Protected Routes (Company User — Branch Management)
*(Require Company User Sanctum Token only)*

> All Branch routes are scoped to the authenticated company user's company. A company user **cannot access branches of another company**.

---

#### `GET /api/company/branches`

List all branches belonging to the authenticated company user's company, with **pagination**, **search**, and **status filter**.

**Query Parameters:**

| Parameter  | Type    | Description                          |
|------------|---------|--------------------------------------|
| `search`   | string  | Search by name, code, email, city    |
| `is_active`| boolean | Filter: `1` (active), `0` (inactive) |
| `per_page` | int     | Items per page (default: 10)         |

**Examples:**

```
GET /api/company/branches
GET /api/company/branches?search=bhubaneswar
GET /api/company/branches?is_active=1
GET /api/company/branches?search=head&is_active=1&per_page=5
```

**Paginated Response (200):**

```json
{
    "success": true,
    "message": "Branches retrieved successfully.",
    "data": [
        {
            "id": 1,
            "company_id": 3,
            "code": "BHB-001",
            "name": "Bhubaneswar Head Office",
            "slug": "bhubaneswar-head-office",
            "email": "bhubaneswar@example.com",
            "phone": "9876543210",
            "manager_user_id": null,
            "address_line1": "Plot 12, Saheed Nagar",
            "address_line2": null,
            "city": "Bhubaneswar",
            "state": "Odisha",
            "country": "India",
            "postal_code": "751007",
            "google_map_link": "https://maps.google.com/?q=20.2961,85.8245",
            "is_head_office": true,
            "is_active": true,
            "created_at": "2026-03-10 09:28:00",
            "updated_at": "2026-03-10 09:28:00"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 10,
        "total": 3,
        "last_page": 1,
        "from": 1,
        "to": 3
    },
    "links": {
        "first": "http://localhost:8000/api/company/branches?page=1",
        "last": "http://localhost:8000/api/company/branches?page=1",
        "prev": null,
        "next": null
    }
}
```

---

#### `POST /api/company/branches`

Create a new branch under the authenticated company.

> **Slug is auto-generated from name.** `company_id` is automatically set from the authenticated token.

**Request Body:**

```json
{
    "code": "BHB-001",
    "name": "Bhubaneswar Head Office",
    "email": "bhubaneswar@example.com",
    "phone": "9876543210",
    "manager_user_id": null,
    "address_line1": "Plot 12, Saheed Nagar",
    "address_line2": null,
    "city": "Bhubaneswar",
    "state": "Odisha",
    "country": "India",
    "postal_code": "751007",
    "google_map_link": "https://maps.google.com/?q=20.2961,85.8245",
    "is_head_office": true,
    "is_active": true
}
```

**Validation Rules:**

| Field             | Rules                                   |
|-------------------|-----------------------------------------|
| `code`            | required, string, max:50, unique per company |
| `name`            | required, string, max:150               |
| `email`           | nullable, email, max:150                |
| `phone`           | nullable, string, max:30                |
| `manager_user_id` | nullable, integer                       |
| `address_line1`   | nullable, string, max:255               |
| `address_line2`   | nullable, string, max:255               |
| `city`            | nullable, string, max:120               |
| `state`           | nullable, string, max:120               |
| `country`         | nullable, string, max:120               |
| `postal_code`     | nullable, string, max:30                |
| `google_map_link` | nullable, url                           |
| `is_head_office`  | boolean                                 |
| `is_active`       | boolean                                 |

**Success Response (201):**

```json
{
    "success": true,
    "message": "Branch created successfully.",
    "data": {
        "id": 1,
        "company_id": 3,
        "code": "BHB-001",
        "name": "Bhubaneswar Head Office",
        "slug": "bhubaneswar-head-office",
        "email": "bhubaneswar@example.com",
        "phone": "9876543210",
        "manager_user_id": null,
        "address_line1": "Plot 12, Saheed Nagar",
        "address_line2": null,
        "city": "Bhubaneswar",
        "state": "Odisha",
        "country": "India",
        "postal_code": "751007",
        "google_map_link": "https://maps.google.com/?q=20.2961,85.8245",
        "is_head_office": true,
        "is_active": true,
        "created_at": "2026-03-10 09:28:00",
        "updated_at": "2026-03-10 09:28:00"
    }
}
```

**Validation Error (422):**

```json
{
    "success": false,
    "message": "Validation error.",
    "errors": {
        "code": ["This branch code already exists in your company."],
        "name": ["Branch name is required."]
    }
}
```

---

#### `GET /api/company/branches/{slug}`

Get a single branch by slug (scoped to the authenticated company).

**Example:** `GET /api/company/branches/bhubaneswar-head-office`

**Success Response (200):**

```json
{
    "success": true,
    "message": "Branch retrieved successfully.",
    "data": {
        "id": 1,
        "company_id": 3,
        "code": "BHB-001",
        "name": "Bhubaneswar Head Office",
        "slug": "bhubaneswar-head-office",
        ...
    }
}
```

**Not Found (404):**

```json
{
    "success": false,
    "message": "Branch not found."
}
```

---

#### `PUT /api/company/branches/{slug}`

Update an existing branch by slug.

> All fields are **optional** — only send what you want to change.
> If `name` is changed, the **slug is automatically regenerated** with uniqueness guaranteed.

**Example:** `PUT /api/company/branches/bhubaneswar-head-office`

**Request Body (partial update):**

```json
{
    "name": "Bhubaneswar Main Office",
    "phone": "9123456789",
    "is_active": false
}
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "Branch updated successfully.",
    "data": {
        "id": 1,
        "company_id": 3,
        "code": "BHB-001",
        "name": "Bhubaneswar Main Office",
        "slug": "bhubaneswar-main-office",
        "phone": "9123456789",
        "is_active": false,
        ...
    }
}
```

---

#### `DELETE /api/company/branches/{slug}`

Delete a branch by slug (scoped to the authenticated company).

**Example:** `DELETE /api/company/branches/bhubaneswar-head-office`

**Success Response (200):**

```json
{
    "success": true,
    "message": "Branch deleted successfully."
}
```

**Not Found (404):**

```json
{
    "success": false,
    "message": "Branch not found."
}
```

---

### 🧩 Protected Routes (Company User — Feature Management)
*(Require Company User Sanctum Token only)*

> Features are **global** (not company-scoped). Any authenticated company user can manage features.  
> **System features (`is_system = true`) cannot be deleted.**

---

#### `GET /api/company/features`

List all features ordered by `sort_order`, with optional search and filter.

**Query Parameters:**

| Parameter   | Type    | Description                            |
|-------------|---------|----------------------------------------|
| `search`    | string  | Search by name, code, category         |
| `is_active` | boolean | Filter: `1` (active), `0` (inactive)   |

**Examples:**

```
GET /api/company/features
GET /api/company/features?search=location
GET /api/company/features?is_active=1
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "Features retrieved successfully.",
    "data": [
        {
            "id": 1,
            "code": "LIVE_LOCATION",
            "name": "Live Location Tracking",
            "category": "Tracking",
            "description": "Track user location in real time.",
            "slug": "live-location-tracking",
            "icon": "map-pin",
            "sort_order": 1,
            "is_system": false,
            "is_active": true,
            "created_at": "2026-03-10 10:11:00",
            "updated_at": "2026-03-10 10:11:00"
        }
    ]
}
```

---

#### `POST /api/company/features`

Create a new feature. **Slug is auto-generated from name.**

**Request Body:**

```json
{
    "code": "LIVE_LOCATION",
    "name": "Live Location Tracking",
    "category": "Tracking",
    "description": "Track user location in real time.",
    "icon": "map-pin",
    "sort_order": 1,
    "is_system": false,
    "is_active": true
}
```

**Validation Rules:**

| Field         | Rules                                      |
|---------------|--------------------------------------------|
| `code`        | required, string, max:80, unique:features  |
| `name`        | required, string, max:150                  |
| `category`    | required, string, max:80                   |
| `description` | nullable, string                           |
| `icon`        | nullable, string, max:80                   |
| `sort_order`  | nullable, integer                          |
| `is_system`   | boolean                                    |
| `is_active`   | boolean                                    |

**Success Response (201):**

```json
{
    "success": true,
    "message": "Feature created successfully.",
    "data": {
        "id": 1,
        "code": "LIVE_LOCATION",
        "name": "Live Location Tracking",
        "category": "Tracking",
        "description": "Track user location in real time.",
        "slug": "live-location-tracking",
        "icon": "map-pin",
        "sort_order": 1,
        "is_system": false,
        "is_active": true,
        "created_at": "2026-03-10 10:11:00",
        "updated_at": "2026-03-10 10:11:00"
    }
}
```

**Validation Error (422):**

```json
{
    "success": false,
    "message": "Validation error.",
    "errors": {
        "code": ["This feature code already exists."],
        "name": ["Feature name is required."]
    }
}
```

---

#### `GET /api/company/features/{slug}`

Get a single feature by slug.

**Example:** `GET /api/company/features/live-location-tracking`

**Success Response (200):**

```json
{
    "success": true,
    "message": "Feature retrieved successfully.",
    "data": {
        "id": 1,
        "code": "LIVE_LOCATION",
        "name": "Live Location Tracking",
        "slug": "live-location-tracking",
        ...
    }
}
```

**Not Found (404):**

```json
{
    "success": false,
    "message": "Feature not found."
}
```

---

#### `PUT /api/company/features/{slug}`

Update an existing feature by slug. All fields are optional.

> If `name` is changed, the **slug is automatically regenerated**.

**Example:** `PUT /api/company/features/live-location-tracking`

**Request Body (partial update):**

```json
{
    "name": "Real-Time Location Tracking",
    "sort_order": 2,
    "is_active": false
}
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "Feature updated successfully.",
    "data": {
        "id": 1,
        "code": "LIVE_LOCATION",
        "name": "Real-Time Location Tracking",
        "slug": "real-time-location-tracking",
        "sort_order": 2,
        "is_active": false,
        ...
    }
}
```

---

#### `DELETE /api/company/features/{slug}`

Delete a feature by slug.

> ⚠️ **System features (`is_system = true`) cannot be deleted.** Returns `403 Forbidden`.

**Example:** `DELETE /api/company/features/live-location-tracking`

**Success Response (200):**

```json
{
    "success": true,
    "message": "Feature deleted successfully."
}
```

**System Feature Error (403):**

```json
{
    "success": false,
    "message": "System features cannot be deleted."
}
```

**Not Found (404):**

```json
{
    "success": false,
    "message": "Feature not found."
}
```

---

### 🏢 Protected Routes (Company User — Department Management)
*(Require Company User Sanctum Token only)*

> All Department routes are **company-scoped**. A company user can only access departments that belong to their own company.
> `company_id` and `created_by` are **automatically set** from the authenticated token.
> **System-default departments (`is_system_default = true`) cannot be deleted.**

---

#### `GET /api/company/departments`

List all departments for the authenticated company, ordered by `level_no` then `name`.

**Query Parameters:**

| Parameter   | Type    | Description                          |
|-------------|---------|--------------------------------------|
| `search`    | string  | Search by name, code                 |
| `is_active` | boolean | Filter: `1` (active), `0` (inactive) |

**Examples:**

```
GET /api/company/departments
GET /api/company/departments?search=human+resource
GET /api/company/departments?is_active=1
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "Departments retrieved successfully.",
    "data": [
        {
            "id": 1,
            "company_id": 3,
            "branch_id": 2,
            "slug": "human-resource-department",
            "parent_department_id": null,
            "code": "HR-001",
            "name": "Human Resource Department",
            "description": "Manages all HR activities.",
            "head_user_id": 5,
            "level_no": 1,
            "reports_to_department_id": null,
            "approval_mode": "hierarchical",
            "escalation_mode": "full_chain",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_by": 1,
            "created_at": "2026-03-10 10:43:00",
            "updated_at": "2026-03-10 10:43:00"
        }
    ]
}
```

---

#### `POST /api/company/departments`

Create a new department. **Slug is auto-generated from name.** `company_id` and `created_by` are set automatically.

**Request Body:**

```json
{
    "code": "HR-001",
    "name": "Human Resource Department",
    "branch_id": 2,
    "parent_department_id": null,
    "reports_to_department_id": null,
    "description": "Manages all HR activities.",
    "head_user_id": 5,
    "level_no": 1,
    "approval_mode": "hierarchical",
    "escalation_mode": "full_chain",
    "can_create_tasks": true,
    "can_receive_tasks": true,
    "is_active": true
}
```

**Validation Rules:**

| Field                     | Rules                                             |
|---------------------------|---------------------------------------------------|
| `code`                    | required, string, max:50, unique per company      |
| `name`                    | required, string, max:150                         |
| `branch_id`               | nullable, exists:branches,id                      |
| `parent_department_id`    | nullable, exists:departments,id                   |
| `reports_to_department_id`| nullable, exists:departments,id                   |
| `description`             | nullable, string                                  |
| `head_user_id`            | nullable, integer                                 |
| `level_no`                | nullable, integer, min:1                          |
| `approval_mode`           | in: single, multi, hierarchical                   |
| `escalation_mode`         | in: none, manager_to_ceo, full_chain, custom      |
| `can_create_tasks`        | boolean                                           |
| `can_receive_tasks`       | boolean                                           |
| `is_active`               | boolean                                           |

**Success Response (201):**

```json
{
    "success": true,
    "message": "Department created successfully.",
    "data": {
        "id": 1,
        "company_id": 3,
        "branch_id": 2,
        "slug": "human-resource-department",
        "code": "HR-001",
        "name": "Human Resource Department",
        ...
    }
}
```

**Validation Error (422):**

```json
{
    "success": false,
    "message": "Validation error.",
    "errors": {
        "code": ["This department code already exists in your company."],
        "approval_mode": ["Approval mode must be: single, multi, or hierarchical."]
    }
}
```

---

#### `GET /api/company/departments/{slug}`

Get a single department by slug (scoped to the authenticated company).

**Example:** `GET /api/company/departments/human-resource-department`

**Success Response (200):**

```json
{
    "success": true,
    "message": "Department retrieved successfully.",
    "data": {
        "id": 1,
        "code": "HR-001",
        "name": "Human Resource Department",
        "slug": "human-resource-department",
        ...
    }
}
```

**Not Found (404):**

```json
{
    "success": false,
    "message": "Department not found."
}
```

---

#### `PUT /api/company/departments/{slug}`

Update an existing department by slug. All fields are optional.

> If `name` changes, the **slug is automatically regenerated**.

**Example:** `PUT /api/company/departments/human-resource-department`

**Request Body (partial update):**

```json
{
    "name": "HR & Administration",
    "approval_mode": "multi",
    "is_active": false
}
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "Department updated successfully.",
    "data": {
        "id": 1,
        "name": "HR & Administration",
        "slug": "hr-administration",
        "approval_mode": "multi",
        "is_active": false,
        ...
    }
}
```

---

#### `DELETE /api/company/departments/{slug}`

Delete a department by slug.

> ⚠️ **System-default departments (`is_system_default = true`) cannot be deleted.** Returns `403 Forbidden`.

**Example:** `DELETE /api/company/departments/human-resource-department`

**Success Response (200):**

```json
{
    "success": true,
    "message": "Department deleted successfully."
}
```

**System Default Error (403):**

```json
{
    "success": false,
    "message": "System default departments cannot be deleted."
}
```

---

### 🔗 Protected Routes (Company User — Department Features)
*(Require Company User Sanctum Token only)*

> Department Feature routes are **company-scoped via the department**. The department must belong to the authenticated company.
> `assigned_by` is **automatically set** from the auth token.
> A feature can only be assigned to a department **once** (unique per department + feature).

---

#### `GET /api/company/department-features`

List all department-feature mappings for the authenticated company.

**Query Parameters:**

| Parameter | Type   | Description                               |
|-----------|--------|-------------------------------------------|
| `search`  | string | Search by department name or feature name |

**Success Response (200):**

```json
{
    "success": true,
    "message": "Department features retrieved successfully.",
    "data": [
        {
            "id": 1,
            "slug": "hr-department-employee-management",
            "department_id": 1,
            "department": {
                "id": 1,
                "name": "Human Resource Department",
                "slug": "hr-department",
                "code": "HR-001"
            },
            "feature_id": 3,
            "feature": {
                "id": 3,
                "name": "Employee Management",
                "slug": "employee-management",
                "code": "EMP_MGMT",
                "category": "HR"
            },
            "access_level": "full",
            "is_enabled": true,
            "assigned_by": 1,
            "assigned_at": "2026-03-10 11:59:00",
            "created_at": "2026-03-10 11:59:00",
            "updated_at": "2026-03-10 11:59:00"
        }
    ]
}
```

---

#### `POST /api/company/department-features`

Assign a feature to a department. **Slug is auto-generated** as `{department-slug}-{feature-slug}`.

> Returns `403` if the department doesn’t belong to the authenticated company.
> Returns `409 Conflict` if the feature is already assigned to that department.

**Request Body:**

```json
{
    "department_id": 1,
    "feature_id": 3,
    "access_level": "full",
    "is_enabled": true
}
```

**Validation Rules:**

| Field           | Rules                                                      |
|-----------------|------------------------------------------------------------|
| `department_id` | required, exists:departments,id                            |
| `feature_id`    | required, exists:features,id                               |
| `access_level`  | in: view, create, edit, delete, approve, full              |
| `is_enabled`    | boolean                                                    |

**Success Response (201):**

```json
{
    "success": true,
    "message": "Feature assigned to department successfully.",
    "data": {
        "id": 1,
        "slug": "hr-department-employee-management",
        "department_id": 1,
        "department": { ... },
        "feature_id": 3,
        "feature": { ... },
        "access_level": "full",
        "is_enabled": true,
        "assigned_by": 1,
        "assigned_at": "2026-03-10 11:59:00"
    }
}
```

**Conflict Error (409):**

```json
{
    "success": false,
    "message": "This feature is already assigned to the selected department."
}
```

**Company Mismatch Error (403):**

```json
{
    "success": false,
    "message": "The selected department does not belong to your company."
}
```

---

#### `GET /api/company/department-features/{slug}`

Get a single department-feature mapping by slug.

**Example:** `GET /api/company/department-features/hr-department-employee-management`

**Success Response (200):**

```json
{
    "success": true,
    "message": "Department feature retrieved successfully.",
    "data": { ... }
}
```

---

#### `PUT /api/company/department-features/{slug}`

Update the access level or enable/disable a department-feature mapping.

> `department_id` and `feature_id` **cannot be changed** after assignment.

**Request Body:**

```json
{
    "access_level": "edit",
    "is_enabled": false
}
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "Department feature updated successfully.",
    "data": {
        "slug": "hr-department-employee-management",
        "access_level": "edit",
        "is_enabled": false,
        ...
    }
}
```

---

#### `DELETE /api/company/department-features/{slug}`

Remove a feature from a department.

**Success Response (200):**

```json
{
    "success": true,
    "message": "Feature removed from department successfully."
}
```

---

### ⚙️ Protected Routes (Company User — System Settings)
*(Require Company User Sanctum Token only)*

> Settings are **company-scoped**. A company user can only access settings belonging to their own company.
> `company_id` is **automatically set** from the auth token.
> `setting_group` and `setting_key` are **immutable** after creation.
> Returns `409 Conflict` if the same (company, branch, group, key) combination already exists.

---

#### `GET /api/company/settings`

List all settings for the authenticated company, ordered by `setting_group` then `setting_key`.

**Query Parameters:**

| Parameter | Type   | Description                             |
|-----------|--------|-----------------------------------------|
| `search`  | string | Search by setting_key or setting_group  |
| `group`   | string | Filter by exact setting_group name      |

**Examples:**

```
GET /api/company/settings
GET /api/company/settings?group=company
GET /api/company/settings?search=timezone
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "Settings retrieved successfully.",
    "data": [
        {
            "id": 1,
            "company_id": 3,
            "branch_id": null,
            "slug": "company-timezone",
            "setting_group": "company",
            "setting_key": "timezone",
            "setting_value": "Asia/Kolkata",
            "casted_value": "Asia/Kolkata",
            "value_type": "string",
            "is_public": false,
            "created_at": "2026-03-10 15:50:00",
            "updated_at": "2026-03-10 15:50:00"
        }
    ]
}
```

---

#### `POST /api/company/settings`

Create a new system setting. **Slug is auto-generated** as `{setting_group}-{setting_key}`.

**Request Body:**

```json
{
    "setting_group": "company",
    "setting_key": "timezone",
    "setting_value": "Asia/Kolkata",
    "value_type": "string",
    "branch_id": null,
    "is_public": false
}
```

**Validation Rules:**

| Field           | Rules                                                    |
|-----------------|----------------------------------------------------------|
| `setting_group` | required, string, max:80                                 |
| `setting_key`   | required, string, max:100                                |
| `setting_value` | nullable                                                 |
| `value_type`    | in: string, integer, float, boolean, json, text          |
| `branch_id`     | nullable, exists:branches,id                             |
| `is_public`     | boolean                                                  |

**Success Response (201):**

```json
{
    "success": true,
    "message": "Setting created successfully.",
    "data": {
        "id": 1,
        "company_id": 3,
        "slug": "company-timezone",
        "setting_group": "company",
        "setting_key": "timezone",
        "setting_value": "Asia/Kolkata",
        "casted_value": "Asia/Kolkata",
        "value_type": "string",
        "is_public": false
    }
}
```

**Conflict Error (409):**

```json
{
    "success": false,
    "message": "This setting key already exists for the given group and scope."
}
```

---

#### `GET /api/company/settings/{slug}`

Get a single setting by slug.

**Example:** `GET /api/company/settings/company-timezone`

**Success Response (200):**

```json
{
    "success": true,
    "message": "Setting retrieved successfully.",
    "data": {
        "slug": "company-timezone",
        "setting_group": "company",
        "setting_key": "timezone",
        "setting_value": "Asia/Kolkata",
        "casted_value": "Asia/Kolkata",
        ...
    }
}
```

---

#### `PUT /api/company/settings/{slug}`

Update a setting's value, type, or visibility.

> `setting_group` and `setting_key` **cannot be changed** after creation.

**Request Body:**

```json
{
    "setting_value": "42",
    "value_type": "integer",
    "is_public": true
}
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "Setting updated successfully.",
    "data": {
        "slug": "company-max-users",
        "setting_value": "42",
        "casted_value": 42,
        "value_type": "integer",
        "is_public": true
    }
}
```

> **Note:** `casted_value` returns the setting_value cast to its proper PHP type based on `value_type` (integer, float, boolean, JSON array, or string).

---

#### `DELETE /api/company/settings/{slug}`

Delete a system setting.

**Success Response (200):**

```json
{
    "success": true,
    "message": "Setting deleted successfully."
}
```

---

## 🔢 Slug Generation Rules

Slugs are **automatically generated** from the resource name:

| Resource | Example Name / Key | Generated Slug |
|----------|--------------------|----------------|
| Admin | John Doe | `john-doe` |
| Branch | Bhubaneswar Head Office | `bhubaneswar-head-office` |
| Feature | Live Location Tracking | `live-location-tracking` |
| Department | Human Resource Department | `human-resource-department` |
| Dept. Feature | hr-department + employee-management | `hr-department-employee-management` |
| System Setting | group=`company`, key=`timezone` | `company-timezone` |
| System Setting (exists) | same group + key | `company-timezone-2` |

---

## 📊 Admin Status Values

| Value | Description |
|-------|-------------|
| `active` | Can login and use the API |
| `inactive` | Cannot login (account disabled) |
| `blocked` | Cannot login (account blocked) |

---

## 🧪 Running Tests

```bash
# Run all tests
php artisan test

# Run only Admin API tests
php artisan test --filter AdminApiTest

# Run with coverage (requires Xdebug)
php artisan test --coverage
```

**Test Coverage includes:**
- ✅ Login with valid/invalid credentials
- ✅ Login with email and username
- ✅ Inactive admin cannot login
- ✅ Authenticated logout
- ✅ Unauthenticated request returns 401
- ✅ View own profile
- ✅ List admins with pagination
- ✅ Search admins
- ✅ Filter admins by status
- ✅ Create admin (auto slug)
- ✅ Duplicate name generates unique slug (`-2`, `-3`)
- ✅ View admin by slug
- ✅ 404 for nonexistent slug
- ✅ Update admin
- ✅ Soft delete admin
- ✅ Self-delete returns 403
- ✅ Restore soft-deleted admin
- ✅ Validation errors (422)

---

## 📮 Postman Collection

Import `postman_collection.json` from the project root into Postman.

**Steps:**
1. Open Postman → **Import** → select `postman_collection.json`
2. Set collection variable `base_url` to `http://localhost:8000/api`
3. Run **Login** request — token is **auto-saved** to the `{{token}}` variable
4. All other requests automatically use `{{token}}`

---

## 📁 Project Structure

```
app/
├── Exceptions/
│   └── Handler.php                       # JSON error handling for all API routes
├── Http/
│   ├── Controllers/API/
│   │   ├── AdminAuthController.php       # Admin login, logout, profile
│   │   ├── AdminController.php           # Admin CRUD + restore
│   │   ├── AuthController.php            # Company user register/login/logout
│   │   ├── BranchController.php          # Branch CRUD (company scoped)
│   │   └── CompanyController.php         # Company CRUD
│   ├── Requests/
│   │   ├── Admin/
│   │   │   ├── CreateAdminRequest.php
│   │   │   ├── UpdateAdminRequest.php
│   │   │   ├── LoginRequest.php
│   │   │   └── UpdateProfileRequest.php
│   │   ├── Branch/
│   │   │   ├── StoreBranchRequest.php
│   │   │   └── UpdateBranchRequest.php
│   │   ├── Auth/
│   │   │   └── LoginRequest.php
│   │   └── Company/
│   │       ├── StoreCompanyRequest.php
│   │       └── UpdateCompanyRequest.php
│   └── Resources/
│       ├── AdminResource.php
│       ├── BranchResource.php
│       └── CompanyResource.php
├── Models/
│   ├── Admin.php
│   ├── Branch.php
│   ├── Company.php
│   └── User.php
├── Repositories/
│   ├── AdminRepository.php
│   ├── BranchRepository.php
│   └── CompanyRepository.php
├── Services/
│   ├── AdminService.php
│   ├── AuthService.php
│   ├── BranchService.php
│   └── CompanyService.php
└── Traits/
    └── ApiResponseTrait.php

database/
├── migrations/
│   ├── *_create_admins_table.php
│   ├── *_create_companies_table.php
│   └── *_create_branches_table.php
└── seeders/
    ├── AdminSeeder.php
    └── DatabaseSeeder.php

routes/
└── api.php

postman_collection.json
company_postman_collection.json
```

---

## 🛡️ Security Features

- ✅ **Password hashing** via `Hash::make()` (bcrypt)
- ✅ **Sanctum token authentication** — stateless Bearer tokens
- ✅ **Rate limiting** on login — 5 attempts per minute
- ✅ **Self-delete protection** — admins cannot delete their own account
- ✅ **Validation** on all inputs via FormRequest classes
- ✅ **Soft deletes** — data is never permanently lost
- ✅ **JSON-only API** — consistent error responses for all exceptions

---

## 📄 License

This project is licensed under the **MIT License**.

---

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch: `git checkout -b feature/your-feature`
3. Commit your changes: `git commit -m 'Add some feature'`
4. Push to the branch: `git push origin feature/your-feature`
5. Open a Pull Request
