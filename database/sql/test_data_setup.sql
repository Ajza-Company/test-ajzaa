-- 🧪 Custom Categories Test Data Setup SQL Script
-- Run this script if you prefer to set up test data manually

-- 1. Create Test Company
INSERT INTO companies (id, name, email, phone, is_active, is_approved, created_at, updated_at) 
VALUES (1, 'Test Company', 'test@company.com', '+966501234567', 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- 2. Create Test Users
INSERT INTO users (name, email, password, phone, is_active, is_admin, company_id, created_at, updated_at) VALUES
('System Admin', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+966501234568', 1, 1, NULL, NOW(), NOW()),
('Company Admin', 'company@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+966501234569', 1, 0, 1, NOW(), NOW()),
('Regular User', 'user@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+966501234570', 1, 0, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- 3. Create System Categories
INSERT INTO categories (id, category_type, company_id, image, is_active, sort_order, created_at, updated_at) VALUES
(1, 'system', NULL, 'cars.jpg', 1, 0, NOW(), NOW()),
(2, 'system', NULL, 'parts.jpg', 1, 1, NOW(), NOW()),
(3, 'system', NULL, 'accessories.jpg', 1, 2, NOW(), NOW()),
(4, 'system', NULL, 'oils.jpg', 1, 3, NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- 4. Create Custom Categories
INSERT INTO categories (id, category_type, company_id, image, is_active, sort_order, created_at, updated_at) VALUES
(5, 'custom', 1, 'luxury-cars.jpg', 1, 0, NOW(), NOW()),
(6, 'custom', 1, 'original-parts.jpg', 1, 1, NOW(), NOW()),
(7, 'custom', 1, 'classic-cars.jpg', 1, 2, NOW(), NOW()),
(8, 'custom', 1, 'luxury-accessories.jpg', 0, 3, NOW(), NOW()),
(9, 'custom', 1, 'maintenance.jpg', 1, 4, NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- 5. Create Category Locales (System Categories)
INSERT INTO category_locales (category_id, locale_id, name, created_at, updated_at) VALUES
(1, 1, 'سيارات', NOW(), NOW()),
(2, 1, 'قطع غيار', NOW(), NOW()),
(3, 1, 'إكسسوارات', NOW(), NOW()),
(4, 1, 'زيوت ومواد تشحيم', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- 6. Create Category Locales (Custom Categories)
INSERT INTO category_locales (category_id, locale_id, name, created_at, updated_at) VALUES
(5, 1, 'سيارات فاخرة', NOW(), NOW()),
(6, 1, 'قطع غيار أصلية', NOW(), NOW()),
(7, 1, 'سيارات كلاسيكية', NOW(), NOW()),
(8, 1, 'إكسسوارات فاخرة', NOW(), NOW()),
(9, 1, 'خدمات الصيانة', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- 7. Create Test Products
INSERT INTO products (id, category_id, price, is_active, is_original, created_at, updated_at) VALUES
(1, 1, 150.00, 1, 1, NOW(), NOW()),  -- Engine Oil (System Category)
(2, 1, 45.00, 1, 1, NOW(), NOW()),   -- Air Filter (System Category)
(3, 5, 2500000.00, 1, 1, NOW(), NOW()), -- Mercedes AMG GT (Custom Category)
(4, 5, 1800000.00, 1, 1, NOW(), NOW()), -- BMW M4 (Custom Category)
(5, 5, 2200000.00, 1, 1, NOW(), NOW())  -- Audi RS6 (Custom Category)
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- 8. Create Product Locales
INSERT INTO product_locales (product_id, locale_id, name, description, created_at, updated_at) VALUES
(1, 1, 'زيت محرك 5W-30', 'زيت محرك عالي الجودة', NOW(), NOW()),
(2, 1, 'فلتر هواء', 'فلتر هواء للمحرك', NOW(), NOW()),
(3, 1, 'مرسيدس AMG GT', 'سيارة رياضية فاخرة', NOW(), NOW()),
(4, 1, 'BMW M4', 'سيارة رياضية ألمانية', NOW(), NOW()),
(5, 1, 'أودي RS6', 'سيارة عائلية رياضية', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- 9. Verify Data
SELECT 'Test Data Summary:' as info;
SELECT COUNT(*) as total_companies FROM companies WHERE id = 1;
SELECT COUNT(*) as total_users FROM users WHERE email LIKE '%@test.com';
SELECT COUNT(*) as system_categories FROM categories WHERE category_type = 'system';
SELECT COUNT(*) as custom_categories FROM categories WHERE category_type = 'custom' AND company_id = 1;
SELECT COUNT(*) as total_products FROM products WHERE id IN (1,2,3,4,5);

-- 10. Show Custom Categories for Company 1
SELECT 'Custom Categories for Company 1:' as info;
SELECT 
    c.id,
    c.category_type,
    c.company_id,
    c.is_active,
    c.sort_order,
    cl.name
FROM categories c
JOIN category_locales cl ON c.id = cl.category_id
WHERE c.category_type = 'custom' AND c.company_id = 1
ORDER BY c.sort_order;

-- 11. Show Products by Category
SELECT 'Products by Category:' as info;
SELECT 
    p.id,
    p.category_id,
    p.price,
    pl.name,
    pl.description,
    c.category_type,
    cl.name as category_name
FROM products p
JOIN product_locales pl ON p.id = pl.product_id
JOIN categories c ON p.category_id = c.id
JOIN category_locales cl ON c.id = cl.category_id
ORDER BY c.category_type, c.id;
