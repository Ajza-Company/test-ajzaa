<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Frontend\Category\F_CategoryResource;
use App\Repositories\Frontend\Category\Fetch\F_FetchCategoryInterface;
use Illuminate\Http\Request;

class F_CategoryController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param F_FetchCategoryInterface $fetchCategory
     */
    public function __construct(private F_FetchCategoryInterface $fetchCategory)
    {

    }

    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        return F_CategoryResource::collection($this->fetchCategory->fetch(paginate: false, latest: false, with: ['variants']));
    }
}
