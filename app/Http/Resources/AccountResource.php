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
            'account_type' => $this->account_type_id,
            'parent_id' => $this->parent_id,
            'is_active' => $this->is_active,
            'is_operable' => $this->is_operable,

            'children' => AccountResource::collection(
                $this->whenLoaded('childrenRecursive')
            ),
        ];
    }
}
