<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\AccountType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * A feature test for get account types.
     */
    public function test_get_account_types(): void
    {
        $data = [
            ['name' => 'Activo', 'normal_balance' => 'debit'],
            ['name' => 'Pasivo', 'normal_balance' => 'credit'],
            ['name' => 'Patrimonio Neto', 'normal_balance' => 'credit'],
            ['name' => 'Ingresos', 'normal_balance' => 'credit'],
            ['name' => 'Gastos', 'normal_balance' => 'debit'],
        ];

        foreach ($data as $type) {
            AccountType::create($type);
        }

        $response = $this->getJson('/api/v1/account-types');

        $response->assertStatus(200)
            ->assertJsonCount(5)
            ->assertJson($data);
    }

    /** @test */
    public function test_it_creates_a_new_account_successfully(): void
    {
        $accountType = AccountType::factory()->create();

        $payload = [
            'name' => 'Caja Chica',
            'code' => '1.1.1',
            'account_type_id' => $accountType->id,
            'parent_id' => null,
            'is_operable' => true,
            'is_active' => true,
        ];

        $response = $this->postJson('/api/v1/account', $payload);

        $response->assertCreated()
            ->assertJsonPath('meta.message', 'Account created successfully.')
            ->assertJsonPath('meta.status', 201)
            ->assertJsonPath('data.name', 'Caja Chica');

        $this->assertDatabaseHas('accounts', [
            'code' => '1.1.1',
            'name' => 'Caja Chica',
        ]);
    }

    /** @test */
    public function test_it_fails_validation_when_required_fields_are_missing(): void
    {
        $response = $this->postJson('/api/v1/account', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'code',
                'account_type_id',
                'is_operable',
                'is_active'
            ]);
    }

    /** @test */
    public function test_it_fails_validation_if_account_code_is_not_unique(): void
    {
        $accountType = AccountType::factory()->create();
        Account::factory()->create(['code' => '1.1.1']);

        $payload = [
            'name' => 'Otra Cuenta',
            'code' => '1.1.1', // Código duplicado
            'account_type_id' => $accountType->id,
            'parent_id' => null,
            'is_operable' => true,
            'is_active' => true,
        ];

        $response = $this->postJson('/api/v1/account', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['code']);
    }

    /** @test */
    public function test_it_fails_if_parent_account_is_operable(): void
    {
        $accountType = AccountType::factory()->create();

        // Padre que SÍ es operable (no debería poder tener hijas)
        $parentAccount = Account::factory()->create([
            'code' => '1.1',
            'is_operable' => true,
            'account_type_id' => $accountType->id,
        ]);

        $payload = [
            'name' => 'Cuenta Hija Invalida',
            'code' => '1.1.1',
            'account_type_id' => $accountType->id,
            'parent_id' => $parentAccount->id,
            'is_operable' => true,
            'is_active' => true,
        ];

        $response = $this->postJson('/api/v1/account', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['parent_id']);
    }

    /** @test */
    public function test_it_fails_if_child_account_type_does_not_match_parent_type(): void
    {
        $typeActivo = AccountType::factory()->create();
        $typePasivo = AccountType::factory()->create();

        $parentAccount = Account::factory()->create([
            'code' => '1.1',
            'is_operable' => false,
            'account_type_id' => $typeActivo->id,
        ]);

        $payload = [
            'name' => 'Cuenta Hija Invalida',
            'code' => '1.1.1',
            'account_type_id' => $typePasivo->id, // Distinto al padre
            'parent_id' => $parentAccount->id,
            'is_operable' => true,
            'is_active' => true,
        ];

        $response = $this->postJson('/api/v1/account', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['account_type_id']);
    }

    /** @test */
    public function test_it_fails_if_child_code_does_not_start_with_parent_code(): void
    {
        $accountType = AccountType::factory()->create();

        $parentAccount = Account::factory()->create([
            'code' => '1.1',
            'is_operable' => false,
            'account_type_id' => $accountType->id,
        ]);

        $payload = [
            'name' => 'Cuenta Hija',
            'code' => '2.1.1', // No empieza con '1.1.'
            'account_type_id' => $accountType->id,
            'parent_id' => $parentAccount->id,
            'is_operable' => true,
            'is_active' => true,
        ];

        $response = $this->postJson('/api/v1/account', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['code']);
    }
}
