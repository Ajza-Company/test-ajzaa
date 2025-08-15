<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PaymentMethodsEnum extends Enum
{
    const CARD = 'card';
    const APPLE_PAY = 'apple_pay';
    const WALLET = 'wallet';
}
