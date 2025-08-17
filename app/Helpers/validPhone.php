<?php

if (!function_exists('isValidPhone')) {
    /**
     * Returns boolean if phone number is valid (fallback implementation)
     * TODO: Install propaganistas/laravel-phone package for better validation
     */
    function isValidPhone(string $phone_number): bool
    {
        // Fallback: Basic Saudi phone number validation
        // Remove any non-digit characters except +
        $cleanPhone = preg_replace('/[^\d+]/', '', $phone_number);
        
        // Check if it starts with +966 or 966 or 05
        return preg_match('/^(\+966|966|05)\d{8}$/', $cleanPhone) === 1;
    }
}
