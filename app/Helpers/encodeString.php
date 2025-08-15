<?php

use App\Enums\EncodingMethodsEnum;
use Vinkla\Hashids\Facades\Hashids;

if (!function_exists('encodeString')) {
    /**
     * Returns decoded Item
     */
    function encodeString(?string $value, string $method = EncodingMethodsEnum::HASHID)
    {
        return (string)$value;
        if (!$value) {
            return null;
        }
        return match ($method) {
            'hashid' => Hashids::encode($value),
            'crypt' => Crypt::encrypt($value),
            default => throw new InvalidArgumentException("Unsupported encoding method: $method"),
        };
    }
}
