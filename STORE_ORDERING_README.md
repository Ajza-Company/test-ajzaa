# Store Manual Ordering & Full Update System

## Overview
تم إضافة نظام الترتيب اليدوي للمتاجر من لوحة التحكم (Admin Dashboard) مع إمكانية تعديل جميع تفاصيل المتجر، بحيث يظهر هذا الترتيب في تطبيق الموبايل، مشابه لما تم عمله في الأقسام.

## Features
- ✅ إضافة عمود `sort_order` إلى جدول المتاجر
- ✅ ترتيب تلقائي للمتاجر الجديدة
- ✅ إمكانية تغيير الترتيب من لوحة التحكم
- ✅ **إمكانية تعديل جميع تفاصيل المتجر من لوحة التحكم**
- ✅ ظهور الترتيب في جميع APIs (Frontend, Supplier, Admin)
- ✅ الحفاظ على جميع APIs الموبايل

## Database Changes
تم إضافة عمود `sort_order` إلى جدول `stores`:
```sql
ALTER TABLE stores ADD COLUMN sort_order INT DEFAULT 0 AFTER phone_number;
```

## API Endpoints

### Admin Dashboard
```
GET    /api/admin/stores                    - عرض المتاجر مع الترتيب
POST   /api/admin/store/update/{id}        - تحديث المتجر بكامل التفاصيل
GET    /api/admin/store/show/{id}          - عرض المتجر مع التفاصيل
PUT    /api/admin/stores/update-order      - تحديث ترتيب المتاجر
POST   /api/admin/store/{id}/active       - تفعيل/إلغاء تفعيل المتجر
```

### Frontend (Mobile App)
```
GET    /api/v1/stores                      - عرض المتاجر مع الترتيب (للمستخدمين)
```

### Supplier Dashboard
```
GET    /api/v1/supplier/stores             - عرض متاجر المورد مع الترتيب
POST   /api/v1/supplier/stores/{id}/update - تحديث متجر المورد
```

## Store Update Fields (Admin)

### البيانات الأساسية
```php
'company_id' => 'sometimes|exists:companies,id',        // الشركة
'area_id' => 'sometimes|exists:areas,id',               // المنطقة
'address' => 'sometimes|string|max:255',                // العنوان
'latitude' => 'sometimes|numeric|between:-90,90',       // خط العرض
'longitude' => 'sometimes|numeric|between:-180,180',    // خط الطول
'address_url' => 'sometimes|string|url|nullable',       // رابط العنوان
'phone_number' => 'sometimes|string|max:20',            // رقم الهاتف
```

### حالة المتجر
```php
'is_active' => 'sometimes|boolean',                     // حالة التفعيل
'can_add_products' => 'sometimes|boolean',              // إمكانية إضافة منتجات
'is_approved' => 'sometimes|boolean',                   // حالة الموافقة
'sort_order' => 'sometimes|integer|min:0',             // الترتيب
```

### الفئة والصورة
```php
'category_id' => 'sometimes|exists:categories,id',      // الفئة
'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // الصورة
```

### ساعات العمل
```php
'hours' => 'sometimes|array',                           // ساعات العمل
'hours.*.day' => 'required_with:hours|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
'hours.*.open_time' => 'nullable|date_format:H:i',      // وقت الفتح
'hours.*.close_time' => 'nullable|date_format:H:i|after:hours.*.open_time' // وقت الإغلاق
```

## Usage Examples

### 1. تحديث ترتيب المتاجر
```javascript
// Admin Dashboard
PUT /api/admin/stores/update-order
{
    "stores": [
        {"id": 1, "sort_order": 1},
        {"id": 2, "sort_order": 2},
        {"id": 3, "sort_order": 3}
    ]
}
```

### 2. تحديث متجر بكامل التفاصيل
```javascript
// Admin Dashboard
POST /api/admin/store/update/{id}
{
    "company_id": "encoded_company_id",
    "area_id": "encoded_area_id",
    "category_id": "encoded_category_id",
    "address": "العنوان الجديد",
    "latitude": 24.7136,
    "longitude": 46.6753,
    "phone_number": "+966501234567",
    "is_active": true,
    "is_approved": true,
    "can_add_products": true,
    "sort_order": 1,
    "hours": [
        {
            "day": "monday",
            "open_time": "09:00",
            "close_time": "18:00"
        },
        {
            "day": "tuesday",
            "open_time": "09:00",
            "close_time": "18:00"
        }
    ]
}
```

### 3. عرض المتاجر مع الترتيب
```php
// في الكود
Store::ordered()->get(); // ترتيب حسب sort_order ثم created_at
```

## Implementation Details

### Model Changes
- تم إضافة `sort_order` إلى `fillable` في نموذج `Store`
- تم إضافة scope `ordered()` للترتيب

### Controller Changes
- تم إضافة دالة `updateOrder()` في `A_StoreController`
- تم تحديث جميع controllers لاستخدام الترتيب الجديد
- تم إضافة `A_UpdateStoreService` لتحديث المتاجر بكامل التفاصيل

### Service Changes
- **A_UpdateStoreService**: تحديث المتاجر بكامل التفاصيل
- **S_CreateStoreService**: ترتيب تلقائي للمتاجر الجديدة
- **CreateCompanyServices**: ترتيب تلقائي للمتاجر الجديدة

### Route Changes
- تم إضافة route جديد `/stores/update-order`
- تم تحديث routes المتاجر

## Mobile App Integration
جميع APIs الموبايل تستخدم الترتيب الجديد تلقائياً:
- Frontend Store API
- Supplier Store API
- Admin Store API

## Testing
1. قم بتشغيل migration: `php artisan migrate`
2. اختبر API تحديث الترتيب
3. اختبر API تحديث المتجر بكامل التفاصيل
4. تأكد من ظهور الترتيب في جميع APIs

## Notes
- المتاجر الجديدة تأخذ ترتيب تلقائي (أعلى ترتيب + 1)
- الترتيب يبدأ من 0
- في حالة تساوي الترتيب، يتم الترتيب حسب تاريخ الإنشاء
- جميع APIs الموبايل تعمل بدون تغيير
- **يمكن الآن تعديل جميع تفاصيل المتجر من لوحة التحكم**

## New Files Created
- `app/Services/Admin/Store/A_UpdateStoreService.php` - خدمة تحديث المتاجر
- `app/Http/Requests/v1/Admin/Store/A_UpdateStoreRequest.php` - تحديث validation rules

## Updated Files
- `app/Models/Store.php` - إضافة sort_order
- `app/Http/Controllers/api/v1/Admin/A_StoreController.php` - تحديث controller
- `routes/admin.php` - إضافة routes جديدة
- جميع controllers أخرى لاستخدام الترتيب الجديد
