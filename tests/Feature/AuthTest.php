<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verify that an existing user can log in successfully.
     *
     * This test ensures that when valid credentials are sent via a POST request,
     * the system authenticates the user, returns an HTTP 200 status code, and
     * responds with the correct JSON structure containing the Sanctum access token.
     *
     * @return void
     */
    public function test_login_user(): void
    {
        // --- Arrange ---
        $user = User::factory()->create([
            'name' => 'Fernando Pessoa',
            'email' => 'fernandomatiaspessoa471@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // --- Act ---
        $response = $this->postJson('/login', [
            'email' => 'fernandomatiaspessoa471@gmail.com',
            'password' => 'password',
        ]);

        // --- Assert ---
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'user' => [
                'id',
                'email',
            ]
        ]);
    }

    /**
     * Verify that login fails when invalid credentials are provided.
     *
     * This test checks that an authentication attempt with an incorrect password
     * is rejected by the system, returning an HTTP 401 Unauthorized status
     * and the expected error message.
     *
     * @return void
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        // --- Arrange ---
        $user = User::factory()->create([
            'name' => 'Fernando Pessoa',
            'email' => 'fernandomatiaspessoa471@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // --- Act ---
        $response = $this->postJson('/login', [
            'email' => 'fernandomatiaspessoa471@gmail.com',
            'password' => 'password_incorrecto',
        ]);

        // --- Assert ---
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Las credenciales no coinciden con nuestros registros.'
        ]);
    }

    /**
     * Verify that an authenticated user can log out successfully.
     *
     * This test simulates a successful login flow to retrieve a Bearer token,
     * sends a POST request to the logout endpoint with the authorization header,
     * and asserts that the server revokes the token and responds with an HTTP 200.
     *
     * @return void
     */
    public function test_logout_user(): void
    {
        // --- Arrange ---
        $user = User::factory()->create([
            'name' => 'Fernando Pessoa',
            'email' => 'fernandomatiaspessoa471@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $loginResponse = $this->postJson('/login', [
            'email' => 'fernandomatiaspessoa471@gmail.com',
            'password' => 'password',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('access_token');

        // --- Act ---
        $logoutResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('api/auth/logout');

        // --- Assert ---
        $logoutResponse->assertStatus(200);
        $logoutResponse->assertJson([
            'message' => 'Sesión cerrada con éxito. El token ha sido revocado.'
        ]);
    }
}
