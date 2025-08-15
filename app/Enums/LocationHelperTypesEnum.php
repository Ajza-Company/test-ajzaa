<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 */
final class LocationHelperTypesEnum extends Enum
{
    const Timezone = 'timezone';
    const CountryCode = 'countryCode';
    const Country = 'countryName';
    const Latitude = 'latitude';
}
