<?php

if (!function_exists('isValidPhone')) {
    /**
     * Returns boolean if phone number is valid (fallback implementation)
     * TODO: Install propaganistas/laravel-phone package for better validation
     */
    function isValidPhone(string $phone_number): bool
    {
        // إلغاء الـ validation تماماً - تقبل أي رقم
        return true;
        
        // الكود القديم (معلق):
        // $cleanPhone = preg_replace('/[^\d+]/', '', $phone_number);
        // return preg_match('/^(\+966|966|05)\d{8}$/', $cleanPhone) === 1;
    }
}
