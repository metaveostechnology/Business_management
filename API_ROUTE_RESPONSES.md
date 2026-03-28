# API Route Response Report

- Generated at: `2026-03-28 07:25:01`
- Workspace: `D:\wamp_server\www\projects\Business_Management\Business Management`
- Total requests executed: `89`
- Successful responses (`< 400`): `49`
- Non-success responses (`>= 400`): `40`

## Summary

| # | Route | Status |
| --- | --- | --- |
| 1 | `POST /api/admin/login` | `500` |
| 2 | `POST /api/register-company` | `201` |
| 3 | `POST /api/company/login` | `200` |
| 4 | `POST /api/company/branches` | `201` |
| 5 | `POST /api/company/features` | `500` |
| 6 | `POST /api/company/roles` | `201` |
| 7 | `POST /api/company/settings` | `500` |
| 8 | `POST /api/company/department-features` | `500` |
| 9 | `POST /api/companies` | `201` |
| 10 | `POST /api/admin/companies` | `401` |
| 11 | `GET /api/admin/profile` | `401` |
| 12 | `PUT /api/admin/profile` | `401` |
| 13 | `POST /api/admins` | `401` |
| 14 | `GET /api/admins` | `401` |
| 15 | `GET /api/admins/{slug}` | `401` |
| 16 | `PUT /api/admins/{slug}` | `500` |
| 17 | `POST /api/admins/{slug}` | `401` |
| 18 | `DELETE /api/admins/{slug}` | `500` |
| 19 | `POST /api/admins/{slug}/restore` | `404` |
| 20 | `GET /api/admin/companies` | `401` |
| 21 | `GET /api/admin/companies/{slug}` | `401` |
| 22 | `PUT /api/admin/companies/{slug}` | `500` |
| 23 | `DELETE /api/admin/companies/{slug}` | `500` |
| 24 | `GET /api/company/profile` | `200` |
| 25 | `PUT /api/company/profile` | `200` |
| 26 | `POST /api/company/change-password` | `200` |
| 27 | `GET /api/companies` | `200` |
| 28 | `GET /api/companies/{slug}` | `200` |
| 29 | `PUT /api/companies/{slug}` | `200` |
| 30 | `POST /api/companies/{slug}` | `404` |
| 31 | `DELETE /api/companies/{slug}` | `404` |
| 32 | `GET /api/company/branches` | `200` |
| 33 | `GET /api/company/branches/{slug}` | `200` |
| 34 | `PUT /api/company/branches/{slug}` | `200` |
| 35 | `DELETE /api/company/branches/{slug}` | `200` |
| 36 | `GET /api/company/features` | `500` |
| 37 | `GET /api/company/features/{slug}` | `500` |
| 38 | `PUT /api/company/features/{slug}` | `500` |
| 39 | `GET /api/company/departments` | `200` |
| 40 | `GET /api/company/departments/{slug}` | `200` |
| 41 | `GET /api/company/department-features` | `500` |
| 42 | `GET /api/company/department-features/{slug}` | `500` |
| 43 | `PUT /api/company/department-features/{slug}` | `500` |
| 44 | `DELETE /api/company/department-features/{slug}` | `500` |
| 45 | `GET /api/company/settings` | `500` |
| 46 | `GET /api/company/settings/{slug}` | `500` |
| 47 | `PUT /api/company/settings/{slug}` | `500` |
| 48 | `DELETE /api/company/settings/{slug}` | `500` |
| 49 | `GET /api/company/roles` | `200` |
| 50 | `GET /api/company/roles/{slug}` | `200` |
| 51 | `PUT /api/company/roles/{slug}` | `200` |
| 52 | `DELETE /api/company/roles/{slug}` | `404` |
| 53 | `POST /api/company/branch-users` | `201` |
| 54 | `GET /api/company/branch-users` | `200` |
| 55 | `GET /api/company/branch-users/{slug}` | `200` |
| 56 | `PUT /api/company/branch-users/{slug}` | `200` |
| 57 | `POST /api/company/branch-users/{slug}/change-password` | `404` |
| 58 | `DELETE /api/company/branch-users/{slug}` | `404` |
| 59 | `POST /api/branch-admin/login` | `200` |
| 60 | `GET /api/branch-admin/profile` | `200` |
| 61 | `GET /api/departments` | `200` |
| 62 | `POST /api/branch/employees` | `201` |
| 63 | `GET /api/branch/employees` | `200` |
| 64 | `GET /api/branch/employees/{slug}` | `200` |
| 65 | `PUT /api/branch/employees/{slug}` | `200` |
| 66 | `POST /api/branch/employees/{slug}` | `404` |
| 67 | `DELETE /api/branch/employees/{slug}` | `404` |
| 68 | `POST /api/branch-admin/logout` | `200` |
| 69 | `POST /api/dept-admin/login` | `200` |
| 70 | `GET /api/dept-admin/profile` | `200` |
| 71 | `POST /api/dept/employees` | `201` |
| 72 | `GET /api/dept/employees` | `200` |
| 73 | `GET /api/dept/employees/{slug}` | `200` |
| 74 | `PUT /api/dept/employees/{slug}` | `200` |
| 75 | `DELETE /api/dept/employees/{slug}` | `404` |
| 76 | `POST /api/dept-admin/logout` | `200` |
| 77 | `POST /api/dept-employee/login` | `200` |
| 78 | `GET /api/dept-employee/profile` | `200` |
| 79 | `POST /api/dept-employee/change-password` | `200` |
| 80 | `POST /api/dept-employee/login` | `401` |
| 81 | `POST /api/dept-employee/logout` | `200` |
| 82 | `DELETE /api/company/features/{slug}` | `404` |
| 83 | `POST /api/employee/login` | `200` |
| 84 | `GET /api/employee/profile` | `200` |
| 85 | `GET /api/employee/attendance` | `200` |
| 86 | `GET /api/employee/attendance/{id}` | `200` |
| 87 | `POST /api/employee/logout` | `200` |
| 88 | `POST /api/company/logout` | `200` |
| 89 | `POST /api/admin/logout` | `401` |

## Detailed Responses

### 1. Admin login

- Route: `POST /api/admin/login`
- Status: `500`

**Request Payload**

```json
{
    "login": "admin@example.com",
    "password": "password123"
}
```

**Response Body**

```json
{
    "success": false,
    "message": "An error occurred during login. Please try again."
}
```

### 2. Register company

- Route: `POST /api/register-company`
- Status: `201`

**Request Payload**

```json
{
    "name": "Codex Company BAJ9",
    "email": "company-xqmgtd@example.com",
    "phone": "9000000001",
    "password": "secret123",
    "password_confirmation": "secret123",
    "address": "123 Codex Street",
    "website": "https://example.com"
}
```

**Response Body**

```json
{
    "success": true,
    "message": "Company registered successfully.",
    "data": {
        "id": 20,
        "slug": "codex-company-baj9",
        "name": "Codex Company BAJ9",
        "legal_name": null,
        "email": "company-xqmgtd@example.com",
        "phone": "9000000001",
        "website": "https://example.com",
        "logo": null,
        "address": null,
        "address_line1": null,
        "address_line2": null,
        "city": null,
        "state": null,
        "country": null,
        "postal_code": null,
        "tax_number": null,
        "registration_number": null,
        "currency_code": null,
        "timezone": null,
        "is_active": null,
        "is_delete": null,
        "created_at": "2026-03-28 07:24:51",
        "updated_at": "2026-03-28 07:24:51",
        "code": "CMP-XGVWKT"
    }
}
```

### 3. Company login

- Route: `POST /api/company/login`
- Status: `200`

**Request Payload**

```json
{
    "email": "company-xqmgtd@example.com",
    "password": "secret123"
}
```

**Response Body**

```json
{
    "success": true,
    "message": "Login successful.",
    "data": {
        "company": "Codex Company BAJ9",
        "email": "company-xqmgtd@example.com",
        "token": "28|SHcq5uI7EVJG6dTleKFC5vcTe5UzdQuMjnsl7Pbo4b719579",
        "profile": {
            "id": 20,
            "slug": "codex-company-baj9",
            "name": "Codex Company BAJ9",
            "legal_name": null,
            "email": "company-xqmgtd@example.com",
            "phone": "9000000001",
            "website": "https://example.com",
            "logo": null,
            "address": null,
            "address_line1": null,
            "address_line2": null,
            "city": null,
            "state": null,
            "country": null,
            "postal_code": null,
            "tax_number": null,
            "registration_number": null,
            "currency_code": "INR",
            "timezone": "Asia/Kolkata",
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-28 07:24:51",
            "updated_at": "2026-03-28 07:24:51",
            "code": "CMP-XGVWKT"
        }
    }
}
```

### 4. Create branch

- Route: `POST /api/company/branches`
- Status: `201`

**Request Payload**

```json
{
    "code": "BRHKUWFQ",
    "name": "Main Branch RNPA",
    "email": "main-branch-kpxncr@example.com",
    "phone": "9000000002",
    "address_line1": "Main branch address",
    "city": "Kolkata",
    "state": "West Bengal",
    "country": "India",
    "postal_code": "700001",
    "is_head_office": true,
    "is_active": true
}
```

**Response Body**

```json
{
    "success": true,
    "message": "Branch created successfully.",
    "data": {
        "id": 13,
        "company_id": 20,
        "code": "BRHKUWFQ",
        "name": "Main Branch RNPA",
        "slug": "main-branch-rnpa",
        "email": "main-branch-kpxncr@example.com",
        "phone": "9000000002",
        "manager_user_id": null,
        "address_line1": "Main branch address",
        "address_line2": null,
        "city": "Kolkata",
        "state": "West Bengal",
        "country": "India",
        "postal_code": "700001",
        "google_map_link": null,
        "is_head_office": true,
        "is_active": true,
        "created_at": "2026-03-28 07:24:51",
        "updated_at": "2026-03-28 07:24:51"
    }
}
```

### 5. Create feature

- Route: `POST /api/company/features`
- Status: `500`

**Request Payload**

```json
{
    "code": "FTUK2XJH",
    "name": "Primary Feature IER0",
    "category": "operations",
    "description": "Primary feature for API tests",
    "icon": "settings",
    "sort_order": 1,
    "is_system": false,
    "is_active": true
}
```

**Response Body**

```json
{
    "success": false,
    "message": "An error occurred while creating the feature."
}
```

### 6. Create role

- Route: `POST /api/company/roles`
- Status: `201`

**Request Payload**

```json
{
    "name": "Supervisor NTAW",
    "description": "Role for route testing",
    "is_active": true
}
```

**Response Body**

```json
{
    "success": true,
    "message": "Role created successfully.",
    "data": {
        "id": 7,
        "name": "Supervisor NTAW",
        "slug": "supervisor-ntaw",
        "description": "Role for route testing",
        "is_active": true,
        "created_at": "2026-03-28 07:24:51",
        "updated_at": "2026-03-28 07:24:51"
    }
}
```

### 7. Create system setting

- Route: `POST /api/company/settings`
- Status: `500`

**Request Payload**

```json
{
    "setting_group": "general",
    "setting_key": "timezone-u6jd6z",
    "setting_value": "Asia/Calcutta",
    "value_type": "string",
    "branch_id": 13,
    "is_public": true
}
```

**Response Body**

```json
{
    "success": false,
    "message": "An error occurred while creating the setting."
}
```

### 8. Create department feature mapping

- Route: `POST /api/company/department-features`
- Status: `500`

**Request Payload**

