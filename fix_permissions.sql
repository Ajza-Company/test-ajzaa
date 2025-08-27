-- 🔧 Fix Permissions System for Custom Categories
-- Run this script to set up proper permissions

-- 1. Add missing permissions for custom categories
INSERT IGNORE INTO permissions (name, guard_name, created_at, updated_at) VALUES
('manage_categories', 'api', NOW(), NOW()),
('manage_company_categories', 'api', NOW(), NOW()),
('view_custom_categories', 'api', NOW(), NOW()),
('create_custom_categories', 'api', NOW(), NOW()),
('edit_custom_categories', 'api', NOW(), NOW()),
('delete_custom_categories', 'api', NOW(), NOW());

-- 2. Create role_user table if it doesn't exist
CREATE TABLE IF NOT EXISTS role_user (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_role_user (role_id, user_id)
);

-- 3. Create permission_role table if it doesn't exist
CREATE TABLE IF NOT EXISTS permission_role (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    permission_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    UNIQUE KEY unique_permission_role (permission_id, role_id)
);

-- 4. Assign permissions to Admin role
INSERT IGNORE INTO permission_role (permission_id, role_id, created_at, updated_at) VALUES
(1, 1, NOW(), NOW()),  -- show-all-permissions -> Admin
(2, 1, NOW(), NOW()),  -- view-orders -> Admin
(3, 1, NOW(), NOW()),  -- accept-orders -> Admin
(4, 1, NOW(), NOW()),  -- view-offers -> Admin
(5, 1, NOW(), NOW()),  -- control-offer -> Admin
(6, 1, NOW(), NOW()),  -- view-users -> Admin
(7, 1, NOW(), NOW()),  -- edit-users -> Admin
(8, 1, NOW(), NOW()),  -- view-stores -> Admin
(9, 1, NOW(), NOW()),  -- edit-stores -> Admin
(10, 1, NOW(), NOW()), -- view-categories -> Admin
(11, 1, NOW(), NOW()), -- manage_categories -> Admin
(12, 1, NOW(), NOW()), -- manage_company_categories -> Admin
(13, 1, NOW(), NOW()), -- view_custom_categories -> Admin
(14, 1, NOW(), NOW()), -- create_custom_categories -> Admin
(15, 1, NOW(), NOW()), -- edit_custom_categories -> Admin
(16, 1, NOW(), NOW()); -- delete_custom_categories -> Admin

-- 5. Assign permissions to Supplier role
INSERT IGNORE INTO permission_role (permission_id, role_id, created_at, updated_at) VALUES
(2, 4, NOW(), NOW()),  -- view-orders -> Supplier
(4, 4, NOW(), NOW()),  -- view-offers -> Supplier
(8, 4, NOW(), NOW()),  -- view-stores -> Supplier
(12, 4, NOW(), NOW()), -- manage_company_categories -> Supplier
(13, 4, NOW(), NOW()), -- view_custom_categories -> Supplier
(14, 4, NOW(), NOW()), -- create_custom_categories -> Supplier
(15, 4, NOW(), NOW()), -- edit_custom_categories -> Supplier
(16, 4, NOW(), NOW()); -- delete_custom_categories -> Supplier

-- 6. Assign Admin role to test admin user (ID: 2)
INSERT IGNORE INTO role_user (role_id, user_id, created_at, updated_at) VALUES
(1, 2, NOW(), NOW());

-- 7. Assign Supplier role to test supplier user (ID: 8)
INSERT IGNORE INTO role_user (role_id, user_id, created_at, updated_at) VALUES
(4, 8, NOW(), NOW());

-- 8. Show results
SELECT '=== PERMISSIONS SETUP COMPLETED ===' as message;

SELECT 'Admin User (ID: 2) now has Admin role with all permissions' as info;
SELECT 'Supplier User (ID: 8) now has Supplier role with custom category permissions' as info;

-- 9. Verify setup
SELECT 'Verifying permissions for Admin role:' as check;
SELECT p.name as permission_name, r.name as role_name
FROM permissions p
JOIN permission_role pr ON p.id = pr.permission_id
JOIN roles r ON pr.role_id = r.id
WHERE r.name = 'Admin'
ORDER BY p.name;
