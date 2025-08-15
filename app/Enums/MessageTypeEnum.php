<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 */
final class MessageTypeEnum extends Enum
{
    const TEXT = 'text';
    const ATTACHMENT = 'attachment';
    const OFFER = 'offer';
    const ENDED = 'ended';
    const START_DELIVERY = 'start_delivery';
    const CANCELLED = 'cancelled';
}