```json
{
    "department_id": 7,
    "feature_id": 10,
    "access_level": "full",
    "is_enabled": true
}
```

**Response Body**

```json
{
    "success": false,
    "message": "An error occurred while assigning the feature."
}
```

### 9. Company CRUD create via /api/companies

- Route: `POST /api/companies`
- Status: `201`

**Request Payload**

```json
{
    "name": "Managed Company EEIQ",
    "code": "CMP9ROKTM",
    "email": "managed-company-zakdcf@example.com",
    "password": "password123",
    "phone": "9123456789",
    "currency_code": "INR",
    "timezone": "Asia/Calcutta",
    "address_line1": "Managed company address",
    "city": "Kolkata",
    "state": "West Bengal",
    "country": "India",
    "postal_code": "700001",
    "is_active": true
}
```

**Response Body**

```json
{
    "success": true,
    "message": "Company created successfully.",
    "data": {
        "id": 21,
        "slug": "managed-company-eeiq",
        "name": "Managed Company EEIQ",
        "legal_name": null,
        "email": "managed-company-zakdcf@example.com",
        "phone": "9123456789",
        "website": null,
        "logo": null,
        "address": null,
        "address_line1": "Managed company address",
        "address_line2": null,
        "city": "Kolkata",
        "state": "West Bengal",
        "country": "India",
        "postal_code": "700001",
        "tax_number": null,
        "registration_number": null,
        "currency_code": "INR",
        "timezone": "Asia/Calcutta",
        "is_active": true,
        "is_delete": null,
        "created_at": "2026-03-28 07:24:52",
        "updated_at": "2026-03-28 07:24:52",
        "code": "CMP-EGIMCM"
    }
}
```

### 10. Admin company CRUD create via /api/admin/companies

- Route: `POST /api/admin/companies`
- Status: `401`

**Request Payload**

```json
{
    "name": "Admin Managed QOLP",
    "email": "admin-company-9bcuaz@example.com",
    "phone": "9234567890",
    "password": "secret123",
    "password_confirmation": "secret123",
    "website": "https://example.org",
    "currency_code": "INR",
    "timezone": "Asia/Calcutta",
    "is_active": true
}
```

**Response Body**

```json
{
    "success": false,
    "message": "Unauthenticated. Please login to access this resource."
}
```

### 11. Admin profile

- Route: `GET /api/admin/profile`
- Status: `401`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "Unauthenticated. Please login to access this resource."
}
```

### 12. Admin update profile

- Route: `PUT /api/admin/profile`
- Status: `401`

**Request Payload**

```json
{
    "phone": "9999990000"
}
```

**Response Body**

```json
{
    "success": false,
    "message": "Unauthenticated. Please login to access this resource."
}
```

### 13. Create admin

- Route: `POST /api/admins`
- Status: `401`

**Request Payload**

```json
{
    "name": "Codex Secondary Admin",
    "email": "secondary-admin-bqpqco@example.com",
    "phone": "9012345678",
    "username": "secondary_admin-i6yv3p",
    "password": "password123",
    "password_confirmation": "password123",
    "status": "active"
}
```

**Response Body**

```json
{
    "success": false,
    "message": "Unauthenticated. Please login to access this resource."
}
```

### 14. List admins

- Route: `GET /api/admins`
- Status: `401`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "Unauthenticated. Please login to access this resource."
}
```

### 15. Show admin

- Route: `GET /api/admins/{slug}`
- Tested URI: `/api/admins/`
- Status: `401`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "Unauthenticated. Please login to access this resource."
}
```

### 16. Update admin (PUT)

- Route: `PUT /api/admins/{slug}`
- Tested URI: `/api/admins/`
- Status: `500`

**Request Payload**

```json
{
    "name": "Codex Secondary Admin Updated"
}
```

**Response Body**

```json
{
    "success": false,
    "message": "The PUT method is not supported for route api/admins. Supported methods: GET, HEAD, POST."
}
```

### 17. Update admin (POST)

- Route: `POST /api/admins/{slug}`
- Tested URI: `/api/admins/`
- Status: `401`

**Request Payload**

```json
{
    "name": "Codex Secondary Admin Post Updated"
}
```

**Response Body**

```json
{
    "success": false,
    "message": "Unauthenticated. Please login to access this resource."
}
```

### 18. Delete admin

- Route: `DELETE /api/admins/{slug}`
- Tested URI: `/api/admins/`
- Status: `500`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "The DELETE method is not supported for route api/admins. Supported methods: GET, HEAD, POST."
}
```

### 19. Restore admin

- Route: `POST /api/admins/{slug}/restore`
- Tested URI: `/api/admins//restore`
- Status: `404`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "The requested resource was not found."
}
```

### 20. List admin companies

- Route: `GET /api/admin/companies`
- Status: `401`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "Unauthenticated. Please login to access this resource."
}
```

### 21. Show admin company

- Route: `GET /api/admin/companies/{slug}`
- Tested URI: `/api/admin/companies/`
- Status: `401`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "Unauthenticated. Please login to access this resource."
}
```

### 22. Update admin company

- Route: `PUT /api/admin/companies/{slug}`
- Tested URI: `/api/admin/companies/`
- Status: `500`

**Request Payload**

```json
{
    "name": "Admin Managed Updated",
    "phone": "9345678901"
}
```

**Response Body**

```json
{
    "success": false,
    "message": "The PUT method is not supported for route api/admin/companies. Supported methods: GET, HEAD, POST."
}
```

### 23. Delete admin company

- Route: `DELETE /api/admin/companies/{slug}`
- Tested URI: `/api/admin/companies/`
- Status: `500`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "The DELETE method is not supported for route api/admin/companies. Supported methods: GET, HEAD, POST."
}
```

### 24. Company profile

- Route: `GET /api/company/profile`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": true,
    "message": "Profile retrieved successfully.",
    "data": {
        "id": 20,
        "slug": "codex-company-baj9",
        "name": "Codex Company BAJ9",
        "legal_name": null,
        "email": "company-xqmgtd@example.com",
        "phone": "9000000001",
        "website": "https://example.com",
        "logo": null,
        "address": null,
        "address_line1": null,
        "address_line2": null,
        "city": null,
        "state": null,
        "country": null,
        "postal_code": null,
        "tax_number": null,
        "registration_number": null,
        "currency_code": "INR",
        "timezone": "Asia/Kolkata",
        "is_active": true,
        "is_delete": false,
        "created_at": "2026-03-28 07:24:51",
        "updated_at": "2026-03-28 07:24:51",
        "code": "CMP-XGVWKT"
    }
}
```

### 25. Update company profile

- Route: `PUT /api/company/profile`
- Status: `200`

**Request Payload**

```json
{
    "name": "Codex Company Updated",
    "phone": "9000000011"
}
```

**Response Body**

```json
{
    "success": true,
    "message": "Profile updated successfully.",
    "data": {
        "id": 20,
        "slug": "codex-company-updated-3",
        "name": "Codex Company Updated",
        "legal_name": null,
        "email": "company-xqmgtd@example.com",
        "phone": "9000000011",
        "website": "https://example.com",
        "logo": null,
        "address": null,
        "address_line1": null,
        "address_line2": null,
        "city": null,
        "state": null,
        "country": null,
        "postal_code": null,
        "tax_number": null,
        "registration_number": null,
        "currency_code": "INR",
        "timezone": "Asia/Kolkata",
        "is_active": true,
        "is_delete": false,
        "created_at": "2026-03-28 07:24:51",
        "updated_at": "2026-03-28 07:24:54",
        "code": "CMP-XGVWKT"
    }
}
```

### 26. Change company password

- Route: `POST /api/company/change-password`
- Status: `200`

**Request Payload**

```json
{
    "current_password": "secret123",
    "new_password": "secret456",
    "new_password_confirmation": "secret456"
}
```

**Response Body**

```json
{
    "success": true,
    "message": "Password updated successfully."
}
```

### 27. List companies

- Route: `GET /api/companies`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": true,
    "message": "Companies retrieved successfully.",
    "data": [
        {
            "id": 21,
            "slug": "managed-company-eeiq",
            "name": "Managed Company EEIQ",
            "legal_name": null,
            "email": "managed-company-zakdcf@example.com",
            "phone": "9123456789",
            "website": null,
            "logo": null,
            "address": null,
            "address_line1": "Managed company address",
            "address_line2": null,
            "city": "Kolkata",
            "state": "West Bengal",
            "country": "India",
            "postal_code": "700001",
            "tax_number": null,
            "registration_number": null,
            "currency_code": "INR",
            "timezone": "Asia/Calcutta",
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-28 07:24:52",
            "updated_at": "2026-03-28 07:24:52",
            "code": "CMP-EGIMCM"
        },
        {
            "id": 20,
            "slug": "codex-company-updated-3",
            "name": "Codex Company Updated",
            "legal_name": null,
            "email": "company-xqmgtd@example.com",
            "phone": "9000000011",
            "website": "https://example.com",
            "logo": null,
            "address": null,
            "address_line1": null,
            "address_line2": null,
            "city": null,
            "state": null,
            "country": null,
            "postal_code": null,
            "tax_number": null,
            "registration_number": null,
            "currency_code": "INR",
            "timezone": "Asia/Kolkata",
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-28 07:24:51",
            "updated_at": "2026-03-28 07:24:54",
            "code": "CMP-XGVWKT"
        },
        {
            "id": 19,
            "slug": "managed-company-updated-2",
            "name": "Managed Company Updated",
            "legal_name": null,
            "email": "managed-company-zigyxw@example.com",
            "phone": "9123456789",
            "website": null,
            "logo": null,
            "address": null,
            "address_line1": "Managed company address",
            "address_line2": null,
            "city": "Kolkata",
            "state": "West Bengal",
            "country": "India",
            "postal_code": "700001",
            "tax_number": null,
            "registration_number": null,
            "currency_code": "USD",
            "timezone": "UTC",
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-28 07:24:14",
            "updated_at": "2026-03-28 07:24:17",
            "code": "CMP-E3YAHV"
        },
        {
            "id": 18,
            "slug": "codex-company-updated-2",
            "name": "Codex Company Updated",
            "legal_name": null,
            "email": "company-cgqqbu@example.com",
            "phone": "9000000011",
            "website": "https://example.com",
            "logo": null,
            "address": null,
            "address_line1": null,
            "address_line2": null,
            "city": null,
            "state": null,
            "country": null,
            "postal_code": null,
            "tax_number": null,
            "registration_number": null,
            "currency_code": "INR",
            "timezone": "Asia/Kolkata",
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-28 07:24:13",
            "updated_at": "2026-03-28 07:24:17",
            "code": "CMP-SYGFK5"
        },
        {
            "id": 16,
            "slug": "codex-company-updated",
            "name": "Codex Company Updated",
            "legal_name": null,
            "email": "company-jqyknt@example.com",
            "phone": "9000000011",
            "website": "https://example.com",
            "logo": null,
            "address": null,
            "address_line1": null,
            "address_line2": null,
            "city": null,
            "state": null,
            "country": null,
            "postal_code": null,
            "tax_number": null,
            "registration_number": null,
            "currency_code": "INR",
            "timezone": "Asia/Kolkata",
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-28 07:18:11",
            "updated_at": "2026-03-28 07:18:14",
            "code": "CMP-IOGJK0"
        },
        {
            "id": 17,
            "slug": "managed-company-updated",
            "name": "Managed Company Updated",
            "legal_name": null,
            "email": "managed-company-xdnawy@example.com",
            "phone": "9123456789",
            "website": null,
            "logo": null,
            "address": null,
            "address_line1": "Managed company address",
            "address_line2": null,
            "city": "Kolkata",
            "state": "West Bengal",
            "country": "India",
            "postal_code": "700001",
            "tax_number": null,
            "registration_number": null,
            "currency_code": "USD",
            "timezone": "UTC",
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-28 07:18:11",
            "updated_at": "2026-03-28 07:18:14",
            "code": "CMP-U9D8ET"
        },
        {
            "id": 13,
            "slug": "self-register-qzwu",
            "name": "Self Register QZWU",
            "legal_name": null,
            "email": "register.company.20260327_103847_tcqzwu@example.com",
            "phone": "9000001000",
            "website": "https://register.example.test",
            "logo": null,
            "address": null,
            "address_line1": null,
            "address_line2": null,
            "city": null,
            "state": null,
            "country": null,
            "postal_code": null,
            "tax_number": null,
            "registration_number": null,
            "currency_code": "INR",
            "timezone": "Asia/Kolkata",
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-27 10:38:50",
            "updated_at": "2026-03-27 10:38:50",
            "code": "CMP-K9E296"
        },
        {
            "id": 12,
            "slug": "apitest-company-kkauxlrx",
            "name": "API Test Company QZWU",
            "legal_name": null,
            "email": "apitest.company.20260327_103847_tcqzwu@example.com",
            "phone": "9000000002",
            "website": "https://example.test",
            "logo": null,
            "address": "Initial Address",
            "address_line1": null,
            "address_line2": null,
            "city": null,
            "state": null,
            "country": null,
            "postal_code": null,
            "tax_number": null,
            "registration_number": null,
            "currency_code": "INR",
            "timezone": "Asia/Kolkata",
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-27 10:38:48",
            "updated_at": "2026-03-27 10:38:48",
            "code": "CMP-9BT31"
        },
        {
            "id": 9,
            "slug": "self-register-yscc",
            "name": "Self Register YSCC",
            "legal_name": null,
            "email": "register.company.20260327_095025_atyscc@example.com",
            "phone": "9000001000",
            "website": "https://register.example.test",
            "logo": null,
            "address": null,
            "address_line1": null,
            "address_line2": null,
            "city": null,
            "state": null,
            "country": null,
            "postal_code": null,
            "tax_number": null,
            "registration_number": null,
            "currency_code": "INR",
            "timezone": "Asia/Kolkata",
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-27 09:50:27",
            "updated_at": "2026-03-27 09:50:27",
            "code": "CMP-HIQYPH"
        },
        {
            "id": 8,
            "slug": "apitest-company-gvbjfayv",
            "name": "API Test Company YSCC",
            "legal_name": null,
            "email": "apitest.company.20260327_095025_atyscc@example.com",
            "phone": "9000000002",
            "website": "https://example.test",
            "logo": null,
            "address": "Initial Address",
            "address_line1": null,
            "address_line2": null,
            "city": null,
            "state": null,
            "country": null,
            "postal_code": null,
            "tax_number": null,
            "registration_number": null,
            "currency_code": "INR",
            "timezone": "Asia/Kolkata",
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-27 09:50:26",
            "updated_at": "2026-03-27 09:50:26",
            "code": "CMP-KHVYD"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 10,
        "total": 14,
        "last_page": 2,
        "from": 1,
        "to": 10
    },
    "links": {
        "first": "http://localhost/api/companies?page=1",
        "last": "http://localhost/api/companies?page=2",
        "prev": null,
        "next": "http://localhost/api/companies?page=2"
    }
}
```

