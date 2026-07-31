<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginFormRequest extends FormRequest
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
     *
     * @bodyParam email string required The user's email. Example: john@example.com
     * @bodyParam password password required The user's password. Example: secret123
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Formato de correo no válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'email' => [
                'description' => 'User email address.',
                'example' => 'john@example.com',
            ],
            'password' => [
                'description' => 'User password.',
                'type' => "password",
                'example' => 'password',
            ],
        ];
    }
}
