<?php

if (!function_exists('getFullUrl')) {
    /**
     * @return null|string The function `getFullUrl` returns a string that concatenates the
     * string "storage/" with the value of the `` parameter and passes it to
     * the `asset` function.
     */
    function getFullUrl(
        ?string $url,
        ?string $default = null
    ) {
        if (!isset($default))  $default = asset("storage/default-no-image.jpg");

        $generatedUrl = str_starts_with($url, "http") ? $url : ($url ? asset("storage/{$url}") : $default);

        if (filter_var($generatedUrl, FILTER_VALIDATE_URL)) {
            return $generatedUrl;
        }

        return $default;
    }
}
