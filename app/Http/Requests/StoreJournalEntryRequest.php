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

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $lines = $this->input('lines', []);

                if (!is_array($lines) || count($lines) < 2) {
                    return;
                }

                foreach ($lines as $index => $line) {
                    $account = Account::find($line['account_id'] ?? null);

                    if ($account && ! $account->is_operable) {
                        $validator->errors()->add(
                            "lines.$index.account_id",
                            'No se puede crear un asiento con una cuenta no operable.'
                        );
                    }
                }

                $debitTotal = collect($lines)->sum(fn ($line) => (float) ($line['debit'] ?? 0));
                $creditTotal = collect($lines)->sum(fn ($line) => (float) ($line['credit'] ?? 0));

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
