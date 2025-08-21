<?php

namespace App\Http\Resources\v1\Frontend\Store;

use App\Enums\EncodingMethodsEnum;
use App\Http\Resources\v1\Frontend\Area\F_AreaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_ShortStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get location from request attributes (set earlier) or cache
        $location = $request->attributes->get('user_location_'.$request->ip()) ?? getUserLocation($request);
        $latitude = $location?->latitude ?? 0;
        $longitude = $location?->longitude ?? 0;
        $distanceAndTime = distanceTimeBetweenTwoLocations($latitude, $longitude, $this->latitude, $this->longitude);
        $localizedDistance = trans('general.distance', [
            'distance' => round($distanceAndTime['distance'], 1),     // Value for :distance
            'distanceUnit' => trans('general.km')
        ]);
        return [
            'id' => encodeString($this->id),
            'name' => $this->company?->localized?->name,
            'rate' => 4.3,
            'image' => getFullUrl($this->company?->cover_image),
            'distanceAndTime' => '',
            'address' => $localizedDistance,
            'is_open' => true,
            'can_add_products' => (bool) $this->can_add_products
        ];
    }
}
