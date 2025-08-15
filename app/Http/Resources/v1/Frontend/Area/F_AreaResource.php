<?php

namespace App\Http\Resources\v1\Frontend\Area;

use App\Enums\EncodingMethodsEnum;
use App\Http\Resources\v1\Frontend\State\F_StateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_AreaResource extends JsonResource
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
            'name' => $this->localized?->name,
            'city' => $this->whenLoaded('state', function (){
                return F_StateResource::make($this->state);
            })
        ];
    }
}
