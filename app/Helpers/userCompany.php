<?php

if (!function_exists('userCompany')) {
    /**
     * Returns decoded Item
     */
    function userCompany()
    {
        Log::info('userCompany: ' . json_encode(auth('api')->user()?->company));
        return auth('api')?->user()?->company;
    }
}
