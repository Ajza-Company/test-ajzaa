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
        // Get all categories with custom sorting (sort_order)
        $categories = \App\Models\Category::whereHas('localized')
            ->with(['localized', 'variants'])
            ->ordered() // Custom sort by sort_order
            ->get();

        return F_CategoryResource::collection($categories);
    }
}
