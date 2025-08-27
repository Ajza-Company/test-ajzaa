<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyProductController extends Controller
{
    /**
     * Get all products for a company (including system and custom categories)
     */
    public function index($companyId): JsonResponse
    {
        $company = Company::findOrFail($companyId);
        
        $products = Product::whereHas('category', function ($query) use ($companyId) {
            $query->where(function ($q) use ($companyId) {
                $q->where('category_type', 'system')
                  ->orWhere(function ($subQ) use ($companyId) {
                      $subQ->where('category_type', 'custom')
                           ->where('company_id', $companyId);
                  });
            });
        })
        ->with(['category.localized', 'category.company.localized'])
        ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get products by category for a company
     */
    public function getByCategory($companyId, $categoryId): JsonResponse
    {
        $company = Company::findOrFail($companyId);
        
        $products = Product::where('category_id', $categoryId)
            ->whereHas('category', function ($query) use ($companyId) {
                $query->where(function ($q) use ($companyId) {
                    $q->where('category_type', 'system')
                      ->orWhere(function ($subQ) use ($companyId) {
                          $subQ->where('category_type', 'custom')
                               ->where('company_id', $companyId);
                      });
                });
            })
            ->with(['category.localized', 'category.company.localized'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get products count by category for a company
     */
    public function getProductsCount($companyId): JsonResponse
    {
        $company = Company::findOrFail($companyId);
        
        $counts = \App\Models\Category::where(function ($query) use ($companyId) {
            $query->where('category_type', 'system')
                  ->orWhere(function ($subQ) use ($companyId) {
                      $subQ->where('category_type', 'custom')
                           ->where('company_id', $companyId);
                  });
        })
        ->withCount('products')
        ->get()
        ->map(function ($category) {
            return [
                'category_id' => $category->id,
                'category_name' => $category->localized?->name,
                'category_type' => $category->category_type,
                'products_count' => $category->products_count
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $counts
        ]);
    }

    /**
     * Search products by name in company categories
     */
    public function searchProducts(Request $request, $companyId): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $company = Company::findOrFail($companyId);
        
        $products = Product::whereHas('category', function ($query) use ($companyId) {
            $query->where(function ($q) use ($companyId) {
                $q->where('category_type', 'system')
                  ->orWhere(function ($subQ) use ($companyId) {
                      $subQ->where('category_type', 'custom')
                           ->where('company_id', $companyId);
                  });
            });
        })
        ->whereHas('translations', function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->query . '%');
        })
        ->with(['category.localized', 'category.company.localized'])
        ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get products statistics for a company
     */
    public function getStatistics($companyId): JsonResponse
    {
        $company = Company::findOrFail($companyId);
        
        $totalProducts = Product::whereHas('category', function ($query) use ($companyId) {
            $query->where(function ($q) use ($companyId) {
                $q->where('category_type', 'system')
                  ->orWhere(function ($subQ) use ($companyId) {
                      $subQ->where('category_type', 'custom')
                           ->where('company_id', $companyId);
                  });
            });
        })->count();

        $systemCategoryProducts = Product::whereHas('category', function ($query) {
            $query->where('category_type', 'system');
        })->count();

        $customCategoryProducts = Product::whereHas('category', function ($query) use ($companyId) {
            $query->where('category_type', 'custom')
                  ->where('company_id', $companyId);
        })->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_products' => $totalProducts,
                'system_category_products' => $systemCategoryProducts,
                'custom_category_products' => $customCategoryProducts,
                'company_id' => $companyId
            ]
        ]);
    }
}
