<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJournalEntryRequest;
use App\Http\Resources\JournalEntryResource;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalEntryController extends Controller
{
    /**
     * List journal entries
     *
     * Returns all journal entries within a date range.
     *
     * If no dates are provided, only entries from the current day are returned.
     *
     * @group Journal Entries
     *
     * @authenticated
     *
     * @queryParam start_date date Filter entries from this date. Format: Y-m-d. Example: 2026-07-01
     * @queryParam end_date date Filter entries until this date. Format: Y-m-d. Example: 2026-07-31
     *
     * @response 200 {
     *   "message": "Asientos obtenidos correctamente.",
     *   "data": [
     *     {
     *       "id": 1,
     *       "entry_date": "2026-07-26",
     *       "description": "Purchase of office supplies",
     *       "lines": [
     *         {
     *           "account_id": 4,
     *           "debit": 1500,
     *           "credit": 0
     *         },
     *         {
     *           "account_id": 12,
     *           "debit": 0,
     *           "credit": 1500
     *         }
     *       ]
     *     }
     *   ],
     *   "meta": {
     *     "start_date": "2026-07-01T00:00:00Z",
     *     "end_date": "2026-07-31T23:59:59Z"
     *   }
     * }
     */
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

    /**
     * Create journal entry
     *
     * Creates a new journal entry and all its lines.
     *
     * The entry must:
     * - Contain at least two lines.
     * - Use operable accounts.
     * - Be balanced (total debits must equal total credits).
     *
     * @group Journal Entries
     *
     * @authenticated
     *
     * @response 201 {
     *   "message": "Asiento contable creado correctamente.",
     *   "data": {
     *     "id": 1,
     *     "entry_date": "2026-07-26",
     *     "description": "Purchase of office supplies",
     *     "lines": [
     *       {
     *         "account_id": 4,
     *         "debit": 1500,
     *         "credit": 0
     *       },
     *       {
     *         "account_id": 12,
     *         "debit": 0,
     *         "credit": 1500
     *       }
     *     ]
     *   }
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "lines": [
     *       "El asiento debe estar balanceado: el total de débitos debe ser igual al total de créditos."
     *     ]
     *   }
     * }
     */
    public function store(StoreJournalEntryRequest $request)
    {
        $data = $request->validated();

        $journalEntry = DB::transaction(function () use ($data) {
            $entry = JournalEntry::create([
                'entry_date' => $data['entry_date'],
                'description' => $data['description'] ?? null,
            ]);

            $entry->lines()->createMany($data['lines']);

            return $entry->load('lines.account');
        });

        return response()->json([
            'message' => 'Asiento contable creado correctamente.',
            'data' => new JournalEntryResource($journalEntry),
        ], 201);
    }
}
