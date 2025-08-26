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

        // Apply with-stores filter if requested
        if (request()->has('with-stores') && filter_var(request('with-stores'), FILTER_VALIDATE_BOOLEAN)) {
            // Load stores for each category individually to apply limit per category
            $categories->each(function ($category) {
                $storesQuery = $category->stores()
                    ->whereHas('company', function ($companyQuery) {
                        $companyQuery->where('is_active', true);
                    })
                    ->where('is_active', true)
                    // Apply stores count limit PER CATEGORY if specified
                    ->when(request()->has('stores-count-limit') && request('stores-count-limit') > 0, function ($storeQuery) {
                        $storeQuery->limit(request('stores-count-limit'));
                    })
                    // Load related data for comprehensive store information
                    ->with([
                        'company.localized',      // Company name and details
                        'area.localized',         // Store location details
                        'category',               // Store category
                        'hours',                  // Store operating hours
                        'storeProducts' => function ($productQuery) {
                            // Only include products with stock
                            $productQuery->where('quantity', '>', 0)
                                       ->with('product.localized');
                        }
                    ])
                    // Add counts for additional information
                    ->withCount([
                        'storeProducts' => function ($query) {
                            // Count of products with stock
                            $query->where('quantity', '>', 0);
                        },
                        'storeProducts as offers_count' => function ($query) {
                            // Count of products with offers (excluding ajza offers)
                            $query->whereHas('offer', function ($offerQuery) {
                                $offerQuery->where('ajza_offer', false);
                            });
                        }
                    ]);
                
                // Load stores for this specific category
                $category->setRelation('stores', $storesQuery->get());
            });
            
            // Add total store count for each category
            $categories->each(function ($category) {
                $category->stores_count = $category->stores()->whereHas('company', function ($query) {
                    $query->where('is_active', true);
                })->where('is_active', true)->count();
            });
        }

        return F_CategoryResource::collection($categories);
    }
}
