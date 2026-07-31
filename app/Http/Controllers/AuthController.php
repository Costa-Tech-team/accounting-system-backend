<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Login
     *
     * Authenticate a user using their email and password.
     *
     * On success, a new Sanctum access token is generated and returned along
     * with the authenticated user's information.
     *
     * @group Authentication With Token
     *
     * @unauthenticated
     *
     * @bodyParam email string required The user's email address. Example: john@pepe.com
     * @bodyParam password string required The user's password. Example: password
     *
     * @response 200 {
     *   "access_token": "1|qwertyuiopasdfghjklzxcvbnm",
     *   "token_type": "Bearer",
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@pepe.com"
     *   }
     * }
     *
     * @response 401 {
     *   "message": "Las credenciales no coinciden con nuestros registros."
     * }
     */
    public function login(LoginFormRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $token = $user->createToken('api')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 200);
        }

        return response()->json([
            'message' => 'Las credenciales no coinciden con nuestros registros.'
        ], 401);
    }

    /**
     * Logout
     *
     * Revoke the current access token and end the authenticated session.
     *
     * @group Authentication With Token
     *
     * @authenticated
     *
     * @response 200 {
     *   "message": "Sesión cerrada con éxito. El token ha sido revocado."
     * }
     */
    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }

        return response()->json([
            'message' => 'Sesión cerrada con éxito. El token ha sido revocado.'
        ], 200);
    }

    /**
     * Get authenticated user
     *
     * Returns the information of the currently authenticated user.
     *
     * @group Authentication With Token
     *
     * @authenticated
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "John Doe",
     *   "email": "john@example.com"
     * }
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
