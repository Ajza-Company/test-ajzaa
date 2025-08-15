<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 */
final class EncodingMethodsEnum extends Enum
{
    const HASHID = 'hashid';
    const CRYPT = 'crypt';
}
