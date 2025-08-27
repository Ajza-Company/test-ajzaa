# Custom Categories API Documentation

## Overview
This document describes the API endpoints for managing custom categories for companies. Each company can have their own custom categories that are separate from the system categories.

## Base URL
```
/api/v1
```

## Authentication
All endpoints require authentication via Bearer token:
```
Authorization: Bearer {your_token}
```

## Endpoints

### 1. Get Custom Categories for Company
**GET** `/companies/{company_id}/custom-categories`

Returns all custom categories for a specific company.

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "سيارات فاخرة",
            "description": "وصف القسم",
            "image": "image_url",
            "is_active": true,
            "sort_order": 0,
            "category_type": "custom",
            "company_id": 1,
            "company": {
                "id": 1,
                "name": "اسم الشركة"
            },
            "products_count": 5,
            "created_at": "2025-08-25T15:00:00.000000Z",
            "updated_at": "2025-08-25T15:00:00.000000Z"
        }
    ]
}
```

### 2. Create Custom Category
**POST** `/companies/{company_id}/custom-categories`

Creates a new custom category for a company.

**Request Body:**
```json
{
    "name": "سيارات فاخرة",
    "description": "وصف القسم",
    "image": "image_url",
    "is_active": true,
    "sort_order": 0
}
```

**Response:**
```json
{
    "success": true,
    "message": "Custom category created successfully",
    "data": {
        "id": 1,
        "name": "سيارات فاخرة",
        "description": "وصف القسم",
        "image": "image_url",
        "is_active": true,
        "sort_order": 0,
        "category_type": "custom",
        "company_id": 1,
        "company": {
            "id": 1,
            "name": "اسم الشركة"
        },
        "products_count": 0,
        "created_at": "2025-08-25T15:00:00.000000Z",
        "updated_at": "2025-08-25T15:00:00.000000Z"
    }
}
```

### 3. Update Custom Category
**PUT** `/companies/custom-categories/{category_id}`

Updates an existing custom category.

**Request Body:**
```json
{
    "name": "الاسم الجديد",
    "description": "الوصف الجديد",
    "image": "image_url",
    "is_active": false,
    "sort_order": 1
}
```

**Response:**
```json
{
    "success": true,
    "message": "Custom category updated successfully",
    "data": {
        "id": 1,
        "name": "الاسم الجديد",
        "description": "الوصف الجديد",
        "image": "image_url",
        "is_active": false,
        "sort_order": 1,
        "category_type": "custom",
        "company_id": 1,
        "company": {
            "id": 1,
            "name": "اسم الشركة"
        },
        "products_count": 5,
        "created_at": "2025-08-25T15:00:00.000000Z",
        "updated_at": "2025-08-25T15:30:00.000000Z"
    }
}
```

### 4. Delete Custom Category
**DELETE** `/companies/custom-categories/{category_id}`

Deletes a custom category. Cannot delete if it has products.

**Response:**
```json
{
    "success": true,
    "message": "Custom category deleted successfully"
}
```

### 5. Get Company Products
**GET** `/companies/{company_id}/products`

Returns all products for a company (both system and custom categories).

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "اسم المنتج",
            "category": {
                "id": 1,
                "name": "اسم القسم",
                "category_type": "custom",
                "company": {
                    "id": 1,
                    "name": "اسم الشركة"
                }
            }
        }
    ]
}
```

### 6. Get Products by Category
**GET** `/companies/{company_id}/categories/{category_id}/products`

Returns products for a specific category within a company.

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "اسم المنتج",
            "category": {
                "id": 1,
                "name": "اسم القسم",
                "category_type": "custom",
                "company": {
                    "id": 1,
                    "name": "اسم الشركة"
                }
            }
        }
    ]
}
```

### 7. Get Products Count by Category
**GET** `/companies/{company_id}/products/count`

Returns count of products for each category (system + custom) for a company.

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "category_id": 1,
            "category_name": "اسم القسم",
            "category_type": "system",
            "products_count": 15
        },
        {
            "category_id": 5,
            "category_name": "سيارات فاخرة",
            "category_type": "custom",
            "products_count": 8
        }
    ]
}
```

### 8. Search Products
**GET** `/companies/{company_id}/products/search?query=اسم المنتج`

