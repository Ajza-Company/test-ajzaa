<?php

if (!function_exists('getUserLocation')) {
    /**
     * Returns mock location data (fallback implementation)
     * TODO: Install stevebauman/location package for real IP geolocation
     */
    function getUserLocation($request): ?object
    {
        return cache()->remember('user_location_'.$request->ip(), 3600, function() use ($request) {
            try {
                // Fallback: Return a mock location object for Saudi Arabia
                return (object) [
                    'countryName' => 'Saudi Arabia',
                    'countryCode' => 'SA',
                    'regionName' => 'Riyadh',
                    'cityName' => 'Riyadh',
                    'zipCode' => '11564',
                    'latitude' => 24.7136,
                    'longitude' => 46.6753,
                    'ip' => $request->ip()
                ];
            } catch (\Exception $ex) {
                return null;
            }
        });
    }
}