### 28. Show company

- Route: `GET /api/companies/{slug}`
- Tested URI: `/api/companies/managed-company-eeiq`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": true,
    "message": "Company retrieved successfully.",
    "data": {
        "id": 21,
        "slug": "managed-company-eeiq",
        "name": "Managed Company EEIQ",
        "legal_name": null,
        "email": "managed-company-zakdcf@example.com",
        "phone": "9123456789",
        "website": null,
        "logo": null,
        "address": null,
        "address_line1": "Managed company address",
        "address_line2": null,
        "city": "Kolkata",
        "state": "West Bengal",
        "country": "India",
        "postal_code": "700001",
        "tax_number": null,
        "registration_number": null,
        "currency_code": "INR",
        "timezone": "Asia/Calcutta",
        "is_active": true,
        "is_delete": false,
        "created_at": "2026-03-28 07:24:52",
        "updated_at": "2026-03-28 07:24:52",
        "code": "CMP-EGIMCM"
    }
}
```

### 29. Update company (PUT)

- Route: `PUT /api/companies/{slug}`
- Tested URI: `/api/companies/managed-company-eeiq`
- Status: `200`

**Request Payload**

```json
{
    "name": "Managed Company Updated",
    "currency_code": "USD",
    "timezone": "UTC"
}
```

**Response Body**

```json
{
    "success": true,
    "message": "Company updated successfully.",
    "data": {
        "id": 21,
        "slug": "managed-company-updated-3",
        "name": "Managed Company Updated",
        "legal_name": null,
        "email": "managed-company-zakdcf@example.com",
        "phone": "9123456789",
        "website": null,
        "logo": null,
        "address": null,
        "address_line1": "Managed company address",
        "address_line2": null,
        "city": "Kolkata",
        "state": "West Bengal",
        "country": "India",
        "postal_code": "700001",
        "tax_number": null,
        "registration_number": null,
        "currency_code": "USD",
        "timezone": "UTC",
        "is_active": true,
        "is_delete": false,
        "created_at": "2026-03-28 07:24:52",
        "updated_at": "2026-03-28 07:24:54",
        "code": "CMP-EGIMCM"
    }
}
```

### 30. Update company (POST)

- Route: `POST /api/companies/{slug}`
- Tested URI: `/api/companies/managed-company-eeiq`
- Status: `404`

**Request Payload**

```json
{
    "name": "Managed Company Post Updated",
    "currency_code": "INR",
    "timezone": "Asia/Calcutta"
}
```

**Response Body**

```json
{
    "success": false,
    "message": "Company not found."
}
```

### 31. Delete company

- Route: `DELETE /api/companies/{slug}`
- Tested URI: `/api/companies/managed-company-eeiq`
- Status: `404`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "Company not found."
}
```

### 32. List branches

- Route: `GET /api/company/branches`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": true,
    "message": "Branches retrieved successfully.",
    "data": [
        {
            "id": 13,
            "company_id": 20,
            "code": "BRHKUWFQ",
            "name": "Main Branch RNPA",
            "slug": "main-branch-rnpa",
            "email": "main-branch-kpxncr@example.com",
            "phone": "9000000002",
            "manager_user_id": null,
            "address_line1": "Main branch address",
            "address_line2": null,
            "city": "Kolkata",
            "state": "West Bengal",
            "country": "India",
            "postal_code": "700001",
            "google_map_link": null,
            "is_head_office": true,
            "is_active": true,
            "created_at": "2026-03-28 07:24:51",
            "updated_at": "2026-03-28 07:24:51"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 10,
        "total": 1,
        "last_page": 1,
        "from": 1,
        "to": 1
    },
    "links": {
        "first": "http://localhost/api/company/branches?page=1",
        "last": "http://localhost/api/company/branches?page=1",
        "prev": null,
        "next": null
    }
}
```

### 33. Show branch

- Route: `GET /api/company/branches/{slug}`
- Tested URI: `/api/company/branches/main-branch-rnpa`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": true,
    "message": "Branch retrieved successfully.",
    "data": {
        "id": 13,
        "company_id": 20,
        "code": "BRHKUWFQ",
        "name": "Main Branch RNPA",
        "slug": "main-branch-rnpa",
        "email": "main-branch-kpxncr@example.com",
        "phone": "9000000002",
        "manager_user_id": null,
        "address_line1": "Main branch address",
        "address_line2": null,
        "city": "Kolkata",
        "state": "West Bengal",
        "country": "India",
        "postal_code": "700001",
        "google_map_link": null,
        "is_head_office": true,
        "is_active": true,
        "created_at": "2026-03-28 07:24:51",
        "updated_at": "2026-03-28 07:24:51"
    }
}
```

### 34. Update branch

- Route: `PUT /api/company/branches/{slug}`
- Tested URI: `/api/company/branches/main-branch-rnpa`
- Status: `200`

**Request Payload**

```json
{
    "name": "Main Branch Updated"
}
```

**Response Body**

```json
{
    "success": true,
    "message": "Branch updated successfully.",
    "data": {
        "id": 13,
        "company_id": 20,
        "code": "BRHKUWFQ",
        "name": "Main Branch Updated",
        "slug": "main-branch-updated-3",
        "email": "main-branch-kpxncr@example.com",
        "phone": "9000000002",
        "manager_user_id": null,
        "address_line1": "Main branch address",
        "address_line2": null,
        "city": "Kolkata",
        "state": "West Bengal",
        "country": "India",
        "postal_code": "700001",
        "google_map_link": null,
        "is_head_office": true,
        "is_active": true,
        "created_at": "2026-03-28 07:24:51",
        "updated_at": "2026-03-28 07:24:55"
    }
}
```

### 35. Delete branch

- Route: `DELETE /api/company/branches/{slug}`
- Tested URI: `/api/company/branches/codex-20260328-072450-disposable-branch-pde42h`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": true,
    "message": "Branch deleted successfully."
}
```

### 36. List features

- Route: `GET /api/company/features`
- Status: `500`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "An error occurred while fetching features."
}
```

### 37. Show feature