Search products by name within company categories.

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "اسم المنتج",
            "category": {
                "id": 1,
                "name": "اسم القسم",
                "category_type": "custom"
            }
        }
    ]
}
```

### 9. Get Products Statistics
**GET** `/companies/{company_id}/products/statistics`

Returns statistics about products in company categories.

**Response:**
```json
{
    "success": true,
    "data": {
        "total_products": 23,
        "system_category_products": 15,
        "custom_category_products": 8,
        "company_id": 1
    }
}
```

## Bulk Operations

### 10. Bulk Update Categories Order
**POST** `/companies/{company_id}/custom-categories/bulk/order`

Update sort order for multiple custom categories.

**Request Body:**
```json
{
    "categories": [
        {
            "id": 1,
            "sort_order": 0
        },
        {
            "id": 2,
            "sort_order": 1
        }
    ]
}
```

**Response:**
```json
{
    "success": true,
    "message": "Categories order updated successfully",
    "data": [
        {
            "id": 1,
            "name": "سيارات فاخرة",
            "sort_order": 0
        },
        {
            "id": 2,
            "name": "قطع غيار",
            "sort_order": 1
        }
    ]
}
```

### 11. Bulk Update Categories Status
**POST** `/companies/{company_id}/custom-categories/bulk/status`

Activate/deactivate multiple custom categories.

**Request Body:**
```json
{
    "category_ids": [1, 2, 3],
    "is_active": false
}
```

**Response:**
```json
{
    "success": true,
    "message": "3 categories updated successfully",
    "data": {
        "updated_count": 3,
        "is_active": false
    }
}
```

### 12. Get Categories Statistics
**GET** `/companies/{company_id}/custom-categories/statistics`

Returns statistics about custom categories for a company.

**Response:**
```json
{
    "success": true,
    "data": {
        "total_categories": 5,
        "active_categories": 4,
        "inactive_categories": 1,
        "categories_with_products": 3,
        "empty_categories": 2
    }
}
```

### 13. Search Categories
**GET** `/companies/{company_id}/custom-categories/search?query=سيارات`

Search custom categories by name.

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "سيارات فاخرة",
            "category_type": "custom"
        }
    ]
}
```

## System Categories Endpoint

### Get System Categories
**GET** `/general/categories`

Returns only system categories (not custom categories).

**Response:**
```json
[
    {
        "id": 1,
        "name": "اسم القسم",
        "image": "image_url",
        "is_active": true,
        "sort_order": 0
    }
]
```

## Permissions

### Required Permissions
- **System Admin**: Can manage custom categories for any company
- **Company Admin**: Can manage custom categories for their own company only

### Permission Checks
- `manage_categories` - Full access to all categories
- `manage_company_categories` - Access to company-specific categories

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "name": ["Category name is required"]
    }
}
```

### Unauthorized (403)
```json
{
    "success": false,
    "message": "Unauthorized"
}
```

### Bad Request (400)
```json
{
    "success": false,
    "message": "Category is not a custom category"
}
```

### Cannot Delete (400)
```json
{
    "success": false,
    "message": "Cannot delete category with existing products"
}
```

## Notes

1. **Custom Categories** are company-specific and do not appear in the general categories endpoint
2. **System Categories** are global and available to all companies
3. **Products** can belong to either system or custom categories
4. **Custom Categories** cannot be deleted if they contain products
5. **Category Type** is automatically set to 'custom' when creating via custom categories endpoint
6. **Company ID** is automatically set when creating custom categories

## Testing with Postman

### Environment Variables
- `base_url`: `http://your-domain.com/api/v1`
- `token`: Your authentication token
- `company_id`: ID of the company you want to test with

### Headers
```
Authorization: Bearer {{token}}
Accept: application/json
Content-Type: application/json
```

### Example Collection
Import this collection to test all endpoints:

```json
{
    "info": {
        "name": "Custom Categories API",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "item": [
        {
            "name": "Get Custom Categories",
            "request": {
                "method": "GET",
                "header": [
                    {
                        "key": "Authorization",
                        "value": "Bearer {{token}}"
                    }
                ],
                "url": {
                    "raw": "{{base_url}}/companies/{{company_id}}/custom-categories"
                }
            }
        },
        {
            "name": "Create Custom Category",
            "request": {
                "method": "POST",
                "header": [
                    {
                        "key": "Authorization",
                        "value": "Bearer {{token}}"
                    },
                    {
                        "key": "Content-Type",
                        "value": "application/json"
                    }
                ],
                "body": {
                    "mode": "raw",
                    "raw": "{\n    \"name\": \"سيارات فاخرة\",\n    \"description\": \"وصف القسم\",\n    \"image\": \"image_url\",\n    \"is_active\": true,\n    \"sort_order\": 0\n}"
                },
                "url": {
                    "raw": "{{base_url}}/companies/{{company_id}}/custom-categories"
                }
            }
        }
    ]
}
```
