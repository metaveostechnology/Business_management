# Admin Management REST API

A **production-ready Admin Management REST API** built with **Laravel 10** and **Laravel Sanctum**, following the **Repository + Service Pattern** with full API Resource transformation and FormRequest validation.

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

### Public Routes

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

#### `GET /api/admins`

List all admins with **pagination**, **search**, and **status filter**.

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

## 🔢 Slug Generation Rules

Slugs are **automatically generated** from the admin's name:

| Name | Generated Slug |
|------|----------------|
| John Doe | `john-doe` |
| John Doe (exists) | `john-doe-2` |
| John Doe (both exist) | `john-doe-3` |

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
│   └── Handler.php                    # JSON error handling for all API routes
├── Http/
│   ├── Controllers/API/
│   │   ├── AdminAuthController.php    # Login, logout, profile
│   │   └── AdminController.php        # Admin CRUD + restore
│   ├── Requests/Admin/
│   │   ├── CreateAdminRequest.php
│   │   ├── UpdateAdminRequest.php
│   │   ├── LoginRequest.php
│   │   └── UpdateProfileRequest.php
│   └── Resources/
│       └── AdminResource.php
├── Models/
│   └── Admin.php
├── Repositories/
│   └── AdminRepository.php
├── Services/
│   └── AdminService.php
└── Traits/
    └── ApiResponseTrait.php

database/
├── factories/AdminFactory.php
├── migrations/*_create_admins_table.php
└── seeders/
    ├── AdminSeeder.php
    └── DatabaseSeeder.php

routes/
└── api.php

tests/Feature/
└── AdminApiTest.php

postman_collection.json
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
