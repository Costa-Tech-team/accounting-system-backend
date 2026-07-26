<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\AccountType;
use App\Models\JournalEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JournalEntryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_journal_entry(): void
    {
        $user = User::factory()->create();

        $debitType = AccountType::create([
            'name' => 'Activo',
            'normal_balance' => 'debit',
        ]);

        $creditType = AccountType::create([
            'name' => 'Pasivo',
            'normal_balance' => 'credit',
        ]);

        $debitAccount = Account::create([
            'account_type_id' => $debitType->id,
            'code' => '1.1.1',
            'name' => 'Caja',
            'is_active' => true,
            'is_operable' => true,
        ]);

        $creditAccount = Account::create([
            'account_type_id' => $creditType->id,
            'code' => '2.1.1',
            'name' => 'Proveedores',
            'is_active' => true,
            'is_operable' => true,
        ]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/journal-entry', [
            'entry_date' => '2026-07-26',
            'description' => 'Venta de prueba',
            'lines' => [
                [
                    'account_id' => $debitAccount->id,
                    'debit' => 100.50,
                    'credit' => 0,
                ],
                [
                    'account_id' => $creditAccount->id,
                    'debit' => 0,
                    'credit' => 100.50,
                ],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.description', 'Venta de prueba');
        $response->assertJsonCount(2, 'data.lines');
        $response->assertJsonPath('data.lines.0.journal_entry_id', $response->json('data.id'));
        $response->assertJsonPath('data.lines.1.journal_entry_id', $response->json('data.id'));

        $this->assertDatabaseHas('journal_entries', [
            'description' => 'Venta de prueba',
        ]);

        $this->assertDatabaseHas('journal_entry_lines', [
            'account_id' => $debitAccount->id,
            'debit' => '100.50',
            'credit' => '0.00',
        ]);
    }

    public function test_user_can_list_journal_entries_filtered_by_date(): void
    {
        $user = User::factory()->create();

        $debitType = AccountType::create([
            'name' => 'Activo',
            'normal_balance' => 'debit',
        ]);

        $creditType = AccountType::create([
            'name' => 'Pasivo',
            'normal_balance' => 'credit',
        ]);

        $debitAccount = Account::create([
            'account_type_id' => $debitType->id,
            'code' => '1.1.2',
            'name' => 'Banco',
            'is_active' => true,
            'is_operable' => true,
        ]);

        $creditAccount = Account::create([
            'account_type_id' => $creditType->id,
            'code' => '2.1.2',
            'name' => 'Cuentas por Pagar',
            'is_active' => true,
            'is_operable' => true,
        ]);

        JournalEntry::create([
            'entry_date' => now()->toDateString(),
            'description' => 'Asiento de hoy',
        ])->lines()->createMany([
            [
                'account_id' => $debitAccount->id,
                'debit' => 50,
                'credit' => 0,
            ],
            [
                'account_id' => $creditAccount->id,
                'debit' => 0,
                'credit' => 50,
            ],
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/journal-entry?start_date=' . now()->toDateString() . '&end_date=' . now()->toDateString());

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.description', 'Asiento de hoy');
    }
}
