
# Company Management API

A **production-ready Company Management REST API** built on top of the **Admin Management API** using **Laravel 10 + Laravel Sanctum**.

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
| File Storage | Laravel Storage (public disk) |

---

## ⚙️ Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
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

| | |
|---|---|
| **Method** | `POST` |
| **Auth** | None (public) |
| **Content-Type** | `application/json` |

**Request:**
```json
{
    "login": "admin@example.com",
    "password": "password123"
}
```

---

## Companies

---

### `GET /api/companies`

Get paginated list of companies.

**Query Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `search` | string | Search by name, code, email, legal_name, city |
| `is_active` | int (0/1) | Filter by active status |
| `per_page` | int | Results per page (default: 10) |

---

### `GET /api/companies/{slug}`

Get a single company by slug.

---

### `POST /api/companies`

Create a new company.

> **Use `multipart/form-data`** to upload a logo. Slug is auto-generated from the name.

**All Fields:**

| Field | Type | Rules |
|-------|------|-------|
| `name` | string | **required**, max:150 |
| `code` | string | **required**, max:50, unique |
| `legal_name` | string | nullable, max:200 |
| `email` | string | nullable, valid email, unique |
| `phone` | string | nullable, **exactly 10 digits** |
| `website` | string | nullable, valid URL |
| `tax_number` | string | nullable, max:100 |
| `registration_number` | string | nullable, max:100 |
| `currency_code` | string | **required**, max:10 |
| `timezone` | string | **required**, max:100 |
| `address_line1` | string | nullable, max:255 |
| `address_line2` | string | nullable, max:255 |
| `city` | string | nullable, max:120 |
| `state` | string | nullable, max:120 |
| `country` | string | nullable, max:120 |
| `postal_code` | string | nullable, max:30 |
| `logo` | **file** | nullable, image (jpeg/png/jpg/gif/webp), max **2MB** |
| `is_active` | boolean | nullable |

**Logo Upload Notes:**
- Field name: **`logo`** (not `logo_path`)
- Accepted types: `jpeg`, `png`, `jpg`, `gif`, `webp`
- Max size: **2MB (2048 KB)**
- Stored at: `storage/app/public/logos/`
- Response returns full public URL: `http://localhost:8000/storage/logos/filename.jpg`

**Example Request (multipart/form-data):**
```
POST /api/companies
Content-Type: multipart/form-data

name        = Infosys Ltd
code        = INFY
currency_code = INR
timezone    = Asia/Kolkata
logo        = [file: company_logo.png]
```

**Success Response (201):**
```json
{
    "success": true,
    "message": "Company created successfully.",
    "data": {
        "slug": "infosys-ltd",
        "code": "INFY",
        "name": "Infosys Ltd",
        "logo_path": "http://localhost:8000/storage/logos/abc123.png",
        "is_active": true,
        ...
    }
}
```

---

### `POST /api/companies/{slug}` ← **Use this for logo upload in Postman**
### `PUT  /api/companies/{slug}` ← JSON-only updates (no file upload)

Update a company by slug.

> **Important:** HTTP `PUT` cannot carry file uploads in Postman/most clients.  
> Use **`POST /api/companies/{slug}`** with `multipart/form-data` when uploading a new logo.

**Behavior:**
- If a new `logo` file is provided → **old logo is deleted** from storage, new one is saved
- If `name` changes → **slug is regenerated** (with uniqueness suffix if needed)

---

### `DELETE /api/companies/{slug}`

Delete a company. The logo file is also **deleted from storage** automatically.

---

## 📷 Logo URL in Response

The `logo_path` field always returns a **full public URL**:

```json
{
    "logo_path": "http://localhost:8000/storage/logos/logos/abc123def456.png"
}
```

If no logo is uploaded:
```json
{
    "logo_path": null
}
```

---

## 🔢 Slug Generation

| Name | Generated Slug |
|------|----------------|
| Infosys Ltd | `infosys-ltd` |
| Infosys Ltd (exists) | `infosys-ltd-2` |
| Infosys Ltd (both exist) | `infosys-ltd-3` |

---

## 📊 Error Reference

**Validation Error (422):**
```json
{
    "success": false,
    "message": "Validation error.",
    "errors": {
        "phone":   ["Phone number must be exactly 10 digits."],
        "logo":    ["The logo must be an image.", "The logo must not be greater than 2048 kilobytes."],
        "website": ["Please provide a valid website URL."]
    }
}
```

**Unauthorized (401):**
```json
{ "success": false, "message": "Unauthenticated." }
```

**Not Found (404):**
```json
{ "success": false, "message": "Company not found." }
```

---

## 📁 Project Structure

```
app/
├── Models/Company.php
├── Http/Controllers/API/CompanyController.php
├── Http/Requests/Company/
│   ├── StoreCompanyRequest.php
│   └── UpdateCompanyRequest.php
├── Http/Resources/CompanyResource.php
├── Repositories/CompanyRepository.php
└── Services/CompanyService.php

storage/app/public/logos/    ← uploaded logos stored here
public/storage/              ← symlinked via php artisan storage:link
```

---

## 📮 Postman

Import `company_postman_collection.json`.

For logo upload in Postman:
1. Select request body type → **form-data**
2. Add field `logo`, change type to **File**, browse and select image
3. Set `Authorization: Bearer {{token}}`
4. Send to `POST /api/companies` (create) or `POST /api/companies/{slug}` (update)