- Route: `GET /api/company/features/{slug}`
- Tested URI: `/api/company/features/codex-20260328-072450-feature-3hp25s`
- Status: `500`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "An error occurred while fetching the feature."
}
```

### 38. Update feature

- Route: `PUT /api/company/features/{slug}`
- Tested URI: `/api/company/features/codex-20260328-072450-feature-3hp25s`
- Status: `500`

**Request Payload**

```json
{
    "name": "Primary Feature Updated",
    "category": "updated-operations"
}
```

**Response Body**

```json
{
    "success": false,
    "message": "An error occurred while updating the feature."
}
```

### 39. List company departments

- Route: `GET /api/company/departments`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": true,
    "message": "Departments retrieved successfully.",
    "data": [
        {
            "id": 2,
            "company_id": null,
            "branch_id": null,
            "slug": "apitest-department-i9vgsyqy",
            "parent_department_id": null,
            "code": "DPT-1FSC",
            "name": "API Test Department HAXQ",
            "description": "Created by automated API route report",
            "head_user_id": null,
            "level_no": 1,
            "reports_to_department_id": null,
            "approval_mode": "single",
            "escalation_mode": "none",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_at": "2026-03-27T09:49:53.000000Z",
            "updated_at": "2026-03-27T09:49:53.000000Z"
        },
        {
            "id": 1,
            "company_id": null,
            "branch_id": null,
            "slug": "apitest-department-x2qpkxh7",
            "parent_department_id": null,
            "code": "DPT-5BKS",
            "name": "API Test Department KYBP",
            "description": "Created by automated API route report",
            "head_user_id": null,
            "level_no": 1,
            "reports_to_department_id": null,
            "approval_mode": "single",
            "escalation_mode": "none",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_at": "2026-03-27T09:48:31.000000Z",
            "updated_at": "2026-03-27T09:48:31.000000Z"
        },
        {
            "id": 4,
            "company_id": null,
            "branch_id": null,
            "slug": "apitest-department-wbplk34t",
            "parent_department_id": null,
            "code": "DPT-TGJF",
            "name": "API Test Department QZWU",
            "description": "Created by automated API route report",
            "head_user_id": null,
            "level_no": 1,
            "reports_to_department_id": null,
            "approval_mode": "single",
            "escalation_mode": "none",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_at": "2026-03-27T10:38:48.000000Z",
            "updated_at": "2026-03-27T10:38:48.000000Z"
        },
        {
            "id": 3,
            "company_id": null,
            "branch_id": null,
            "slug": "apitest-department-zqahcxac",
            "parent_department_id": null,
            "code": "DPT-WQDK",
            "name": "API Test Department YSCC",
            "description": "Created by automated API route report",
            "head_user_id": null,
            "level_no": 1,
            "reports_to_department_id": null,
            "approval_mode": "single",
            "escalation_mode": "none",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_at": "2026-03-27T09:50:26.000000Z",
            "updated_at": "2026-03-27T09:50:26.000000Z"
        },
        {
            "id": 5,
            "company_id": null,
            "branch_id": null,
            "slug": "codex-20260328-071810-department-wbhulb",
            "parent_department_id": null,
            "code": "DPTYJWI9G",
            "name": "Codex Department IHBY",
            "description": "API route test department",
            "head_user_id": null,
            "level_no": 1,
            "reports_to_department_id": null,
            "approval_mode": "hierarchical",
            "escalation_mode": "full_chain",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_at": "2026-03-28T07:18:11.000000Z",
            "updated_at": "2026-03-28T07:18:11.000000Z"
        },
        {
            "id": 6,
            "company_id": null,
            "branch_id": null,
            "slug": "codex-20260328-072413-department-2etthq",
            "parent_department_id": null,
            "code": "DPTE0QKBI",
            "name": "Codex Department OXKK",
            "description": "API route test department",
            "head_user_id": null,
            "level_no": 1,
            "reports_to_department_id": null,
            "approval_mode": "hierarchical",
            "escalation_mode": "full_chain",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_at": "2026-03-28T07:24:14.000000Z",
            "updated_at": "2026-03-28T07:24:14.000000Z"
        },
        {
            "id": 7,
            "company_id": null,
            "branch_id": null,
            "slug": "codex-20260328-072450-department-qr6gxk",
            "parent_department_id": null,
            "code": "DPTYACM5S",
            "name": "Codex Department XS6B",
            "description": "API route test department",
            "head_user_id": null,
            "level_no": 1,
            "reports_to_department_id": null,
            "approval_mode": "hierarchical",
            "escalation_mode": "full_chain",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:51.000000Z"
        }
    ]
}
```

### 40. Show company department

- Route: `GET /api/company/departments/{slug}`
- Tested URI: `/api/company/departments/codex-20260328-072450-department-qr6gxk`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": true,
    "message": "Department retrieved successfully.",
    "data": {
        "id": 7,
        "company_id": null,
        "branch_id": null,
        "slug": "codex-20260328-072450-department-qr6gxk",
        "parent_department_id": null,
        "code": "DPTYACM5S",
        "name": "Codex Department XS6B",
        "description": "API route test department",
        "head_user_id": null,
        "level_no": 1,
        "reports_to_department_id": null,
        "approval_mode": "hierarchical",
        "escalation_mode": "full_chain",
        "can_create_tasks": true,
        "can_receive_tasks": true,
        "is_system_default": false,
        "is_active": true,
        "created_at": "2026-03-28T07:24:51.000000Z",
        "updated_at": "2026-03-28T07:24:51.000000Z"
    }
}
```

### 41. List department features

- Route: `GET /api/company/department-features`
- Status: `500`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "An error occurred while fetching department features."
}
```

### 42. Show department feature

- Route: `GET /api/company/department-features/{slug}`
- Tested URI: `/api/company/department-features/codex-20260328-072450-mapping-l1zh9y`
- Status: `500`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "An error occurred while fetching the department feature."
}
```

### 43. Update department feature

- Route: `PUT /api/company/department-features/{slug}`
- Tested URI: `/api/company/department-features/codex-20260328-072450-mapping-l1zh9y`
- Status: `500`

**Request Payload**

```json
{
    "access_level": "approve",
    "is_enabled": true
}
```

**Response Body**

```json
{
    "success": false,
    "message": "An error occurred while updating the department feature."
}
```

### 44. Delete department feature

- Route: `DELETE /api/company/department-features/{slug}`
- Tested URI: `/api/company/department-features/codex-20260328-072450-mapping-l1zh9y`
- Status: `500`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "An error occurred while removing the department feature."
}
```

### 45. List settings

- Route: `GET /api/company/settings`
- Status: `500`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "An error occurred while fetching settings."
}
```

### 46. Show setting

- Route: `GET /api/company/settings/{slug}`
- Tested URI: `/api/company/settings/`
- Status: `500`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "An error occurred while fetching settings."
}
```

### 47. Update setting

- Route: `PUT /api/company/settings/{slug}`
- Tested URI: `/api/company/settings/`
- Status: `500`

**Request Payload**

```json
{
    "setting_value": "Asia/Kolkata",
    "value_type": "string",
    "is_public": false
}
```

**Response Body**

```json
{
    "success": false,
    "message": "The PUT method is not supported for route api/company/settings. Supported methods: GET, HEAD, POST."
}
```

### 48. Delete setting

- Route: `DELETE /api/company/settings/{slug}`
- Tested URI: `/api/company/settings/`
- Status: `500`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "The DELETE method is not supported for route api/company/settings. Supported methods: GET, HEAD, POST."
}
```

### 49. List roles

- Route: `GET /api/company/roles`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": true,
    "message": "Roles retrieved successfully.",
    "data": [
        {
            "id": 7,
            "name": "Supervisor NTAW",
            "slug": "supervisor-ntaw",
            "description": "Role for route testing",
            "is_active": true,
            "created_at": "2026-03-28 07:24:51",
            "updated_at": "2026-03-28 07:24:51"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 10,
        "total": 1,
        "last_page": 1,
        "from": 1,
        "to": 1
    },
    "links": {
        "first": "http://localhost/api/company/roles?page=1",
        "last": "http://localhost/api/company/roles?page=1",
        "prev": null,
        "next": null
    }
}
```

### 50. Show role

- Route: `GET /api/company/roles/{slug}`
- Tested URI: `/api/company/roles/supervisor-ntaw`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": true,
    "message": "Role retrieved successfully.",
    "data": {
        "id": 7,
        "name": "Supervisor NTAW",
        "slug": "supervisor-ntaw",
        "description": "Role for route testing",
        "is_active": true,
        "created_at": "2026-03-28 07:24:51",
        "updated_at": "2026-03-28 07:24:51"
    }
}
```

### 51. Update role

- Route: `PUT /api/company/roles/{slug}`
- Tested URI: `/api/company/roles/supervisor-ntaw`
- Status: `200`

**Request Payload**

```json
{
    "name": "Supervisor Updated"
}
```

**Response Body**

```json
{
    "success": true,
    "message": "Role updated successfully.",
    "data": {
        "id": 7,
        "name": "Supervisor Updated",
        "slug": "supervisor-updated",
        "description": "Role for route testing",
        "is_active": true,
        "created_at": "2026-03-28 07:24:51",
        "updated_at": "2026-03-28 07:24:55"
    }
}
```

### 52. Delete role

- Route: `DELETE /api/company/roles/{slug}`
- Tested URI: `/api/company/roles/supervisor-ntaw`
- Status: `404`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "Role not found."
}
```

### 53. Create branch user

- Route: `POST /api/company/branch-users`
- Status: `201`

**Request Payload**

```json
{
    "branch_id": 13,
    "dept_id": 7,
    "name": "Codex Route Branch User",
    "email": "route-branch-user-joloqh@example.com",
    "password": "password123",
    "phone": "9111111111",
    "is_active": true
}
```

**Response Body**

```json
{
    "success": true,
    "message": "Employee created successfully.",
    "data": {
        "id": 30,
        "company_id": 20,
        "emp_id": "CMP-XGVWKT-29760460",
        "branch": {
            "id": 13,
            "name": "Main Branch Updated",
            "slug": "main-branch-updated-3"
        },
        "department": {
            "id": 7,
            "name": "Codex Department XS6B",
            "slug": "codex-20260328-072450-department-qr6gxk"
        },
        "profile_image": null,
        "name": "Codex Route Branch User",
        "email": "route-branch-user-joloqh@example.com",
        "phone": "9111111111",
        "slug": "codex-route-branch-user-3",
        "is_dept_admin": null,
        "is_branch_admin": null,
        "is_active": true,
        "is_delete": null,
        "created_by": 20,
        "created_at": "2026-03-28 07:24:55",
        "updated_at": "2026-03-28 07:24:55"
    }
}
```

### 54. List branch users

