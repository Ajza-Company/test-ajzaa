<?php

namespace App\Http\Resources\v1\Frontend\Wallet;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_ShortWalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => encodeString($this->id),
            'type' => $this->type,
            'amount' => $this->amount,
            'description' => $this->description,
            'reference' => $this->reference,
            'status' => $this->status,
            'date' => $this->created_at,
        ];
    }
}
