<?php

namespace App\Http\Controllers\api\v1\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Supplier\Team\S_CreateTeamMemberRequest;
use App\Http\Requests\v1\Supplier\Team\S_UpdateTeamMemberRequest;
use App\Http\Resources\v1\Supplier\Team\S_TeamResource;
use App\Services\Supplier\Team\S_CreateTeamMemberService;
use App\Services\Supplier\Team\S_UpdateTeamMemberService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class S_TeamController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param S_CreateTeamMemberService $createTeamMember
     * @param S_UpdateTeamMemberService $updateTeamMember
     */
    public function __construct(private S_CreateTeamMemberService $createTeamMember, private S_UpdateTeamMemberService $updateTeamMember)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = userCompany();
        if (!$company) {
            return response()->json(['message' => 'Company not found'], Response::HTTP_NOT_FOUND);
        }
        return S_TeamResource::collection($company?->users()->with(['permissions', 'store'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(S_CreateTeamMemberRequest $request)
    {
        return $this->createTeamMember->create($request->validated());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(S_UpdateTeamMemberRequest $request, string $user_id)
    {
        return $this->updateTeamMember->update($request->validated(), $user_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
