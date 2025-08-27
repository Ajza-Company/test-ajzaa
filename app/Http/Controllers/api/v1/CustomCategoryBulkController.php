<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CustomCategoryResource;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomCategoryBulkController extends Controller
{
    /**
     * Bulk update custom categories order
     */
    public function updateOrder(Request $request, $companyId): JsonResponse
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.sort_order' => 'required|integer|min:0'
        ]);

        $company = Company::findOrFail($companyId);
        
        foreach ($request->categories as $categoryData) {
            $category = Category::find($categoryData['id']);
            
            if ($category && $category->company_id == $companyId && $category->category_type === 'custom') {
                $category->update(['sort_order' => $categoryData['sort_order']]);
            }
        }

        $categories = Category::forCompany($companyId)
            ->with(['localized', 'translations'])
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Categories order updated successfully',
            'data' => CustomCategoryResource::collection($categories)
        ]);
    }

    /**
     * Bulk activate/deactivate custom categories
     */
    public function updateStatus(Request $request, $companyId): JsonResponse
    {
        $request->validate([
            'category_ids' => 'required|array',
            'category_ids.*' => 'required|exists:categories,id',
            'is_active' => 'required|boolean'
        ]);

        $company = Company::findOrFail($companyId);
        
        $updatedCount = Category::whereIn('id', $request->category_ids)
            ->where('company_id', $companyId)
            ->where('category_type', 'custom')
            ->update(['is_active' => $request->is_active]);

        return response()->json([
            'success' => true,
            'message' => "{$updatedCount} categories updated successfully",
            'data' => [
                'updated_count' => $updatedCount,
                'is_active' => $request->is_active
            ]
        ]);
    }

    /**
     * Get custom categories statistics for a company
     */
    public function statistics($companyId): JsonResponse
    {
        $company = Company::findOrFail($companyId);
        
        $totalCategories = Category::forCompany($companyId)->count();
        $activeCategories = Category::forCompany($companyId)->where('is_active', true)->count();
        $inactiveCategories = Category::forCompany($companyId)->where('is_active', false)->count();
        
        $categoriesWithProducts = Category::forCompany($companyId)
            ->whereHas('products')
            ->count();
        
        $emptyCategories = $totalCategories - $categoriesWithProducts;

        return response()->json([
            'success' => true,
            'data' => [
                'total_categories' => $totalCategories,
                'active_categories' => $activeCategories,
                'inactive_categories' => $inactiveCategories,
                'categories_with_products' => $categoriesWithProducts,
                'empty_categories' => $emptyCategories
            ]
        ]);
    }

    /**
     * Search custom categories by name
     */
    public function search(Request $request, $companyId): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $company = Company::findOrFail($companyId);
        
        $categories = Category::forCompany($companyId)
            ->whereHas('translations', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->query . '%');
            })
            ->with(['localized', 'translations'])
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => CustomCategoryResource::collection($categories)
        ]);
    }
}