- Route: `GET /api/company/branch-users`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": true,
    "message": "Employees retrieved successfully.",
    "data": [
        {
            "id": 30,
            "company_id": 20,
            "emp_id": "CMP-XGVWKT-29760460",
            "branch": {
                "id": 13,
                "name": "Main Branch Updated",
                "slug": "main-branch-updated-3"
            },
            "department": {
                "id": 7,
                "name": "Codex Department XS6B",
                "slug": "codex-20260328-072450-department-qr6gxk"
            },
            "profile_image": null,
            "name": "Codex Route Branch User",
            "email": "route-branch-user-joloqh@example.com",
            "phone": "9111111111",
            "slug": "codex-route-branch-user-3",
            "is_dept_admin": false,
            "is_branch_admin": false,
            "is_active": true,
            "is_delete": false,
            "created_by": 20,
            "created_at": "2026-03-28 07:24:55",
            "updated_at": "2026-03-28 07:24:55"
        },
        {
            "id": 29,
            "company_id": 20,
            "emp_id": "EMP-29760459",
            "branch": {
                "id": 13,
                "name": "Main Branch Updated",
                "slug": "main-branch-updated-3"
            },
            "department": {
                "id": 7,
                "name": "Codex Department XS6B",
                "slug": "codex-20260328-072450-department-qr6gxk"
            },
            "profile_image": null,
            "name": "Codex Dept Employee",
            "email": "dept-employee-eqtmxf@example.com",
            "phone": "9876543210",
            "slug": "codex-dept-employee-hz2tu",
            "is_dept_admin": false,
            "is_branch_admin": false,
            "is_active": true,
            "is_delete": false,
            "created_by": 20,
            "created_at": "2026-03-28 07:24:54",
            "updated_at": "2026-03-28 07:24:54"
        },
        {
            "id": 27,
            "company_id": 20,
            "emp_id": "EMP-13427249",
            "branch": {
                "id": 13,
                "name": "Main Branch Updated",
                "slug": "main-branch-updated-3"
            },
            "department": {
                "id": 7,
                "name": "Codex Department XS6B",
                "slug": "codex-20260328-072450-department-qr6gxk"
            },
            "profile_image": null,
            "name": "Codex Employee",
            "email": "employee-vsgabo@example.com",
            "phone": "9876543210",
            "slug": "codex-employee-r9rc8",
            "is_dept_admin": false,
            "is_branch_admin": false,
            "is_active": true,
            "is_delete": false,
            "created_by": 20,
            "created_at": "2026-03-28 07:24:53",
            "updated_at": "2026-03-28 07:24:53"
        },
        {
            "id": 28,
            "company_id": 20,
            "emp_id": "EMP-31794450",
            "branch": {
                "id": 13,
                "name": "Main Branch Updated",
                "slug": "main-branch-updated-3"
            },
            "department": {
                "id": 7,
                "name": "Codex Department XS6B",
                "slug": "codex-20260328-072450-department-qr6gxk"
            },
            "profile_image": null,
            "name": "Codex Branch User Target",
            "email": "branch-user-target-eofkzh@example.com",
            "phone": "9876543210",
            "slug": "codex-branch-user-target-ihzge",
            "is_dept_admin": false,
            "is_branch_admin": false,
            "is_active": true,
            "is_delete": false,
            "created_by": 20,
            "created_at": "2026-03-28 07:24:53",
            "updated_at": "2026-03-28 07:24:53"
        },
        {
            "id": 25,
            "company_id": 20,
            "emp_id": "EMP-80666417",
            "branch": {
                "id": 13,
                "name": "Main Branch Updated",
                "slug": "main-branch-updated-3"
            },
            "department": {
                "id": 7,
                "name": "Codex Department XS6B",
                "slug": "codex-20260328-072450-department-qr6gxk"
            },
            "profile_image": null,
            "name": "Codex Branch Admin",
            "email": "branch-admin-yjmiwj@example.com",
            "phone": "9876543210",
            "slug": "codex-branch-admin-gfunw",
            "is_dept_admin": false,
            "is_branch_admin": true,
            "is_active": true,
            "is_delete": false,
            "created_by": 20,
            "created_at": "2026-03-28 07:24:52",
            "updated_at": "2026-03-28 07:24:52"
        },
        {
            "id": 26,
            "company_id": 20,
            "emp_id": "EMP-62901729",
            "branch": {
                "id": 13,
                "name": "Main Branch Updated",
                "slug": "main-branch-updated-3"
            },
            "department": {
                "id": 7,
                "name": "Codex Department XS6B",
                "slug": "codex-20260328-072450-department-qr6gxk"
            },
            "profile_image": null,
            "name": "Codex Dept Admin",
            "email": "dept-admin-mqdbcp@example.com",
            "phone": "9876543210",
            "slug": "codex-dept-admin-mhzj0",
            "is_dept_admin": true,
            "is_branch_admin": false,
            "is_active": true,
            "is_delete": false,
            "created_by": 20,
            "created_at": "2026-03-28 07:24:52",
            "updated_at": "2026-03-28 07:24:52"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 10,
        "total": 6,
        "last_page": 1,
        "from": 1,
        "to": 6
    },
    "links": {
        "first": "http://localhost/api/company/branch-users?page=1",
        "last": "http://localhost/api/company/branch-users?page=1",
        "prev": null,
        "next": null
    }
}
```

### 55. Show branch user

- Route: `GET /api/company/branch-users/{slug}`
- Tested URI: `/api/company/branch-users/codex-branch-user-target-ihzge`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": true,
    "message": "Employee retrieved successfully.",
    "data": {
        "id": 28,
        "company_id": 20,
        "emp_id": "EMP-31794450",
        "branch": {
            "id": 13,
            "name": "Main Branch Updated",
            "slug": "main-branch-updated-3"
        },
        "department": {
            "id": 7,
            "name": "Codex Department XS6B",
            "slug": "codex-20260328-072450-department-qr6gxk"
        },
        "profile_image": null,
        "name": "Codex Branch User Target",
        "email": "branch-user-target-eofkzh@example.com",
        "phone": "9876543210",
        "slug": "codex-branch-user-target-ihzge",
        "is_dept_admin": false,
        "is_branch_admin": false,
        "is_active": true,
        "is_delete": false,
        "created_by": 20,
        "created_at": "2026-03-28 07:24:53",
        "updated_at": "2026-03-28 07:24:53"
    }
}
```

### 56. Update branch user

- Route: `PUT /api/company/branch-users/{slug}`
- Tested URI: `/api/company/branch-users/codex-branch-user-target-ihzge`
- Status: `200`

**Request Payload**

```json
{
    "name": "Codex Branch User Target Updated"
}
```

**Response Body**

```json
{
    "success": true,
    "message": "Employee updated successfully.",
    "data": {
        "id": 28,
        "company_id": 20,
        "emp_id": "EMP-31794450",
        "branch": {
            "id": 13,
            "name": "Main Branch Updated",
            "slug": "main-branch-updated-3"
        },
        "department": {
            "id": 7,
            "name": "Codex Department XS6B",
            "slug": "codex-20260328-072450-department-qr6gxk"
        },
        "profile_image": null,
        "name": "Codex Branch User Target Updated",
        "email": "branch-user-target-eofkzh@example.com",
        "phone": "9876543210",
        "slug": "codex-branch-user-target-updated-3",
        "is_dept_admin": false,
        "is_branch_admin": false,
        "is_active": true,
        "is_delete": false,
        "created_by": 20,
        "created_at": "2026-03-28 07:24:53",
        "updated_at": "2026-03-28 07:24:55"
    }
}
```

### 57. Change branch user password

- Route: `POST /api/company/branch-users/{slug}/change-password`
- Tested URI: `/api/company/branch-users/codex-branch-user-target-ihzge/change-password`
- Status: `404`

**Request Payload**

```json
{
    "current_password": "password123",
    "new_password": "password456",
    "confirm_password": "password456"
}
```

**Response Body**

```json
{
    "success": false,
    "message": "Employee not found."
}
```

### 58. Delete branch user

- Route: `DELETE /api/company/branch-users/{slug}`
- Tested URI: `/api/company/branch-users/codex-branch-user-target-ihzge`
- Status: `404`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "Employee not found."
}
```

### 59. Branch admin login

- Route: `POST /api/branch-admin/login`
- Status: `200`

**Request Payload**

```json
{
    "email": "branch-admin-yjmiwj@example.com",
    "password": "password123"
}
```

**Response Body**

```json
{
    "status": true,
    "message": "Login successful",
    "token": "29|aaoh3GZMC0PJRodYBoWZlRwIOMY3h3BqGXtZvss5824a4b3e",
    "user": {
        "id": 25,
        "company_id": 20,
        "emp_id": "EMP-80666417",
        "branch_id": 13,
        "dept_id": 7,
        "name": "Codex Branch Admin",
        "email": "branch-admin-yjmiwj@example.com",
        "phone": "9876543210",
        "profile_image": null,
        "is_dept_admin": false,
        "is_branch_admin": true,
        "slug": "codex-branch-admin-gfunw",
        "is_active": true,
        "is_delete": false,
        "created_by": 20,
        "created_at": "2026-03-28T07:24:52.000000Z",
        "updated_at": "2026-03-28T07:24:52.000000Z"
    }
}
```

### 60. Branch admin profile

- Route: `GET /api/branch-admin/profile`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "status": true,
    "message": "Profile fetched successfully",
    "data": {
        "id": 25,
        "company_id": 20,
        "emp_id": "EMP-80666417",
        "branch_id": 13,
        "dept_id": 7,
        "name": "Codex Branch Admin",
        "email": "branch-admin-yjmiwj@example.com",
        "phone": "9876543210",
        "profile_image": null,
        "is_dept_admin": false,
        "is_branch_admin": true,
        "slug": "codex-branch-admin-gfunw",
        "is_active": true,
        "is_delete": false,
        "created_by": 20,
        "created_at": "2026-03-28T07:24:52.000000Z",
        "updated_at": "2026-03-28T07:24:52.000000Z",
        "company": {
            "id": 20,
            "slug": "codex-company-updated-3",
            "code": "CMP-XGVWKT",
            "name": "Codex Company Updated",
            "legal_name": null,
            "email": "company-xqmgtd@example.com",
            "phone": "9000000011",
            "website": "https://example.com",
            "tax_number": null,
            "registration_number": null,
            "currency_code": "INR",
            "timezone": "Asia/Kolkata",
            "address_line1": null,
            "address_line2": null,
            "city": null,
            "state": null,
            "country": null,
            "postal_code": null,
            "address": null,
            "logo_path": null,
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:54.000000Z"
        },
        "branch": {
            "id": 13,
            "company_id": 20,
            "code": "BRHKUWFQ",
            "name": "Main Branch Updated",
            "slug": "main-branch-updated-3",
            "email": "main-branch-kpxncr@example.com",
            "phone": "9000000002",
            "manager_user_id": null,
            "address_line1": "Main branch address",
            "address_line2": null,
            "city": "Kolkata",
            "state": "West Bengal",
            "country": "India",
            "postal_code": "700001",
            "google_map_link": null,
            "is_head_office": true,
            "is_active": true,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:55.000000Z"
        },
        "department": {
            "id": 7,
            "slug": "codex-20260328-072450-department-qr6gxk",
            "parent_department_id": null,
            "reports_to_department_id": null,
            "code": "DPTYACM5S",
            "name": "Codex Department XS6B",
            "description": "API route test department",
            "head_user_id": null,
            "level_no": 1,
            "approval_mode": "hierarchical",
            "escalation_mode": "full_chain",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_by": null,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:51.000000Z"
        }
    }
}
```

### 61. Branch admin departments list

