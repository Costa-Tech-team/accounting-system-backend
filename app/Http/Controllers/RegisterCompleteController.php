<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterCompleteController extends Controller
{
    /**
     * Muestra el formulario de registro (GET)
     */
    public function showForm(Request $request)
    {
        $email = $request->query('email');
        $name = $request->query('name');

        return view('auth.complete-register', compact('email', 'name'));
    }

    /**
     * Procesa la creación del usuario (POST)
     */
    public function store(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $email = $request->query('email');
        $name = $request->query('name');

        if (User::where('email', $email)->exists()) {
            return response()->json(['status'=> 'Esta cuenta ya está registrada.']);
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        return response()->json([
            "message" => "Usuario registrado con exito",
            "user" => $user
        ]);
    }
}
