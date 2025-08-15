<?php

use Stevebauman\Location\Facades\Location;
use Stevebauman\Location\Position;

if (!function_exists('getUserLocation')) {
    /**
     * Returns decoded Item
     */
    function getUserLocation($request): Position|bool|null
    {
        return cache()->remember('user_location_'.$request->ip(), 3600, function() use ($request) {
            try {
                return Location::get($request->ip()) ?: null;
            } catch (\Exception $ex) {
                return null;
            }
        });
    }
}
