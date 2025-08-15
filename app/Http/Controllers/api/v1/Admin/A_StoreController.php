<?php

namespace App\Http\Controllers\api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Admin\Store\A_UpdateStoreRequest;
use App\Http\Resources\v1\Admin\Company\A_ShortCompanyResource;
use App\Models\Store;
use App\Repositories\Admin\Store\Find\A_FindStoreInterface;
use Illuminate\Http\Request;

class A_StoreController extends Controller
{
    /**
     *
     * @param A_FindStoreInterface $findStore
     */
    public function __construct(private A_FindStoreInterface $findStore)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(A_UpdateStoreRequest $request, string $id)
    {
        $store = $this->findStore->find(decodeString($id));
        return $store->update($request->validated());
    }

    public function active(string $id)
    {
        $id = decodeString($id);

        $store = Store::find($id);
        $store->is_active = !$store->is_active;
        $store->save();
        return $store;
    }
}
