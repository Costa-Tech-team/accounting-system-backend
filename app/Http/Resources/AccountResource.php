<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'account_type' => $this->whenLoaded('accountType', function () {
                return [
                    'id' => $this->accountType->id,
                    'name' => $this->accountType->name,
                    'normal_balance' => $this->accountType->normal_balance,
                ];
            }),
            'parent_id' => $this->parent_id,
            'is_active' => $this->is_active,

            'children' => AccountResource::collection(
                $this->whenLoaded('childrenRecursive')
            ),
        ];
    }
}
