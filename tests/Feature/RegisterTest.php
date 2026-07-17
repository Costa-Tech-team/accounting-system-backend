<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_registration_form_if_signature_is_valid(): void
    {
        $name = 'Fernando Pessoa';
        $email = 'fernando@example.com';

        $signedUrl = URL::temporarySignedRoute(
            'register.complete',
            now()->addHours(24),
            ['email' => $email, 'name' => $name]
        );

        $response = $this->get($signedUrl);

        $response->assertStatus(200);
        $response->assertViewIs('auth.complete-register');
        $response->assertViewHas('email', $email);
        $response->assertViewHas('name', $name);
    }

    public function test_cannot_view_form_if_url_signature_is_invalid_or_altered(): void
    {
        $this->withMiddleware();

        $signedUrl = URL::temporarySignedRoute(
            'register.complete',
            now()->addHours(24),
            ['email' => 'original@example.com', 'name' => 'Original']
        );

        $alteredUrl = str_replace('original%40example.com', 'hacker%40example.com', $signedUrl);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->getJson($alteredUrl);

        $response->assertStatus(403);
    }

    public function test_cannot_view_form_if_user_already_exists_in_database(): void
    {
        $email = 'fernando@example.com';
        User::factory()->create(['email' => $email]);

        $signedUrl = URL::temporarySignedRoute(
            'register.complete',
            now()->addHours(24),
            ['email' => $email, 'name' => 'Fernando']
        );

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->getJson($signedUrl);

        $response->assertStatus(403);
        $response->assertJson(['status' => 'Esta cuenta ya está registrada.']);
    }


    public function test_can_complete_registration_successfully(): void
    {
        $name = 'Fernando Pessoa';
        $email = 'fernando@example.com';

        $signedUrl = URL::temporarySignedRoute(
            'register.complete.store',
            now()->addHours(24),
            ['email' => $email, 'name' => $name]
        );

        $response = $this->postJson($signedUrl, [
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('message', 'Usuario registrado con exito');

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
        ]);

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_registration_fails_if_password_is_invalid_or_does_not_match(): void
    {
        $signedUrl = URL::temporarySignedRoute(
            'register.complete',
            now()->addHours(24),
            ['email' => 'test@example.com', 'name' => 'Test']
        );

        $response = $this->from($signedUrl)
            ->post($signedUrl, [
                'password' => 'password',
                'password_confirmation' => 'other_password',
            ]);

        $response->assertStatus(302);
        $response->assertRedirect($signedUrl);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_cannot_register_via_post_if_user_already_exists(): void
    {
        $email = 'fernando@example.com';
        User::factory()->create(['email' => $email]);

        $signedUrl = URL::temporarySignedRoute(
            'register.complete.store',
            now()->addHours(24),
            ['email' => $email, 'name' => 'Fernando']
        );

        $response = $this->postJson($signedUrl, [
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'Esta cuenta ya está registrada.']);
    }
}
