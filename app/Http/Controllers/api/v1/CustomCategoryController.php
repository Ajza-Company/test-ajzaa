<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\StoreCustomCategoryRequest;
use App\Http\Requests\v1\UpdateCustomCategoryRequest;
use App\Http\Resources\v1\CustomCategoryResource;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CustomCategoryController extends Controller
{
    /**
     * Get custom categories for a specific company
     */
    public function index($companyId): JsonResponse
    {
        $company = Company::findOrFail($companyId);
        
        $categories = Category::forCompany($companyId)
            ->with(['localized', 'translations'])
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => CustomCategoryResource::collection($categories)
        ]);
    }

    /**
     * Store a new custom category for a company
     */
    public function store(StoreCustomCategoryRequest $request, $companyId): JsonResponse
    {
        $company = Company::findOrFail($companyId);
        
        // Check permissions
        if (!Auth::user()->hasPermission('manage_categories') && 
            !Auth::user()->hasPermission('manage_company_categories', $companyId)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $category = Category::create([
            'category_type' => 'custom',
            'company_id' => $companyId,
            'image' => $request->image,
            'is_active' => $request->is_active ?? true,
            'sort_order' => $request->sort_order ?? 0
        ]);

        // Create locale record
        $category->translations()->create([
            'name' => $request->name,
            'locale_id' => 1, // Default locale, you might want to make this dynamic
            'category_id' => $category->id
        ]);

        if ($request->description) {
            // Add description to locale if you have description field
            // $category->translations()->update(['description' => $request->description]);
        }

        $category->load(['localized', 'translations']);

        return response()->json([
            'success' => true,
            'message' => 'Custom category created successfully',
            'data' => new CustomCategoryResource($category)
        ], 201);
    }

    /**
     * Update a custom category
     */
    public function update(UpdateCustomCategoryRequest $request, $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        
        if ($category->category_type !== 'custom') {
            return response()->json([
                'success' => false,
                'message' => 'Category is not a custom category'
            ], 400);
        }

        // Check permissions
        if (!Auth::user()->hasPermission('manage_categories') && 
            !Auth::user()->hasPermission('manage_company_categories', $category->company_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 400);
        }

        $category->update([
            'image' => $request->image,
            'is_active' => $request->is_active,
            'sort_order' => $request->sort_order
        ]);

        // Update locale record
        if ($request->has('name')) {
            $category->translations()->update([
                'name' => $request->name
            ]);
        }

        $category->load(['localized', 'translations']);

        return response()->json([
            'success' => true,
            'message' => 'Custom category updated successfully',
            'data' => new CustomCategoryResource($category)
        ]);
    }

    /**
     * Delete a custom category
     */
    public function destroy($id): JsonResponse
    {
        $category = Category::findOrFail($id);
        
        if ($category->category_type !== 'custom') {
            return response()->json([
                'success' => false,
                'message' => 'Category is not a custom category'
            ], 400);
        }

        // Check permissions
        if (!Auth::user()->hasPermission('manage_categories') && 
            !Auth::user()->hasPermission('manage_company_categories', $category->company_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Check if category has products
        if ($category->products()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with existing products'
            ], 400);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Custom category deleted successfully'
        ]);
    }
}
