<?php

if (!function_exists('distanceTimeBetweenTwoLocations')) {
    /**
     * Returns decoded Item
     */
    function distanceTimeBetweenTwoLocations($lat1, $lon1, $lat2, $lon2, $speed = 90): array
    {
        $earthRadius = 6371; // Radius of the Earth in kilometers

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        $distance = $earthRadius * $angle;
        return [
            'distance' => round($distance, 2), // in kilometers
            'time' => round(($distance / $speed) * 60), // in minutes
        ];
    }
}