- Route: `GET /api/departments`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": true,
    "message": "Departments retrieved successfully.",
    "data": [
        {
            "id": 2,
            "company_id": null,
            "branch_id": null,
            "slug": "apitest-department-i9vgsyqy",
            "parent_department_id": null,
            "code": "DPT-1FSC",
            "name": "API Test Department HAXQ",
            "description": "Created by automated API route report",
            "head_user_id": null,
            "level_no": 1,
            "reports_to_department_id": null,
            "approval_mode": "single",
            "escalation_mode": "none",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_at": "2026-03-27T09:49:53.000000Z",
            "updated_at": "2026-03-27T09:49:53.000000Z"
        },
        {
            "id": 1,
            "company_id": null,
            "branch_id": null,
            "slug": "apitest-department-x2qpkxh7",
            "parent_department_id": null,
            "code": "DPT-5BKS",
            "name": "API Test Department KYBP",
            "description": "Created by automated API route report",
            "head_user_id": null,
            "level_no": 1,
            "reports_to_department_id": null,
            "approval_mode": "single",
            "escalation_mode": "none",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_at": "2026-03-27T09:48:31.000000Z",
            "updated_at": "2026-03-27T09:48:31.000000Z"
        },
        {
            "id": 4,
            "company_id": null,
            "branch_id": null,
            "slug": "apitest-department-wbplk34t",
            "parent_department_id": null,
            "code": "DPT-TGJF",
            "name": "API Test Department QZWU",
            "description": "Created by automated API route report",
            "head_user_id": null,
            "level_no": 1,
            "reports_to_department_id": null,
            "approval_mode": "single",
            "escalation_mode": "none",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_at": "2026-03-27T10:38:48.000000Z",
            "updated_at": "2026-03-27T10:38:48.000000Z"
        },
        {
            "id": 3,
            "company_id": null,
            "branch_id": null,
            "slug": "apitest-department-zqahcxac",
            "parent_department_id": null,
            "code": "DPT-WQDK",
            "name": "API Test Department YSCC",
            "description": "Created by automated API route report",
            "head_user_id": null,
            "level_no": 1,
            "reports_to_department_id": null,
            "approval_mode": "single",
            "escalation_mode": "none",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_at": "2026-03-27T09:50:26.000000Z",
            "updated_at": "2026-03-27T09:50:26.000000Z"
        },
        {
            "id": 5,
            "company_id": null,
            "branch_id": null,
            "slug": "codex-20260328-071810-department-wbhulb",
            "parent_department_id": null,
            "code": "DPTYJWI9G",
            "name": "Codex Department IHBY",
            "description": "API route test department",
            "head_user_id": null,
            "level_no": 1,
            "reports_to_department_id": null,
            "approval_mode": "hierarchical",
            "escalation_mode": "full_chain",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_at": "2026-03-28T07:18:11.000000Z",
            "updated_at": "2026-03-28T07:18:11.000000Z"
        },
        {
            "id": 6,
            "company_id": null,
            "branch_id": null,
            "slug": "codex-20260328-072413-department-2etthq",
            "parent_department_id": null,
            "code": "DPTE0QKBI",
            "name": "Codex Department OXKK",
            "description": "API route test department",
            "head_user_id": null,
            "level_no": 1,
            "reports_to_department_id": null,
            "approval_mode": "hierarchical",
            "escalation_mode": "full_chain",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_at": "2026-03-28T07:24:14.000000Z",
            "updated_at": "2026-03-28T07:24:14.000000Z"
        },
        {
            "id": 7,
            "company_id": null,
            "branch_id": null,
            "slug": "codex-20260328-072450-department-qr6gxk",
            "parent_department_id": null,
            "code": "DPTYACM5S",
            "name": "Codex Department XS6B",
            "description": "API route test department",
            "head_user_id": null,
            "level_no": 1,
            "reports_to_department_id": null,
            "approval_mode": "hierarchical",
            "escalation_mode": "full_chain",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:51.000000Z"
        }
    ]
}
```

### 62. Create branch employee

- Route: `POST /api/branch/employees`
- Status: `201`

**Request Payload**

```json
{
    "name": "Branch Employee Route",
    "email": "branch-employee-dev3a8@example.com",
    "password": "password123",
    "phone": "9222222222",
    "dept_id": 7
}
```

**Response Body**

```json
{
    "status": true,
    "message": "Employee created successfully",
    "data": {
        "company_id": 20,
        "branch_id": 13,
        "dept_id": 7,
        "emp_id": "CMP-XGVWKT-29760461",
        "name": "Branch Employee Route",
        "email": "branch-employee-dev3a8@example.com",
        "phone": "9222222222",
        "profile_image": null,
        "slug": "branch-employee-route",
        "is_branch_admin": false,
        "is_dept_admin": false,
        "is_active": true,
        "is_delete": false,
        "created_by": 25,
        "updated_at": "2026-03-28T07:24:56.000000Z",
        "created_at": "2026-03-28T07:24:56.000000Z",
        "id": 31
    }
}
```

### 63. List branch employees

- Route: `GET /api/branch/employees`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "status": true,
    "message": "Employees retrieved successfully",
    "data": [
        {
            "id": 31,
            "company_id": 20,
            "emp_id": "CMP-XGVWKT-29760461",
            "branch_id": 13,
            "dept_id": 7,
            "name": "Branch Employee Route",
            "email": "branch-employee-dev3a8@example.com",
            "phone": "9222222222",
            "profile_image": null,
            "is_dept_admin": false,
            "is_branch_admin": false,
            "slug": "branch-employee-route",
            "is_active": true,
            "is_delete": false,
            "created_by": 25,
            "created_at": "2026-03-28T07:24:56.000000Z",
            "updated_at": "2026-03-28T07:24:56.000000Z"
        },
        {
            "id": 30,
            "company_id": 20,
            "emp_id": "CMP-XGVWKT-29760460",
            "branch_id": 13,
            "dept_id": 7,
            "name": "Codex Route Branch User",
            "email": "route-branch-user-joloqh@example.com",
            "phone": "9111111111",
            "profile_image": null,
            "is_dept_admin": false,
            "is_branch_admin": false,
            "slug": "codex-route-branch-user-3",
            "is_active": true,
            "is_delete": false,
            "created_by": 20,
            "created_at": "2026-03-28T07:24:55.000000Z",
            "updated_at": "2026-03-28T07:24:55.000000Z"
        },
        {
            "id": 29,
            "company_id": 20,
            "emp_id": "EMP-29760459",
            "branch_id": 13,
            "dept_id": 7,
            "name": "Codex Dept Employee",
            "email": "dept-employee-eqtmxf@example.com",
            "phone": "9876543210",
            "profile_image": null,
            "is_dept_admin": false,
            "is_branch_admin": false,
            "slug": "codex-dept-employee-hz2tu",
            "is_active": true,
            "is_delete": false,
            "created_by": 20,
            "created_at": "2026-03-28T07:24:54.000000Z",
            "updated_at": "2026-03-28T07:24:54.000000Z"
        },
        {
            "id": 28,
            "company_id": 20,
            "emp_id": "EMP-31794450",
            "branch_id": 13,
            "dept_id": 7,
            "name": "Codex Branch User Target Updated",
            "email": "branch-user-target-eofkzh@example.com",
            "phone": "9876543210",
            "profile_image": null,
            "is_dept_admin": false,
            "is_branch_admin": false,
            "slug": "codex-branch-user-target-updated-3",
            "is_active": true,
            "is_delete": false,
            "created_by": 20,
            "created_at": "2026-03-28T07:24:53.000000Z",
            "updated_at": "2026-03-28T07:24:55.000000Z"
        },
        {
            "id": 27,
            "company_id": 20,
            "emp_id": "EMP-13427249",
            "branch_id": 13,
            "dept_id": 7,
            "name": "Codex Employee",
            "email": "employee-vsgabo@example.com",
            "phone": "9876543210",
            "profile_image": null,
            "is_dept_admin": false,
            "is_branch_admin": false,
            "slug": "codex-employee-r9rc8",
            "is_active": true,
            "is_delete": false,
            "created_by": 20,
            "created_at": "2026-03-28T07:24:53.000000Z",
            "updated_at": "2026-03-28T07:24:53.000000Z"
        },
        {
            "id": 26,
            "company_id": 20,
            "emp_id": "EMP-62901729",
            "branch_id": 13,
            "dept_id": 7,
            "name": "Codex Dept Admin",
            "email": "dept-admin-mqdbcp@example.com",
            "phone": "9876543210",
            "profile_image": null,
            "is_dept_admin": true,
            "is_branch_admin": false,
            "slug": "codex-dept-admin-mhzj0",
            "is_active": true,
            "is_delete": false,
            "created_by": 20,
            "created_at": "2026-03-28T07:24:52.000000Z",
            "updated_at": "2026-03-28T07:24:52.000000Z"
        }
    ]
}
```

### 64. Show branch employee

- Route: `GET /api/branch/employees/{slug}`
- Tested URI: `/api/branch/employees/branch-employee-route`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "status": true,
    "message": "Employee retrieved successfully",
    "data": {
        "id": 31,
        "company_id": 20,
        "emp_id": "CMP-XGVWKT-29760461",
        "branch_id": 13,
        "dept_id": 7,
        "name": "Branch Employee Route",
        "email": "branch-employee-dev3a8@example.com",
        "phone": "9222222222",
        "profile_image": null,
        "is_dept_admin": false,
        "is_branch_admin": false,
        "slug": "branch-employee-route",
        "is_active": true,
        "is_delete": false,
        "created_by": 25,
        "created_at": "2026-03-28T07:24:56.000000Z",
        "updated_at": "2026-03-28T07:24:56.000000Z"
    }
}
```

### 65. Update branch employee (PUT)

- Route: `PUT /api/branch/employees/{slug}`
- Tested URI: `/api/branch/employees/branch-employee-route`
- Status: `200`

**Request Payload**

```json
{
    "name": "Branch Employee Route Updated",
    "dept_id": 7
}
```

**Response Body**

```json
{
    "status": true,
    "message": "Employee updated successfully",
    "data": {
        "id": 31,
        "company_id": 20,
        "emp_id": "CMP-XGVWKT-29760461",
        "branch_id": 13,
        "dept_id": 7,
        "name": "Branch Employee Route Updated",
        "email": "branch-employee-dev3a8@example.com",
        "phone": "9222222222",
        "profile_image": null,
        "is_dept_admin": false,
        "is_branch_admin": false,
        "slug": "branch-employee-route-updated-3",
        "is_active": true,
        "is_delete": false,
        "created_by": 25,
        "created_at": "2026-03-28T07:24:56.000000Z",
        "updated_at": "2026-03-28T07:24:56.000000Z"
    }
}
```

### 66. Update branch employee (POST)

- Route: `POST /api/branch/employees/{slug}`
- Tested URI: `/api/branch/employees/branch-employee-route`
- Status: `404`

**Request Payload**

```json
{
    "name": "Branch Employee Route Post Updated",
    "dept_id": 7
}
```

**Response Body**

```json
{
    "status": false,
    "message": "Employee not found",
    "data": []
}
```

### 67. Delete branch employee

- Route: `DELETE /api/branch/employees/{slug}`
- Tested URI: `/api/branch/employees/branch-employee-route`
- Status: `404`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "status": false,
    "message": "Employee not found",
    "data": []
}
```

### 68. Branch admin logout

- Route: `POST /api/branch-admin/logout`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "status": true,
    "message": "Logout successful"
}
```

### 69. Dept admin login

- Route: `POST /api/dept-admin/login`
- Status: `200`

**Request Payload**

```json
{
    "email": "dept-admin-mqdbcp@example.com",
    "password": "password123"
}
```

**Response Body**

```json
{
    "status": true,
    "message": "Login successful",
    "token": "30|gczhPDECPr96ixXqOzKsMerSpYUQKy6aCdMUnqsIe74e4878",
    "user": {
        "id": 26,
        "company_id": 20,
        "emp_id": "EMP-62901729",
        "branch_id": 13,
        "dept_id": 7,
        "name": "Codex Dept Admin",
        "email": "dept-admin-mqdbcp@example.com",
        "phone": "9876543210",
        "profile_image": null,
        "is_dept_admin": true,
        "is_branch_admin": false,
        "slug": "codex-dept-admin-mhzj0",
        "is_active": true,
        "is_delete": false,
        "created_by": 20,
        "created_at": "2026-03-28T07:24:52.000000Z",
        "updated_at": "2026-03-28T07:24:52.000000Z",
        "company": {
            "id": 20,
            "slug": "codex-company-updated-3",
            "code": "CMP-XGVWKT",
            "name": "Codex Company Updated",
            "legal_name": null,
            "email": "company-xqmgtd@example.com",
            "phone": "9000000011",
            "website": "https://example.com",
            "tax_number": null,
            "registration_number": null,
            "currency_code": "INR",
            "timezone": "Asia/Kolkata",
            "address_line1": null,
            "address_line2": null,
            "city": null,
            "state": null,
            "country": null,
            "postal_code": null,
            "address": null,
            "logo_path": null,
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:54.000000Z"
        },
        "branch": {
            "id": 13,
            "company_id": 20,
            "code": "BRHKUWFQ",
            "name": "Main Branch Updated",
            "slug": "main-branch-updated-3",
            "email": "main-branch-kpxncr@example.com",
            "phone": "9000000002",
            "manager_user_id": null,
            "address_line1": "Main branch address",
            "address_line2": null,
            "city": "Kolkata",
            "state": "West Bengal",
            "country": "India",
            "postal_code": "700001",
            "google_map_link": null,
            "is_head_office": true,
            "is_active": true,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:55.000000Z"
        },
        "department": {
            "id": 7,
            "slug": "codex-20260328-072450-department-qr6gxk",
            "parent_department_id": null,
            "reports_to_department_id": null,
            "code": "DPTYACM5S",
            "name": "Codex Department XS6B",
            "description": "API route test department",
            "head_user_id": null,
            "level_no": 1,
            "approval_mode": "hierarchical",
            "escalation_mode": "full_chain",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_by": null,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:51.000000Z"
        }
    }
}
```

### 70. Dept admin profile

- Route: `GET /api/dept-admin/profile`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "status": true,
    "message": "Profile fetched successfully",
    "data": {
        "id": 26,
        "company_id": 20,
        "emp_id": "EMP-62901729",
        "branch_id": 13,
        "dept_id": 7,
        "name": "Codex Dept Admin",
        "email": "dept-admin-mqdbcp@example.com",
        "phone": "9876543210",
        "profile_image": null,
        "is_dept_admin": true,
        "is_branch_admin": false,
        "slug": "codex-dept-admin-mhzj0",
        "is_active": true,
        "is_delete": false,
        "created_by": 20,
        "created_at": "2026-03-28T07:24:52.000000Z",
        "updated_at": "2026-03-28T07:24:52.000000Z",
        "company": {
            "id": 20,
            "slug": "codex-company-updated-3",
            "code": "CMP-XGVWKT",
            "name": "Codex Company Updated",
            "legal_name": null,
            "email": "company-xqmgtd@example.com",
            "phone": "9000000011",
            "website": "https://example.com",
            "tax_number": null,
            "registration_number": null,
            "currency_code": "INR",
            "timezone": "Asia/Kolkata",
            "address_line1": null,
            "address_line2": null,
            "city": null,
            "state": null,
            "country": null,
            "postal_code": null,
            "address": null,
            "logo_path": null,
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:54.000000Z"
        },
        "branch": {
            "id": 13,
            "company_id": 20,
            "code": "BRHKUWFQ",
            "name": "Main Branch Updated",
            "slug": "main-branch-updated-3",
            "email": "main-branch-kpxncr@example.com",
            "phone": "9000000002",
            "manager_user_id": null,
            "address_line1": "Main branch address",
            "address_line2": null,
            "city": "Kolkata",
            "state": "West Bengal",
            "country": "India",
            "postal_code": "700001",
            "google_map_link": null,
            "is_head_office": true,
            "is_active": true,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:55.000000Z"
        },
        "department": {
            "id": 7,
            "slug": "codex-20260328-072450-department-qr6gxk",
            "parent_department_id": null,
            "reports_to_department_id": null,
            "code": "DPTYACM5S",
            "name": "Codex Department XS6B",
            "description": "API route test department",
            "head_user_id": null,
            "level_no": 1,
            "approval_mode": "hierarchical",
            "escalation_mode": "full_chain",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_by": null,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:51.000000Z"
        }
    }
}
```

