<?php

namespace App\Http\Requests;

use App\Models\Account;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreJournalEntryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'entry_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
            'lines' => ['required', 'array', 'min:2'],
            'lines.*.account_id' => ['required', 'exists:accounts,id'],
            'lines.*.debit' => ['required', 'numeric', 'min:0'],
            'lines.*.credit' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get custom documentation for request body parameters.
     */
    public function bodyParameters(): array
    {
        return [
            'entry_date' => [
                'description' => 'Journal entry date.',
                'example' => '2026-07-26',
            ],

            'description' => [
                'description' => 'Optional description of the journal entry.',
                'example' => 'Purchase of office supplies.',
            ],

            'lines' => [
                'description' => 'List of journal entry lines. At least two lines are required and the total debit must equal the total credit.',
                'example' => [
                    [
                        'account_id' => 4,
                        'debit' => 1500.00,
                        'credit' => 0,
                    ],
                    [
                        'account_id' => 12,
                        'debit' => 0,
                        'credit' => 1500.00,
                    ],
                ],
            ],

            'lines.*.account_id' => [
                'description' => 'ID of an operable account.',
                'example' => 4,
            ],

            'lines.*.debit' => [
                'description' => 'Debit amount. Use 0 if this line is a credit.',
                'example' => 1500.00,
            ],

            'lines.*.credit' => [
                'description' => 'Credit amount. Use 0 if this line is a debit.',
                'example' => 0,
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $lines = $this->input('lines', []);

                if (!is_array($lines) || count($lines) < 2) {
                    return;
                }

                $accountIds = collect($lines)->pluck('account_id')->filter()->unique();
                $accounts = Account::whereIn('id', $accountIds)->get()->keyBy('id');

                foreach ($lines as $index => $line) {
                    $accountId = $line['account_id'] ?? null;
                    $account = $accounts->get($accountId);
                    $debit = (float) ($line['debit'] ?? 0);
                    $credit = (float) ($line['credit'] ?? 0);

                    if ($account && ! $account->is_operable) {
                        $validator->errors()->add(
                            "lines.$index.account_id",
                            'No se puede crear un asiento con una cuenta no operable.'
                        );
                    }

                    if ($debit == 0 && $credit == 0) {
                        $validator->errors()->add(
                            "lines.$index",
                            'La línea debe registrar un valor en débito o en crédito.'
                        );
                    }

                    if ($debit > 0 && $credit > 0) {
                        $validator->errors()->add(
                            "lines.$index",
                            'Una misma línea no puede registrar tanto débito como crédito.'
                        );
                    }
                }

                $debitTotal = collect($lines)->sum(fn($line) => (float) ($line['debit'] ?? 0));
                $creditTotal = collect($lines)->sum(fn($line) => (float) ($line['credit'] ?? 0));

                if (round($debitTotal, 2) !== round($creditTotal, 2)) {
                    $validator->errors()->add(
                        'lines',
                        'El asiento debe estar balanceado: el total de débitos debe ser igual al total de créditos.'
                    );
                }
            },
        ];
    }
}
