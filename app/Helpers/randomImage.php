<?php

use App\Enums\EncodingMethodsEnum;
use Illuminate\Contracts\Encryption\DecryptException;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Crypt;

if (!function_exists('randomImage')) {
    /**
     * Returns decoded Item
     */
    function randomImage($type = null): array
    {
        if ($type == 'slider') {
            return[
                'https://cdn.salla.sa/form-builder/uFI1nZhhy5EDOcvY8m4jc1nqhdxPc1qIvQAp3PSF.png',
                'https://cdn.salla.sa/form-builder/v2wLgOEKpNR2Hk2PLxuyRou0kMdgjMt5WgfkbB7Y.png',
                'https://fastoriginal.eu/wp-content/uploads/2024/02/slider-fastoriginal-2-1024x577.webp',
                'https://www.stuermer-machines.com/fileadmin/user_upload/csm_Header-Slider_Stuermer_Ersatzteilshop_2024_landing_page.jpg'
            ];
        }
        return[
            'https://png.pngtree.com/thumb_back/fh260/background/20230521/pngtree-an-image-of-various-pieces-of-automobile-parts-in-an-automobile-image_2680988.jpg',
            'https://png.pngtree.com/background/20230522/original/pngtree-silver-car-laying-out-on-a-grey-background-with-different-parts-picture-image_2694720.jpg',
            'https://images.akhbarelyom.com//images/images/medium/20180824124108053.jpg',
            'https://image.made-in-china.com/202f0j00atKkefYgIqrV/Engine-Mounting-for-Hyundai-Car-Parts-and-China-Car-Parts.webp',
            'https://cdn.salla.sa/EqoGY/RezCc2coBV4vHkMlfockAETZ8AuPPiaJDXOBt50c.jpg',
            'https://fvs.com.sa/wp-content/uploads/2023/10/%D9%85%D8%B4%D8%B1%D9%88%D8%B9-%D9%84%D9%85%D8%AD%D9%84-%D8%AA%D8%AC%D8%A7%D8%B1%D8%A9-%D9%82%D8%B7%D8%B9-%D8%BA%D9%8A%D8%A7%D8%B1.jpg',
            'https://cdn.salla.sa/EZYaDB/BNEzypaxjGFwEe4nJ8dsK1zpSbNgRo6Pj0EbYdf2.jpg',
            'https://lh7-us.googleusercontent.com/TCRNvQaEpviPm69AnX5L6vHYyoYstdkTWQtWIg9Y1HDu1Ospf6UZWS0-G5NTMEh59NrqSIiGrkFHwEEgYqdslCOdDXqY3UKJPr5dFXTG2HnwxDTAv_Pmjgx8xYy8MIbRPdjcjfXFgE2HffdTzVRBP0s',
            'https://images-eu.ssl-images-amazon.com/images/I/71E-9zis47L._AC_UL330_SR330,330_.jpg',
            'https://i.pinimg.com/1200x/9f/18/58/9f18583fed6c6245c30ff424192f4ada.jpg',
            'https://images-na.ssl-images-amazon.com/images/I/416ksYUThhL._SL500_._AC_SL500_.jpg'
        ];
    }
}
