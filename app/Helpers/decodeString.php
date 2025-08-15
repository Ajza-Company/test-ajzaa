<?php

use App\Enums\EncodingMethodsEnum;
use Illuminate\Contracts\Encryption\DecryptException;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Crypt;

if (!function_exists('decodeString')) {
    /**
     * Returns decoded Item
     */
    function decodeString(string $encodedValue, string $method = EncodingMethodsEnum::HASHID)
    {
        return (int)$encodedValue;
        return match ($method) {
            'hashid' => decodeHashid($encodedValue),
            'crypt' => decodeCrypt($encodedValue),
            default => throw new InvalidArgumentException("Unsupported decoding method: $method"),
        };
    }

    /**
     * Decodes a hashid-encoded string.
     */
    function decodeHashid(string $encodedValue): ?string
    {
        $decodedValue = Hashids::decode($encodedValue);
        return count($decodedValue) > 0 ? $decodedValue[0] : null;
    }

    /**
     * Decodes an encrypted string using Laravel's Crypt facade.
     */
    function decodeCrypt(string $encodedValue): ?string
    {
        try {
            return Crypt::decrypt($encodedValue);
        } catch (DecryptException $e) {
            return null;
        }
    }
}
