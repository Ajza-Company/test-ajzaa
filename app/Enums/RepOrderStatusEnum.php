<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 */
final class RepOrderStatusEnum extends Enum
{
    const PENDING = 'pending';
    const ACCEPTED = 'accepted';
    const ENDED = 'ended';
    const CANCELLED = 'cancelled';
    const TIMEOUT = 'timeout';
}
