<?php

use App\Enums\EncodingMethodsEnum;
use App\Models\Setting;
use Illuminate\Contracts\Encryption\DecryptException;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Crypt;

if (!function_exists('ajzaSetting')) {
    /**
     * Returns decoded Item
     */
    function ajzaSetting()
    {
        return json_decode(Setting::first()->setting);
    }
}
