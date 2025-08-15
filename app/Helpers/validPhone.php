<?php

use Propaganistas\LaravelPhone\PhoneNumber;

if (!function_exists('isValidPhone')) {
    /**
     * Returns boolean if phone number is valid
     */
    function isValidPhone(string $phone_number): bool
    {
        $phone = new PhoneNumber($phone_number, 'SA');
        return $phone->isValid();
    }
}
