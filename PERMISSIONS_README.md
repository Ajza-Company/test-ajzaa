# 🔐 نظام الصلاحيات والرولز - Ajza App

## 📋 نظرة عامة

هذا النظام يستخدم مكتبة **Spatie Laravel Permission** لإدارة الرولز والصلاحيات في تطبيق Ajza.

## 🎯 الرولز المتاحة

### 1. **Admin** - مدير النظام
- يملك جميع الصلاحيات
- يمكنه إدارة المستخدمين والمتاجر والمنتجات

### 2. **Supplier** - المورد
- يمكنه إدارة متاجره ومنتجاته
- يمكنه عرض وقبول الطلبات
- يمكنه إدارة العروض

### 3. **Client** - العميل
- يمكنه تصفح المنتجات
- يمكنه عمل الطلبات
- يمكنه إدارة حساباته

### 4. **Workshop** - الورشة
- يمكنه إدارة طلبات الإصلاح
- يمكنه التواصل مع العملاء

### 5. **Representative** - الممثل
- يمكنه إدارة الطلبات
- يمكنه التواصل مع العملاء

## 🔑 الصلاحيات المتاحة

### صلاحيات المورد (Supplier):
- `show-all-permissions` - عرض جميع الصلاحيات
- `view-orders` - عرض الطلبات
- `accept-orders` - قبول الطلبات
- `view-offers` - عرض العروض
- `control-offer` - التحكم في العرض
- `view-users` - عرض المستخدمين
- `edit-users` - تعديل المستخدمين
- `view-stores` - عرض المتاجر
- `edit-stores` - تعديل المتاجر
- `view-categories` - عرض الفئات
- `edit-categories` - تعديل الفئات
- `view-products` - عرض المنتجات
- `edit-products` - تعديل المنتجات
- `view-orders-statistics` - عرض إحصائيات الطلبات

### صلاحيات المدير (Admin):
- `a.show-all-users` - عرض جميع المستخدمين
- `a.control-user` - التحكم في المستخدم
- `a.show-all-stores` - عرض جميع المتاجر
- `a.control-store` - التحكم في المتجر
- `a.show-all-repSales` - عرض جميع مندوبي المبيعات
- `a.control-repSales` - التحكم في مندوبي المبيعات
- `a.show-all-promos` - عرض جميع الرموز الترويجية
- `a.control-promo` - التحكم في الرمز الترويجي
- `a.show-all-products` - عرض جميع المنتجات
- `a.control-product` - التحكم في المنتج
- `a.show-all-states` - عرض جميع الولايات
- `a.control-state` - التحكم في الولاية
- `a.show-all-offers` - عرض جميع العروض
- `a.control-offers` - التحكم في العروض
- `a.show-all-chat` - عرض جميع المحادثات
- `a.control-chat` - التحكم في المحادثة

## 🛠️ الأوامر المتاحة

### 1. عرض جميع الرولز والصلاحيات:
```bash
php artisan permissions:list
```

### 2. التحقق من صلاحيات مستخدم معين:
```bash
php artisan user:check-permissions +966550506713
```

### 3. إضافة رول لمستخدم:
```bash
php artisan user:assign-role +966550506713 Supplier
```

### 4. إضافة صلاحية لمستخدم:
```bash
php artisan user:assign-permission +966550506713 view-orders
```

### 5. تفعيل حساب مستخدم:
```bash
php artisan user:activate +966550506713
```

## 🔍 تشخيص مشاكل تسجيل الدخول

### المشاكل الشائعة:

#### 1. **المستخدم لا يستطيع تسجيل الدخول**
**الأسباب المحتملة:**
- `is_active = false`
- `is_registered = false`
- `deletion_status != 'active'`
- لا يوجد رول مخصص للمستخدم
- لا توجد صلاحيات مخصصة

**الحل:**
```bash
# تفعيل المستخدم
php artisan user:activate +966550506713

# إضافة رول
php artisan user:assign-role +966550506713 Supplier

# إضافة صلاحيات
php artisan user:assign-permission +966550506713 show-all-permissions
```

#### 2. **المستخدم لا يملك صلاحيات كافية**
**الحل:**
```bash
# إضافة صلاحيات إضافية
php artisan user:assign-permission +966550506713 view-orders
php artisan user:assign-permission +966550506713 accept-orders
```

#### 3. **المستخدم محذوف أو معلق**
**الحل:**
```bash
# إعادة تفعيل الحساب
php artisan user:activate +966550506713
```

## 📊 فحص حالة المستخدم

```bash
php artisan user:check-permissions +966550506713
```

**المخرجات المتوقعة:**
```
User Information:
ID: 123
Name: اسم المستخدم
Email: user@example.com
Mobile: +966550506713
Is Active: Yes
Is Registered: Yes
Deletion Status: active

Roles:
- Supplier

Permissions through Roles:
- show-all-permissions
- view-orders
- accept-orders
- view-offers

✅ User can login successfully
```

## 🚀 إضافة مستخدم جديد مع صلاحيات

### 1. إنشاء المستخدم في قاعدة البيانات
### 2. تفعيل الحساب:
```bash
php artisan user:activate +966550506713
```

### 3. إضافة رول:
```bash
php artisan user:assign-role +966550506713 Supplier
```

### 4. إضافة صلاحيات:
```bash
php artisan user:assign-permission +966550506713 show-all-permissions
php artisan user:assign-permission +966550506713 view-orders
php artisan user:assign-permission +966550506713 accept-orders
```

## 🔧 Middleware المتاحة

### 1. **check.role** - التحقق من الرول:
```php
Route::middleware(['auth:sanctum', 'check.role:Supplier'])->group(function () {
    // Routes for Supplier role only
});
```

### 2. **check.permissions** - التحقق من الصلاحيات:
```php
Route::middleware(['auth:sanctum', 'check.permissions:view-orders'])->group(function () {
    // Routes that require view-orders permission
});
```

### 3. **role** - Spatie Permission middleware:
```php
Route::middleware(['auth:sanctum', 'role:Supplier'])->group(function () {
    // Routes for Supplier role
});
```

### 4. **permission** - Spatie Permission middleware:
```php
Route::middleware(['auth:sanctum', 'permission:view-orders'])->group(function () {
    // Routes that require view-orders permission
});
```

## 📝 ملاحظات مهمة

1. **جميع الرولز تستخدم guard 'api'**
2. **الصلاحيات مرتبطة بالرولز**
3. **المستخدم يمكن أن يكون له أكثر من رول**
4. **الصلاحيات يمكن إضافتها مباشرة للمستخدم أو من خلال الرولز**
5. **نظام تسجيل الدخول يتحقق من:**
   - `is_active = true`
   - `is_registered = true`
   - `deletion_status = 'active'`
   - وجود رول واحد على الأقل

## 🆘 استكشاف الأخطاء

### إذا لم تعمل الأوامر:
1. تأكد من تشغيل `php artisan config:clear`
2. تأكد من تشغيل `php artisan cache:clear`
3. تأكد من وجود البيانات في قاعدة البيانات
4. تحقق من سجلات الأخطاء في `storage/logs/laravel.log`

### إذا لم يعمل تسجيل الدخول:
1. استخدم `php artisan user:check-permissions {mobile}` لفحص الحالة
2. تأكد من أن المستخدم مفعل
3. تأكد من وجود رول مخصص
4. تأكد من وجود صلاحيات مخصصة
