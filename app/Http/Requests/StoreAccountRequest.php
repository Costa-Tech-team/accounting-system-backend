<?php

namespace App\Http\Requests;

use App\Models\Account;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreAccountRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:accounts,code',
            'account_type_id' => 'required|exists:account_types,id',
            'parent_id' => 'nullable|exists:accounts,id',
            'is_operable' => 'required|boolean',
            'is_active' => 'required|boolean',
        ];
    }


    public function after(): array
    {
        return [
            function (Validator $validator) {

                if ($this->parent_id) {
                    $parent = Account::find($this->parent_id);

                    if ($parent && $parent->is_operable) {
                        $validator->errors()->add(
                            'parent_id',
                            'No se puede crear una cuenta hija de una cuenta operable.'
                        );
                    }

                    if ($parent && $parent->account_type_id != $this->account_type_id) {
                        $validator->errors()->add(
                            'account_type_id',
                            'La cuenta hija debe pertenecer al mismo tipo que la cuenta padre.'
                        );
                    }

                    if ($parent && !str_starts_with($this->code, $parent->code . '.')) {
                        $validator->errors()->add(
                            'code',
                            'El código no coincide con la jerarquía de la cuenta padre.'
                        );
                    }
                }
            }
        ];
    }
}
