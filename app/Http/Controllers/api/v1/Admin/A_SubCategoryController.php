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
use Illuminate\Http\Request;

class A_SubCategoryController extends Controller
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
    public function index(string $category_id)
    {
        return F_CategoryResource::collection($this->fetchCategory->fetch(paginate: false, with: ['variants','parent'] , data: ['parent_id' => decodeString($category_id)]));
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
        return F_ShortCategoryResource::make($Category->load('parent'));
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
}
