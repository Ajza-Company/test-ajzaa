<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Frontend\SliderImage\F_SliderImageResource;
use App\Repositories\Frontend\SliderImage\Fetch\F_FetchSliderImageInterface;
use Illuminate\Http\Request;

class F_SliderImageController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param F_FetchSliderImageInterface $fetchSliderImage
     */
    public function __construct(private F_FetchSliderImageInterface $fetchSliderImage)
    {

    }

    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        return F_SliderImageResource::collection($this->fetchSliderImage->fetch(isLocalized: false));
    }
}
