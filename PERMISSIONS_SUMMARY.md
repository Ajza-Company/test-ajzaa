# 📋 ملخص نظام الصلاحيات - Ajza App

## 🎯 ما تم إنجازه

### 1. **إنشاء Middleware للصلاحيات**
- `CheckUserPermissions` - للتحقق من الصلاحيات
- `CheckUserRole` - للتحقق من الرولز
- تم تسجيلهم في `bootstrap/app.php`

### 2. **إنشاء Artisan Commands**
- `CheckUserPermissions` - فحص صلاحيات المستخدم
- `AssignUserRole` - إضافة رول للمستخدم
- `AssignUserPermission` - إضافة صلاحية للمستخدم
- `ActivateUser` - تفعيل حساب المستخدم
- `ListRolesAndPermissions` - عرض جميع الرولز والصلاحيات

### 3. **إنشاء Helper Functions**
- `checkUserAccess()` - التحقق من صلاحية معينة
- `checkUserRole()` - التحقق من رول معين
- `getUserPermissions()` - الحصول على صلاحيات المستخدم
- `getUserRoles()` - الحصول على رولز المستخدم
- `checkUserLoginStatus()` - فحص حالة تسجيل الدخول

### 4. **إنشاء Controller للصلاحيات**
- `UserPermissionsController` - للتحقق من الصلاحيات عبر API
- Routes مسجلة في `routes/api.php`

### 5. **إنشاء ملفات التوثيق**
- `PERMISSIONS_README.md` - دليل شامل للنظام
- `PERMISSIONS_SUMMARY.md` - ملخص المشروع

## 🚀 كيفية الاستخدام

### **فحص صلاحيات مستخدم معين:**
```bash
php artisan user:check-permissions +966550506713
```

### **إضافة رول لمستخدم:**
```bash
php artisan user:assign-role +966550506713 Supplier
```

### **إضافة صلاحية لمستخدم:**
```bash
php artisan user:assign-permission +966550506713 view-orders
```

### **تفعيل حساب مستخدم:**
```bash
php artisan user:activate +966550506713
```

### **عرض جميع الرولز والصلاحيات:**
```bash
php artisan permissions:list
```

## 🔍 تشخيص مشاكل تسجيل الدخول

### **المشاكل الشائعة:**
1. **المستخدم لا يستطيع تسجيل الدخول**
   - `is_active = false`
   - `is_registered = false`
   - `deletion_status != 'active'`
   - لا يوجد رول مخصص
   - لا توجد صلاحيات مخصصة

2. **المستخدم لا يملك صلاحيات كافية**
   - إضافة صلاحيات إضافية
   - التأكد من وجود رول مناسب

3. **المستخدم محذوف أو معلق**
   - إعادة تفعيل الحساب

## 🛠️ API Endpoints المتاحة

### **GET /api/v1/permissions/my-permissions**
- فحص صلاحيات المستخدم الحالي

### **GET /api/v1/permissions/check-user?mobile={mobile}**
- فحص صلاحيات مستخدم معين

### **POST /api/v1/permissions/check-permission**
- التحقق من صلاحية معينة

### **POST /api/v1/permissions/check-role**
- التحقق من رول معين

### **GET /api/v1/permissions/all**
- عرض جميع الصلاحيات

### **GET /api/v1/permissions/roles**
- عرض جميع الرولز

## 🔧 Middleware المتاحة

### **check.role**
```php
Route::middleware(['auth:sanctum', 'check.role:Supplier'])->group(function () {
    // Routes for Supplier role only
});
```

### **check.permissions**
```php
Route::middleware(['auth:sanctum', 'check.permissions:view-orders'])->group(function () {
    // Routes that require view-orders permission
});
```

## 📊 الرولز والصلاحيات

### **الرولز:**
- Admin - مدير النظام
- Supplier - المورد
- Client - العميل
- Workshop - الورشة
- Representative - الممثل

### **الصلاحيات الرئيسية:**
- عرض الطلبات (`view-orders`)
- قبول الطلبات (`accept-orders`)
- إدارة العروض (`control-offer`)
- إدارة المنتجات (`edit-products`)
- إدارة المتاجر (`edit-stores`)
- إدارة المستخدمين (`edit-users`)
- إدارة الفئات (`edit-categories`)
- عرض الإحصائيات (`view-orders-statistics`)

## ✅ الخطوات التالية

1. **اختبار النظام:**
   ```bash
   php artisan permissions:list
   php artisan user:check-permissions +966550506713
   ```

2. **إضافة صلاحيات للمستخدمين:**
   ```bash
   php artisan user:assign-role +966550506713 Supplier
   php artisan user:assign-permission +966550506713 show-all-permissions
   ```

3. **استخدام Middleware في Routes:**
   ```php
   Route::middleware(['auth:sanctum', 'check.role:Supplier'])->group(function () {
       // Supplier routes
   });
   ```

4. **اختبار API Endpoints:**
   - `/api/v1/permissions/my-permissions`
   - `/api/v1/permissions/check-user?mobile=+966550506713`

## 🆘 استكشاف الأخطاء

### **إذا لم تعمل الأوامر:**
```bash
php artisan config:clear
php artisan cache:clear
```

### **إذا لم يعمل تسجيل الدخول:**
1. استخدم `php artisan user:check-permissions {mobile}`
2. تأكد من أن المستخدم مفعل
3. تأكد من وجود رول مخصص
4. تأكد من وجود صلاحيات مخصصة

## 📝 ملاحظات مهمة

1. **جميع الرولز تستخدم guard 'api'**
2. **الصلاحيات مرتبطة بالرولز**
3. **المستخدم يمكن أن يكون له أكثر من رول**
4. **نظام تسجيل الدخول يتحقق من:**
   - `is_active = true`
   - `is_registered = true`
   - `deletion_status = 'active'`
   - وجود رول واحد على الأقل

## 🎉 النتيجة النهائية

تم إنشاء نظام صلاحيات شامل يتيح:
- إدارة الرولز والصلاحيات
- التحقق من صلاحيات المستخدمين
- تشخيص مشاكل تسجيل الدخول
- إضافة وإزالة الصلاحيات
- API endpoints للتحقق من الصلاحيات
- Middleware لحماية Routes
- Artisan commands لإدارة النظام

النظام جاهز للاستخدام ويمكن تطويره أكثر حسب الحاجة.
