<?php

namespace App\Http\Controllers\api\v1\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Supplier\Permission\S_ShortPermissionResource;
use App\Repositories\Supplier\Permission\Fetch\S_FetchPermissionInterface;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class S_PermissionController extends Controller
{
    /**
     * class constructor.
     *
     * @return void
     */
    public function __construct(private S_FetchPermissionInterface $fetchPermission)
    {
        // ...
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return S_ShortPermissionResource::collection($this->fetchPermission->fetch());
    }
}
