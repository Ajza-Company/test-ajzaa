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
}
