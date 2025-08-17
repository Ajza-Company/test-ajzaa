<?php

namespace App\Http\Controllers\api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Frontend\Category\F_CategoryResource;
use App\Http\Resources\v1\Frontend\Category\F_ShortCategoryResource;
use App\Repositories\Frontend\Category\Fetch\F_FetchCategoryInterface;
use App\Http\Requests\v1\Admin\Category\CreateCategoryRequest;
use App\Http\Requests\v1\Admin\Category\UpdateCategoryRequest;
use App\Services\Admin\Category\A_CreateCategoryService;
use App\Services\Admin\Category\A_DeleteCategoryService;
use App\Services\Admin\Category\A_UpdateCategoryService;
use App\Repositories\Frontend\Category\Find\F_FindCategoryInterface;
use App\Models\Category;
use Illuminate\Http\Request;

class A_CategoryController extends Controller
{
    /**
     *
     * @param F_FindCategoryInterface $findCategory
     * @param F_FetchCategoryInterface $fetchCategory
     * @param A_CreateCategoryService $createCategory
     * @param A_UpdateCategoryService $updateCategory
     * @param A_DeleteCategoryService $deleteCategory
     */
    public function __construct(private F_FindCategoryInterface $findCategory,
                                private F_FetchCategoryInterface $fetchCategory,
                                private A_CreateCategoryService $createCategory,
                                private A_UpdateCategoryService $updateCategory,
                                private A_DeleteCategoryService $deleteCategory
                            )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::where('parent_id', null)
            ->when($request->search, function ($query, $search) {
                return $query->whereHas('localized', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->ordered() // استخدام الـ scope الجديد
            ->with(['children' => function($query) {
                $query->ordered(); // ترتيب الأقسام الفرعية أيضاً
            }, 'variants', 'localized'])
            ->get();

        return F_CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request)
    {
       return $this->createCategory->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Category = $this->findCategory->find(decodeString($id));
        return F_ShortCategoryResource::make($Category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        $Category = $this->findCategory->find(decodeString($id));

        return $this->updateCategory->update($request->validated(),$Category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Category = $this->findCategory->find(decodeString($id));

        return $this->deleteCategory->delete($Category);
    }

    /**
     * Update categories order
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->categories as $categoryData) {
            Category::where('id', $categoryData['id'])
                ->update(['sort_order' => $categoryData['sort_order']]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Order updated successfully'
        ]);
    }

    /**
     * Get subcategories for a parent category
     */
    public function getSubCategories($parentId)
    {
        $subCategories = Category::where('parent_id', decodeString($parentId))
            ->ordered()
            ->with(['localized', 'variants'])
            ->get();

        return F_CategoryResource::collection($subCategories);
    }
}
