<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 */
final class OrderStatusEnum extends Enum
{
    const PENDING = 'pending';
    const SHIPPED = 'shipped';
    const ACCEPTED = 'accepted';
    const CANCELLED = 'cancelled';
    const REJECTED = 'rejected';
    const COMPLETED = 'completed';
}
