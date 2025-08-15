<?php

namespace App\Repositories\Frontend\SliderImage\Fetch;

use App\Models\SliderImage;
use App\Repositories\Frontend\F_FetchRepository;

class F_FetchSliderImageRepository extends F_FetchRepository implements F_FetchSliderImageInterface
{
    /**
     * Create a new instance.
     *
     * @param SliderImage $model
     */
    public function __construct(SliderImage $model)
    {
        parent::__construct($model);
    }
}
