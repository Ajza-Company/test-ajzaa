<?php

if (!function_exists('parseBoolean')) {
    /**
     * Returns decoded Item
     */
    function parseBoolean(mixed $value): bool
    {
        if (is_string($value)) {
            return strtolower($value) === 'true' || $value === '1';
        }

        return (bool) $value;
    }
}
