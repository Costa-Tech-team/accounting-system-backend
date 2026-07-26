<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JournalEntryResource extends JsonResource
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
            'entry_date' => $this->entry_date->toDateString(),
            'description' => $this->description,
            'created_at' => $this->created_at->toIso8601String(),
            'lines' => $this->whenLoaded('lines', function () {
                return $this->lines->map(function ($line) {
                    return [
                        'id' => $line->id,
                        'journal_entry_id' => $line->journal_entry_id,
                        'account_id' => $line->account_id,
                        'account_name' => $line->account?->name,
                        'debit' => (float) $line->debit,
                        'credit' => (float) $line->credit,
                    ];
                });
            }),
        ];
    }
}