### 71. Create dept employee

- Route: `POST /api/dept/employees`
- Status: `201`

**Request Payload**

```json
{
    "name": "Dept Employee Route",
    "email": "dept-employee-route-rslchq@example.com",
    "password": "password123",
    "phone": "9333333333"
}
```

**Response Body**

```json
{
    "status": true,
    "message": "Employee created successfully",
    "data": {
        "company_id": 20,
        "branch_id": 13,
        "dept_id": 7,
        "emp_id": "CMP-XGVWKT-29760462",
        "name": "Dept Employee Route",
        "email": "dept-employee-route-rslchq@example.com",
        "phone": "9333333333",
        "profile_image": null,
        "slug": "dept-employee-route",
        "is_branch_admin": false,
        "is_dept_admin": false,
        "is_active": true,
        "is_delete": false,
        "created_by": 26,
        "updated_at": "2026-03-28T07:24:58.000000Z",
        "created_at": "2026-03-28T07:24:58.000000Z",
        "id": 32
    }
}
```

### 72. List dept employees

- Route: `GET /api/dept/employees`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "status": true,
    "message": "Employees retrieved successfully",
    "data": [
        {
            "id": 32,
            "company_id": 20,
            "emp_id": "CMP-XGVWKT-29760462",
            "branch_id": 13,
            "dept_id": 7,
            "name": "Dept Employee Route",
            "email": "dept-employee-route-rslchq@example.com",
            "phone": "9333333333",
            "profile_image": null,
            "is_dept_admin": false,
            "is_branch_admin": false,
            "slug": "dept-employee-route",
            "is_active": true,
            "is_delete": false,
            "created_by": 26,
            "created_at": "2026-03-28T07:24:58.000000Z",
            "updated_at": "2026-03-28T07:24:58.000000Z"
        },
        {
            "id": 31,
            "company_id": 20,
            "emp_id": "CMP-XGVWKT-29760461",
            "branch_id": 13,
            "dept_id": 7,
            "name": "Branch Employee Route Updated",
            "email": "branch-employee-dev3a8@example.com",
            "phone": "9222222222",
            "profile_image": null,
            "is_dept_admin": false,
            "is_branch_admin": false,
            "slug": "branch-employee-route-updated-3",
            "is_active": true,
            "is_delete": false,
            "created_by": 25,
            "created_at": "2026-03-28T07:24:56.000000Z",
            "updated_at": "2026-03-28T07:24:56.000000Z"
        },
        {
            "id": 30,
            "company_id": 20,
            "emp_id": "CMP-XGVWKT-29760460",
            "branch_id": 13,
            "dept_id": 7,
            "name": "Codex Route Branch User",
            "email": "route-branch-user-joloqh@example.com",
            "phone": "9111111111",
            "profile_image": null,
            "is_dept_admin": false,
            "is_branch_admin": false,
            "slug": "codex-route-branch-user-3",
            "is_active": true,
            "is_delete": false,
            "created_by": 20,
            "created_at": "2026-03-28T07:24:55.000000Z",
            "updated_at": "2026-03-28T07:24:55.000000Z"
        },
        {
            "id": 29,
            "company_id": 20,
            "emp_id": "EMP-29760459",
            "branch_id": 13,
            "dept_id": 7,
            "name": "Codex Dept Employee",
            "email": "dept-employee-eqtmxf@example.com",
            "phone": "9876543210",
            "profile_image": null,
            "is_dept_admin": false,
            "is_branch_admin": false,
            "slug": "codex-dept-employee-hz2tu",
            "is_active": true,
            "is_delete": false,
            "created_by": 20,
            "created_at": "2026-03-28T07:24:54.000000Z",
            "updated_at": "2026-03-28T07:24:54.000000Z"
        },
        {
            "id": 28,
            "company_id": 20,
            "emp_id": "EMP-31794450",
            "branch_id": 13,
            "dept_id": 7,
            "name": "Codex Branch User Target Updated",
            "email": "branch-user-target-eofkzh@example.com",
            "phone": "9876543210",
            "profile_image": null,
            "is_dept_admin": false,
            "is_branch_admin": false,
            "slug": "codex-branch-user-target-updated-3",
            "is_active": true,
            "is_delete": false,
            "created_by": 20,
            "created_at": "2026-03-28T07:24:53.000000Z",
            "updated_at": "2026-03-28T07:24:55.000000Z"
        },
        {
            "id": 27,
            "company_id": 20,
            "emp_id": "EMP-13427249",
            "branch_id": 13,
            "dept_id": 7,
            "name": "Codex Employee",
            "email": "employee-vsgabo@example.com",
            "phone": "9876543210",
            "profile_image": null,
            "is_dept_admin": false,
            "is_branch_admin": false,
            "slug": "codex-employee-r9rc8",
            "is_active": true,
            "is_delete": false,
            "created_by": 20,
            "created_at": "2026-03-28T07:24:53.000000Z",
            "updated_at": "2026-03-28T07:24:53.000000Z"
        }
    ]
}
```

### 73. Show dept employee

- Route: `GET /api/dept/employees/{slug}`
- Tested URI: `/api/dept/employees/dept-employee-route`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "status": true,
    "message": "Employee retrieved successfully",
    "data": {
        "id": 32,
        "company_id": 20,
        "emp_id": "CMP-XGVWKT-29760462",
        "branch_id": 13,
        "dept_id": 7,
        "name": "Dept Employee Route",
        "email": "dept-employee-route-rslchq@example.com",
        "phone": "9333333333",
        "profile_image": null,
        "is_dept_admin": false,
        "is_branch_admin": false,
        "slug": "dept-employee-route",
        "is_active": true,
        "is_delete": false,
        "created_by": 26,
        "created_at": "2026-03-28T07:24:58.000000Z",
        "updated_at": "2026-03-28T07:24:58.000000Z"
    }
}
```

### 74. Update dept employee

- Route: `PUT /api/dept/employees/{slug}`
- Tested URI: `/api/dept/employees/dept-employee-route`
- Status: `200`

**Request Payload**

```json
{
    "name": "Dept Employee Route Updated"
}
```

**Response Body**

```json
{
    "status": true,
    "message": "Employee updated successfully",
    "data": {
        "id": 32,
        "company_id": 20,
        "emp_id": "CMP-XGVWKT-29760462",
        "branch_id": 13,
        "dept_id": 7,
        "name": "Dept Employee Route Updated",
        "email": "dept-employee-route-rslchq@example.com",
        "phone": "9333333333",
        "profile_image": null,
        "is_dept_admin": false,
        "is_branch_admin": false,
        "slug": "dept-employee-route-updated-3",
        "is_active": true,
        "is_delete": false,
        "created_by": 26,
        "created_at": "2026-03-28T07:24:58.000000Z",
        "updated_at": "2026-03-28T07:24:58.000000Z"
    }
}
```

### 75. Delete dept employee

- Route: `DELETE /api/dept/employees/{slug}`
- Tested URI: `/api/dept/employees/dept-employee-route`
- Status: `404`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "status": false,
    "message": "Employee not found",
    "data": []
}
```

### 76. Dept admin logout

- Route: `POST /api/dept-admin/logout`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "status": true,
    "message": "Logout successful",
    "data": []
}
```

### 77. Dept employee login

- Route: `POST /api/dept-employee/login`
- Status: `200`

**Request Payload**

```json
{
    "email": "dept-employee-eqtmxf@example.com",
    "password": "password123"
}
```

**Response Body**

```json
{
    "status": true,
    "message": "Login successful",
    "token": "31|aBvgXckNH7VwHUwFrU78W8J4P2e461kn8Nf4WwYR911a7c24",
    "user": {
        "id": 29,
        "company_id": 20,
        "emp_id": "EMP-29760459",
        "branch_id": 13,
        "dept_id": 7,
        "name": "Codex Dept Employee",
        "email": "dept-employee-eqtmxf@example.com",
        "phone": "9876543210",
        "profile_image": null,
        "is_dept_admin": false,
        "is_branch_admin": false,
        "slug": "codex-dept-employee-hz2tu",
        "is_active": true,
        "is_delete": false,
        "created_by": 20,
        "created_at": "2026-03-28T07:24:54.000000Z",
        "updated_at": "2026-03-28T07:24:54.000000Z",
        "company": {
            "id": 20,
            "slug": "codex-company-updated-3",
            "code": "CMP-XGVWKT",
            "name": "Codex Company Updated",
            "legal_name": null,
            "email": "company-xqmgtd@example.com",
            "phone": "9000000011",
            "website": "https://example.com",
            "tax_number": null,
            "registration_number": null,
            "currency_code": "INR",
            "timezone": "Asia/Kolkata",
            "address_line1": null,
            "address_line2": null,
            "city": null,
            "state": null,
            "country": null,
            "postal_code": null,
            "address": null,
            "logo_path": null,
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:54.000000Z"
        },
        "branch": {
            "id": 13,
            "company_id": 20,
            "code": "BRHKUWFQ",
            "name": "Main Branch Updated",
            "slug": "main-branch-updated-3",
            "email": "main-branch-kpxncr@example.com",
            "phone": "9000000002",
            "manager_user_id": null,
            "address_line1": "Main branch address",
            "address_line2": null,
            "city": "Kolkata",
            "state": "West Bengal",
            "country": "India",
            "postal_code": "700001",
            "google_map_link": null,
            "is_head_office": true,
            "is_active": true,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:55.000000Z"
        },
        "department": {
            "id": 7,
            "slug": "codex-20260328-072450-department-qr6gxk",
            "parent_department_id": null,
            "reports_to_department_id": null,
            "code": "DPTYACM5S",
            "name": "Codex Department XS6B",
            "description": "API route test department",
            "head_user_id": null,
            "level_no": 1,
            "approval_mode": "hierarchical",
            "escalation_mode": "full_chain",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_by": null,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:51.000000Z"
        }
    }
}
```

