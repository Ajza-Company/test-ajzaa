<?php

namespace App\Http\Controllers\api\v1\Admin;

use App\Http\Resources\v1\General\RepChat\G_RepChatMessageResource;
use App\Http\Resources\v1\Supplier\RepOrder\S_ShortRepOrderResource;
use App\Http\Controllers\Controller;
use App\Models\RepOrder;
use App\Repositories\Supplier\RepOrder\Find\S_FindRepOrderInterface;
use Illuminate\Http\Request;

class F_RepOrderController extends Controller
{
    /**
     * Create a new instance.
     * @param S_FindRepOrderInterface $findOrder
     */
    public function __construct(
        private S_FindRepOrderInterface $findOrder)
    {

    }

    public function index(Request $request) {
        return S_ShortRepOrderResource::collection(
            RepOrder::with(['repChat','address'])->filter($request)->latest()->adaptivePaginate()
        );
    }

    public function repChat(string $id)
    {
        $repOrder = $this->findOrder->find(decodeString($id));

        return G_RepChatMessageResource::collection(
            $repOrder?->repChat?->messages()->with(['sender','chat','chat.user1','chat.user2'])->get()
        );
    }
}
