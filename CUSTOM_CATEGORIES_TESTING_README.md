# 🧪 Custom Categories Testing Guide

## 📋 Overview
This guide will help you set up and test the Custom Categories feature on your local database.

## 🚀 Quick Setup

### 1. Run the Master Seeder
```bash
php artisan db:seed --class=CustomCategoriesMasterSeeder
```

This will:
- ✅ Run all necessary migrations
- ✅ Create test company
- ✅ Create test users
- ✅ Create system categories
- ✅ Create custom categories
- ✅ Create test products

### 2. Alternative: Run Individual Seeders
```bash
# Run migrations first
php artisan migrate

# Create test users
php artisan db:seed --class=TestUsersSeeder

# Create custom categories and products
php artisan db:seed --class=CustomCategoriesTestSeeder
```

---

## 📊 Test Data Created

### 🏢 Test Company
- **ID**: 1
- **Name**: Test Company
- **Email**: test@company.com
- **Status**: Active & Approved

### 👥 Test Users
| Email | Password | Role | Company |
|-------|----------|------|---------|
| `admin@test.com` | `password123` | System Admin | None |
| `company@test.com` | `password123` | Company Admin | Test Company |
| `user@test.com` | `password123` | Regular User | Test Company |

### 🏷️ System Categories (Global)
1. **سيارات** - Cars
2. **قطع غيار** - Spare Parts
3. **إكسسوارات** - Accessories
4. **زيوت ومواد تشحيم** - Oils & Lubricants

### 🏷️ Custom Categories (Company-Specific)
1. **سيارات فاخرة** - Luxury Cars (Active)
2. **قطع غيار أصلية** - Original Parts (Active)
3. **سيارات كلاسيكية** - Classic Cars (Active)
4. **إكسسوارات فاخرة** - Luxury Accessories (Inactive)
5. **خدمات الصيانة** - Maintenance Services (Active)

### 📦 Test Products
- **System Category Products**:
  - زيت محرك 5W-30 (Engine Oil)
  - فلتر هواء (Air Filter)

- **Custom Category Products**:
  - مرسيدس AMG GT (Mercedes AMG GT)
  - BMW M4
  - أودي RS6 (Audi RS6)

---

## 🧪 Testing Scenarios

### 🔐 Authentication Testing
1. **Valid Token**: Use any test user's token
2. **Invalid Token**: Test with wrong/missing token
3. **Expired Token**: Test with expired token

### 👑 Permission Testing
1. **System Admin**: Should access everything
2. **Company Admin**: Should access only their company
3. **Regular User**: Should have limited access

### 📝 CRUD Operations Testing
1. **Create**: Test with valid/invalid data
2. **Read**: Test different filters and scopes
3. **Update**: Test partial and full updates
4. **Delete**: Test with/without products

### 🔄 Bulk Operations Testing
1. **Bulk Order Update**: Test multiple categories
2. **Bulk Status Update**: Test activate/deactivate
3. **Statistics**: Test data accuracy
4. **Search**: Test different query patterns

---

## 📱 Postman Testing

### 1. Import Collection
Import the `Custom_Categories_Postman_Collection.json` file

### 2. Set Environment Variables
```
base_url: http://localhost:8000/api/v1
token: your_bearer_token_here
company_id: 1
```

### 3. Get Authentication Token
```bash
# Login with any test user
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@test.com",
    "password": "password123"
  }'
```

### 4. Test Flow
1. **Get System Categories** (should work without auth)
2. **Get Custom Categories** (requires auth)
3. **Create Custom Category** (test validation)
4. **Update Custom Category** (test permissions)
5. **Test Bulk Operations**
6. **Test Products APIs**

---

## 🐛 Common Issues & Solutions

### Issue: Migration Fails
```bash
# Solution: Clear cache and retry
php artisan config:clear
php artisan cache:clear
php artisan migrate:fresh
```

### Issue: Seeder Fails
```bash
# Solution: Check database connection
php artisan tinker
DB::connection()->getPdo();
```

### Issue: API Returns 500 Error
```bash
# Solution: Check Laravel logs
tail -f storage/logs/laravel.log
```

### Issue: Permission Denied
```bash
# Solution: Check user permissions
php artisan tinker
User::find(1)->permissions;
```

---

## 📊 Expected API Responses

### ✅ Success Response Format
```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": { ... }
}
```

### ❌ Error Response Format
```json
{
    "success": false,
    "message": "Error description",
    "errors": { ... }
}
```

---

## 🔍 Testing Checklist

### Basic Functionality
- [ ] System categories are accessible
- [ ] Custom categories are company-specific
- [ ] CRUD operations work correctly
- [ ] Validation rules are enforced

### Permissions
- [ ] System admin can access everything
- [ ] Company admin can access their company only
- [ ] Regular user has limited access
- [ ] Unauthorized access is blocked

### Data Integrity
- [ ] Categories have correct types
- [ ] Company relationships are maintained
- [ ] Products are properly categorized
- [ ] Soft deletes work correctly

### Performance
- [ ] API responses are fast
- [ ] Database queries are optimized
- [ ] No N+1 query problems
- [ ] Proper indexing is in place

---

## 🎯 Next Steps

1. **Test all endpoints** using Postman
2. **Verify data integrity** in database
3. **Test edge cases** and error scenarios
4. **Performance testing** with larger datasets
5. **Integration testing** with frontend

---

## 📞 Support

If you encounter any issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify database structure
3. Check user permissions
4. Test with different user roles

---

**Happy Testing! 🎉**
