<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJournalEntryRequest;
use App\Http\Resources\JournalEntryResource;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalEntryController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $startDate = $startDate ? now()->parse($startDate)->startOfDay() : now()->startOfDay();
        $endDate = $endDate ? now()->parse($endDate)->endOfDay() : now()->endOfDay();

        $query = JournalEntry::query()
            ->with('lines.account')
            ->whereDate('entry_date', '>=', $startDate->toDateString())
            ->whereDate('entry_date', '<=', $endDate->toDateString())
            ->orderBy('entry_date', 'desc')
            ->orderBy('id', 'desc');

        $entries = $query->get();

        return response()->json([
            'message' => 'Asientos obtenidos correctamente.',
            'data' => JournalEntryResource::collection($entries),
            'meta' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }

    public function store(StoreJournalEntryRequest $request)
    {
        $data = $request->validated();

        $journalEntry = DB::transaction(function () use ($data) {
            $entry = JournalEntry::create([
                'entry_date' => $data['entry_date'],
                'description' => $data['description'] ?? null,
            ]);

            foreach ($data['lines'] as $line) {
                $entry->lines()->create([
                    'account_id' => $line['account_id'],
                    'debit' => $line['debit'],
                    'credit' => $line['credit'],
                ]);
            }

            return $entry->load('lines.account');
        });

        return response()->json([
            'message' => 'Asiento contable creado correctamente.',
            'data' => new JournalEntryResource($journalEntry),
        ], 201);
    }
}
