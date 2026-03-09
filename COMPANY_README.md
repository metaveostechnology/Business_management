# Company Management API

A **production-ready Company Management REST API** built on top of the existing **Admin Management API** using **Laravel 10 + Laravel Sanctum**.

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

## ⚙️ Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

---

## 🌐 Base API URL

```
http://localhost:8000/api
```

---

## 🔑 Authentication

All company endpoints require a **Bearer Token** (obtained from admin login).

```
Authorization: Bearer {your_token}
```

---

# 📡 API Endpoints

---

## Auth

### `POST /api/admin/login`

Authenticate an admin and receive a Bearer token.

| | |
|---|---|
| **Method** | `POST` |
| **Auth** | None (public) |

**Request:**
```json
{
    "login": "admin@example.com",
    "password": "password123"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Login successful.",
    "data": {
        "token": "1|abc123sanctumtoken",
        "admin": { ... }
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

---

## Companies

---

### `GET /api/companies`

Get paginated list of all companies.

| | |
|---|---|
| **Method** | `GET` |
| **Auth** | Bearer Token required |

**Query Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `search` | string | Search by name, code, email, legal_name, city |
| `is_active` | int (0/1) | Filter by active status |
| `per_page` | int | Results per page (default: 10) |

**Examples:**
```
GET /api/companies
GET /api/companies?search=infosys
GET /api/companies?is_active=1
GET /api/companies?search=info&is_active=1&per_page=5
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Companies retrieved successfully.",
    "data": [
        {
            "slug": "infosys-ltd",
            "code": "INFY",
            "name": "Infosys Ltd",
            "legal_name": "Infosys Limited",
            "email": "info@infosys.com",
            "phone": "9876543210",
            "website": "https://infosys.com",
            "currency_code": "INR",
            "timezone": "Asia/Kolkata",
            "city": "Bangalore",
            "state": "Karnataka",
            "country": "India",
            "is_active": true,
            "created_at": "2024-01-01 00:00:00",
            "updated_at": "2024-01-01 00:00:00"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 10,
        "total": 25,
        "last_page": 3
    },
    "links": { ... }
}
```

---

### `GET /api/companies/{slug}`

Get a single company by slug.

| | |
|---|---|
| **Method** | `GET` |
| **Auth** | Bearer Token required |

**Example:** `GET /api/companies/infosys-ltd`

**Success Response (200):**
```json
{
    "success": true,
    "message": "Company retrieved successfully.",
    "data": {
        "slug": "infosys-ltd",
        "code": "INFY",
        "name": "Infosys Ltd",
        ...
    }
}
```

**Error Response (404):**
```json
{
    "success": false,
    "message": "Company not found."
}
```

---

### `POST /api/companies`

Create a new company. Slug is **auto-generated** from the name.

| | |
|---|---|
| **Method** | `POST` |
| **Auth** | Bearer Token required |

**Request Body:**
```json
{
    "name": "Infosys Ltd",
    "code": "INFY",
    "legal_name": "Infosys Limited",
    "email": "info@infosys.com",
    "phone": "9876543210",
    "website": "https://infosys.com",
    "tax_number": "GSTIN123456",
    "registration_number": "REG123456",
    "currency_code": "INR",
    "timezone": "Asia/Kolkata",
    "address_line1": "Electronics City",
    "city": "Bangalore",
    "state": "Karnataka",
    "country": "India",
    "postal_code": "560100",
    "is_active": 1
}
```

**Validation Rules:**

| Field | Rules |
|-------|-------|
| `name` | required, string, max:150 |
| `code` | required, string, max:50, unique |
| `email` | nullable, email, unique |
| `phone` | nullable, **exactly 10 digits** |
| `website` | nullable, url |
| `currency_code` | required, string, max:10 |
| `timezone` | required, string, max:100 |
| `is_active` | boolean |

**Success Response (201):**
```json
{
    "success": true,
    "message": "Company created successfully.",
    "data": {
        "slug": "infosys-ltd",
        ...
    }
}
```

---

### `PUT /api/companies/{slug}`

Update a company by slug.

| | |
|---|---|
| **Method** | `PUT` |
| **Auth** | Bearer Token required |

**Request Body (all fields optional):**
```json
{
    "name": "Infosys Technologies",
    "city": "Pune",
    "is_active": 0
}
```

> If `name` is changed, the **slug is automatically regenerated** with uniqueness guarantee.

**Success Response (200):**
```json
{
    "success": true,
    "message": "Company updated successfully.",
    "data": { ... }
}
```

---

### `DELETE /api/companies/{slug}`

Delete a company by slug.

| | |
|---|---|
| **Method** | `DELETE` |
| **Auth** | Bearer Token required |

**Success Response (200):**
```json
{
    "success": true,
    "message": "Company deleted successfully."
}
```

---

## 🔢 Slug Generation

Slugs are auto-generated from the company name:

| Name | Generated Slug |
|------|----------------|
| Infosys Ltd | `infosys-ltd` |
| Infosys Ltd (exists) | `infosys-ltd-2` |
| Infosys Ltd (both exist) | `infosys-ltd-3` |

---

## 📊 Error Response Format

**Validation Error (422):**
```json
{
    "success": false,
    "message": "Validation error.",
    "errors": {
        "phone": ["Phone number must be exactly 10 digits."],
        "code": ["This company code is already in use."]
    }
}
```

**Unauthorized (401):**
```json
{
    "success": false,
    "message": "Unauthenticated."
}
```

---

## 📁 Project Structure

```
app/
├── Models/
│   ├── Admin.php
│   └── Company.php
├── Http/Controllers/API/
│   ├── AdminAuthController.php
│   ├── AdminController.php
│   └── CompanyController.php          ← new
├── Http/Requests/
│   ├── Admin/
│   └── Company/
│       ├── StoreCompanyRequest.php    ← new
│       └── UpdateCompanyRequest.php   ← new
├── Http/Resources/
│   ├── AdminResource.php
│   └── CompanyResource.php            ← new
├── Repositories/
│   ├── AdminRepository.php
│   └── CompanyRepository.php          ← new
├── Services/
│   ├── AdminService.php
│   └── CompanyService.php             ← new
└── Traits/ApiResponseTrait.php

database/migrations/
├── *_create_admins_table.php
└── *_create_companies_table.php       ← new
```

---

## 📮 Postman Collection

Import `company_postman_collection.json` from the project root.

1. Open Postman → **Import** → select the file
2. Set `base_url` = `http://localhost:8000/api`
3. Run **Admin Login** — token auto-saved
4. All company endpoints use `{{token}}` automatically