### 78. Dept employee profile

- Route: `GET /api/dept-employee/profile`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "status": true,
    "message": "Profile fetched successfully",
    "data": {
        "id": 26,
        "company_id": 20,
        "emp_id": "EMP-62901729",
        "branch_id": 13,
        "dept_id": 7,
        "name": "Codex Dept Admin",
        "email": "dept-admin-mqdbcp@example.com",
        "phone": "9876543210",
        "profile_image": null,
        "is_dept_admin": true,
        "is_branch_admin": false,
        "slug": "codex-dept-admin-mhzj0",
        "is_active": true,
        "is_delete": false,
        "created_by": 20,
        "created_at": "2026-03-28T07:24:52.000000Z",
        "updated_at": "2026-03-28T07:24:52.000000Z",
        "company": {
            "id": 20,
            "slug": "codex-company-updated-3",
            "code": "CMP-XGVWKT",
            "name": "Codex Company Updated",
            "legal_name": null,
            "email": "company-xqmgtd@example.com",
            "phone": "9000000011",
            "website": "https://example.com",
            "tax_number": null,
            "registration_number": null,
            "currency_code": "INR",
            "timezone": "Asia/Kolkata",
            "address_line1": null,
            "address_line2": null,
            "city": null,
            "state": null,
            "country": null,
            "postal_code": null,
            "address": null,
            "logo_path": null,
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:54.000000Z"
        },
        "branch": {
            "id": 13,
            "company_id": 20,
            "code": "BRHKUWFQ",
            "name": "Main Branch Updated",
            "slug": "main-branch-updated-3",
            "email": "main-branch-kpxncr@example.com",
            "phone": "9000000002",
            "manager_user_id": null,
            "address_line1": "Main branch address",
            "address_line2": null,
            "city": "Kolkata",
            "state": "West Bengal",
            "country": "India",
            "postal_code": "700001",
            "google_map_link": null,
            "is_head_office": true,
            "is_active": true,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:55.000000Z"
        },
        "department": {
            "id": 7,
            "slug": "codex-20260328-072450-department-qr6gxk",
            "parent_department_id": null,
            "reports_to_department_id": null,
            "code": "DPTYACM5S",
            "name": "Codex Department XS6B",
            "description": "API route test department",
            "head_user_id": null,
            "level_no": 1,
            "approval_mode": "hierarchical",
            "escalation_mode": "full_chain",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_by": null,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:51.000000Z"
        }
    }
}
```

### 79. Dept employee change password

- Route: `POST /api/dept-employee/change-password`
- Status: `200`

**Request Payload**

```json
{
    "current_password": "password123",
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
}
```

**Response Body**

```json
{
    "status": true,
    "message": "Password changed successfully"
}
```

### 80. Dept employee re-login after password change

- Route: `POST /api/dept-employee/login`
- Status: `401`

**Request Payload**

```json
{
    "email": "dept-employee-eqtmxf@example.com",
    "password": "newpassword123"
}
```

**Response Body**

```json
{
    "status": false,
    "message": "Invalid credentials",
    "data": []
}
```

### 81. Dept employee logout

- Route: `POST /api/dept-employee/logout`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "status": true,
    "message": "Logout successful"
}
```

### 82. Delete feature

- Route: `DELETE /api/company/features/{slug}`
- Tested URI: `/api/company/features/codex-20260328-072450-feature-3hp25s`
- Status: `404`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "Feature not found."
}
```

### 83. Employee login

- Route: `POST /api/employee/login`
- Status: `200`

**Request Payload**

```json
{
    "email": "employee-vsgabo@example.com",
    "password": "password123"
}
```

**Response Body**

```json
{
    "status": true,
    "message": "Login successful",
    "token": "32|R3tzebUDothGjHt5JPfFsT8h10jGPziOnECtzQjxdd4a5220",
    "user": {
        "id": 27,
        "company_id": 20,
        "emp_id": "EMP-13427249",
        "branch_id": 13,
        "dept_id": 7,
        "name": "Codex Employee",
        "email": "employee-vsgabo@example.com",
        "phone": "9876543210",
        "profile_image": null,
        "is_dept_admin": false,
        "is_branch_admin": false,
        "slug": "codex-employee-r9rc8",
        "is_active": true,
        "is_delete": false,
        "created_by": 20,
        "created_at": "2026-03-28T07:24:53.000000Z",
        "updated_at": "2026-03-28T07:24:53.000000Z",
        "company": {
            "id": 20,
            "slug": "codex-company-updated-3",
            "code": "CMP-XGVWKT",
            "name": "Codex Company Updated",
            "legal_name": null,
            "email": "company-xqmgtd@example.com",
            "phone": "9000000011",
            "website": "https://example.com",
            "tax_number": null,
            "registration_number": null,
            "currency_code": "INR",
            "timezone": "Asia/Kolkata",
            "address_line1": null,
            "address_line2": null,
            "city": null,
            "state": null,
            "country": null,
            "postal_code": null,
            "address": null,
            "logo_path": null,
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:54.000000Z"
        },
        "branch": {
            "id": 13,
            "company_id": 20,
            "code": "BRHKUWFQ",
            "name": "Main Branch Updated",
            "slug": "main-branch-updated-3",
            "email": "main-branch-kpxncr@example.com",
            "phone": "9000000002",
            "manager_user_id": null,
            "address_line1": "Main branch address",
            "address_line2": null,
            "city": "Kolkata",
            "state": "West Bengal",
            "country": "India",
            "postal_code": "700001",
            "google_map_link": null,
            "is_head_office": true,
            "is_active": true,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:55.000000Z"
        },
        "department": {
            "id": 7,
            "slug": "codex-20260328-072450-department-qr6gxk",
            "parent_department_id": null,
            "reports_to_department_id": null,
            "code": "DPTYACM5S",
            "name": "Codex Department XS6B",
            "description": "API route test department",
            "head_user_id": null,
            "level_no": 1,
            "approval_mode": "hierarchical",
            "escalation_mode": "full_chain",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_by": null,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:51.000000Z"
        }
    }
}
```

### 84. Employee profile

- Route: `GET /api/employee/profile`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "status": true,
    "message": "Profile fetched successfully",
    "data": {
        "id": 27,
        "company_id": 20,
        "emp_id": "EMP-13427249",
        "branch_id": 13,
        "dept_id": 7,
        "name": "Codex Employee",
        "email": "employee-vsgabo@example.com",
        "phone": "9876543210",
        "profile_image": null,
        "is_dept_admin": false,
        "is_branch_admin": false,
        "slug": "codex-employee-r9rc8",
        "is_active": true,
        "is_delete": false,
        "created_by": 20,
        "created_at": "2026-03-28T07:24:53.000000Z",
        "updated_at": "2026-03-28T07:24:53.000000Z",
        "company": {
            "id": 20,
            "slug": "codex-company-updated-3",
            "code": "CMP-XGVWKT",
            "name": "Codex Company Updated",
            "legal_name": null,
            "email": "company-xqmgtd@example.com",
            "phone": "9000000011",
            "website": "https://example.com",
            "tax_number": null,
            "registration_number": null,
            "currency_code": "INR",
            "timezone": "Asia/Kolkata",
            "address_line1": null,
            "address_line2": null,
            "city": null,
            "state": null,
            "country": null,
            "postal_code": null,
            "address": null,
            "logo_path": null,
            "is_active": true,
            "is_delete": false,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:54.000000Z"
        },
        "branch": {
            "id": 13,
            "company_id": 20,
            "code": "BRHKUWFQ",
            "name": "Main Branch Updated",
            "slug": "main-branch-updated-3",
            "email": "main-branch-kpxncr@example.com",
            "phone": "9000000002",
            "manager_user_id": null,
            "address_line1": "Main branch address",
            "address_line2": null,
            "city": "Kolkata",
            "state": "West Bengal",
            "country": "India",
            "postal_code": "700001",
            "google_map_link": null,
            "is_head_office": true,
            "is_active": true,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:55.000000Z"
        },
        "department": {
            "id": 7,
            "slug": "codex-20260328-072450-department-qr6gxk",
            "parent_department_id": null,
            "reports_to_department_id": null,
            "code": "DPTYACM5S",
            "name": "Codex Department XS6B",
            "description": "API route test department",
            "head_user_id": null,
            "level_no": 1,
            "approval_mode": "hierarchical",
            "escalation_mode": "full_chain",
            "can_create_tasks": true,
            "can_receive_tasks": true,
            "is_system_default": false,
            "is_active": true,
            "created_by": null,
            "created_at": "2026-03-28T07:24:51.000000Z",
            "updated_at": "2026-03-28T07:24:51.000000Z"
        }
    }
}
```

### 85. Employee attendance index

- Route: `GET /api/employee/attendance`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "status": true,
    "message": "Attendance fetched successfully",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 6,
                "company_id": 20,
                "branch_id": 13,
                "dept_id": 7,
                "branch_user_id": 27,
                "login_time": "2026-03-28T07:25:01.000000Z",
                "logout_time": null,
                "device_info": "Codex API Route Tester",
                "ip_address": "127.0.0.1",
                "created_at": "2026-03-28T07:25:01.000000Z",
                "updated_at": "2026-03-28T07:25:01.000000Z",
                "work_duration_minutes": null
            }
        ],
        "first_page_url": "http://localhost/api/employee/attendance?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://localhost/api/employee/attendance?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost/api/employee/attendance?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "http://localhost/api/employee/attendance",
        "per_page": 10,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

### 86. Employee attendance show

- Route: `GET /api/employee/attendance/{id}`
- Tested URI: `/api/employee/attendance/6`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "status": true,
    "message": "Attendance record fetched successfully",
    "data": {
        "id": 6,
        "company_id": 20,
        "branch_id": 13,
        "dept_id": 7,
        "branch_user_id": 27,
        "login_time": "2026-03-28T07:25:01.000000Z",
        "logout_time": null,
        "device_info": "Codex API Route Tester",
        "ip_address": "127.0.0.1",
        "created_at": "2026-03-28T07:25:01.000000Z",
        "updated_at": "2026-03-28T07:25:01.000000Z",
        "work_duration_minutes": null
    }
}
```

### 87. Employee logout

- Route: `POST /api/employee/logout`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "status": true,
    "message": "Logout successful"
}
```

### 88. Company logout

- Route: `POST /api/company/logout`
- Status: `200`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": true,
    "message": "Logged out successfully."
}
```

### 89. Admin logout

- Route: `POST /api/admin/logout`
- Status: `401`

**Request Payload**

```json
[]
```

**Response Body**

```json
{
    "success": false,
    "message": "Unauthenticated. Please login to access this resource."
}
```

