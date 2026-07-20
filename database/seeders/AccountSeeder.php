<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activo = AccountType::where('name', 'Activo')->first();
        $pasivo = AccountType::where('name', 'Pasivo')->first();
        $patrimonio = AccountType::where('name', 'Patrimonio Neto')->first();
        $ingresos = AccountType::where('name', 'Ingresos')->first();
        $gastos = AccountType::where('name', 'Gastos')->first();

        // ACTIVO

        $activoRoot = Account::create([
            'account_type_id' => $activo->id,
            'code' => '1',
            'name' => 'Activo',
            'is_active' => true,
            'is_operable' => false,
        ]);

        $activoCorriente = Account::create([
            'account_type_id' => $activo->id,
            'parent_id' => $activoRoot->id,
            'code' => '1.1',
            'name' => 'Activo Corriente',
            'is_active' => true,
            'is_operable' => false,
        ]);

        Account::create([
            'account_type_id' => $activo->id,
            'parent_id' => $activoCorriente->id,
            'code' => '1.1.1',
            'name' => 'Caja',
            'is_active' => true,
            'is_operable' => true,
        ]);

        Account::create([
            'account_type_id' => $activo->id,
            'parent_id' => $activoCorriente->id,
            'code' => '1.1.2',
            'name' => 'Banco',
            'is_active' => true,
            'is_operable' => true,
        ]);

        // PASIVO

        $pasivoRoot = Account::create([
            'account_type_id' => $pasivo->id,
            'code' => '2',
            'name' => 'Pasivo',
            'is_active' => true,
            'is_operable' => false,
        ]);

        Account::create([
            'account_type_id' => $pasivo->id,
            'parent_id' => $pasivoRoot->id,
            'code' => '2.1',
            'name' => 'Proveedores',
            'is_active' => true,
            'is_operable' => true,
        ]);

        // PATRIMONIO NETO

        $patrimonioRoot = Account::create([
            'account_type_id' => $patrimonio->id,
            'code' => '3',
            'name' => 'Patrimonio Neto',
            'is_active' => true,
            'is_operable' => false,
        ]);

        Account::create([
            'account_type_id' => $patrimonio->id,
            'parent_id' => $patrimonioRoot->id,
            'code' => '3.1',
            'name' => 'Capital',
            'is_active' => true,
            'is_operable' => true,
        ]);

        // INGRESOS

        $ingresosRoot = Account::create([
            'account_type_id' => $ingresos->id,
            'code' => '4',
            'name' => 'Ingresos',
            'is_active' => true,
            'is_operable' => false,
        ]);

        Account::create([
            'account_type_id' => $ingresos->id,
            'parent_id' => $ingresosRoot->id,
            'code' => '4.1',
            'name' => 'Ventas',
            'is_active' => true,
            'is_operable' => true,
        ]);

        // GASTOS

        $gastosRoot = Account::create([
            'account_type_id' => $gastos->id,
            'code' => '5',
            'name' => 'Gastos',
            'is_active' => true,
            'is_operable' => false,
        ]);

        Account::create([
            'account_type_id' => $gastos->id,
            'parent_id' => $gastosRoot->id,
            'code' => '5.1',
            'name' => 'Servicios',
            'is_active' => true,
            'is_operable' => true,
        ]);

        Account::create([
            'account_type_id' => $gastos->id,
            'parent_id' => $gastosRoot->id,
            'code' => '5.2',
            'name' => 'Sueldos',
            'is_active' => true,
            'is_operable' => true,
        ]);
    }
}
