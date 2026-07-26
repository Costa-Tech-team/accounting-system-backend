<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AccountType::insert([
            [
                'name' => 'Activo',
                'normal_balance' => 'debit',
            ],
            [
                'name' => 'Pasivo',
                'normal_balance' => 'credit',
            ],
            [
                'name' => 'Patrimonio Neto',
                'normal_balance' => 'credit',
            ],
            [
                'name' => 'Ingresos',
                'normal_balance' => 'credit',
            ],
            [
                'name' => 'Gastos',
                'normal_balance' => 'debit',
            ],
        ]);
    }
}
