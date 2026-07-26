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
        $activo = AccountType::where('name', 'Activo')->firstOrFail();
        $pasivo = AccountType::where('name', 'Pasivo')->firstOrFail();
        $patrimonio = AccountType::where('name', 'Patrimonio Neto')->firstOrFail();
        $ingresos = AccountType::where('name', 'Ingresos')->firstOrFail();
        $gastos = AccountType::where('name', 'Gastos')->firstOrFail();

        $createAccount = function (string $code, string $name, AccountType $accountType, ?Account $parent = null, bool $isOperable = false): Account {
            return Account::updateOrCreate(
                ['code' => $code],
                [
                    'account_type_id' => $accountType->id,
                    'parent_id' => $parent?->id,
                    'name' => $name,
                    'is_active' => true,
                    'is_operable' => $isOperable,
                ]
            );
        };

        $activoRoot = $createAccount('1', 'Activo', $activo);
        $activoCorriente = $createAccount('1.1', 'Activo Corriente', $activo, $activoRoot);
        $activoNoCorriente = $createAccount('1.2', 'Activo No Corriente', $activo, $activoRoot);

        $createAccount('1.1.1', 'Caja', $activo, $activoCorriente, true);
        $createAccount('1.1.2', 'Bancos', $activo, $activoCorriente, true);
        $createAccount('1.1.3', 'Cuentas por Cobrar', $activo, $activoCorriente, true);
        $createAccount('1.1.4', 'Inventarios', $activo, $activoCorriente, true);

        $createAccount('1.2.1', 'Equipos y Mobiliario', $activo, $activoNoCorriente, true);
        $createAccount('1.2.2', 'Depreciación Acumulada', $activo, $activoNoCorriente, true);

        $pasivoRoot = $createAccount('2', 'Pasivo', $pasivo);
        $pasivoCorriente = $createAccount('2.1', 'Pasivo Corriente', $pasivo, $pasivoRoot);

        $createAccount('2.1.1', 'Proveedores', $pasivo, $pasivoCorriente, true);
        $createAccount('2.1.2', 'Cuentas por Pagar', $pasivo, $pasivoCorriente, true);
        $createAccount('2.1.3', 'Impuestos por Pagar', $pasivo, $pasivoCorriente, true);

        $patrimonioRoot = $createAccount('3', 'Patrimonio Neto', $patrimonio);
        $createAccount('3.1', 'Capital', $patrimonio, $patrimonioRoot, true);
        $createAccount('3.2', 'Utilidades Retenidas', $patrimonio, $patrimonioRoot, true);

        $ingresosRoot = $createAccount('4', 'Ingresos', $ingresos);
        $createAccount('4.1', 'Ventas', $ingresos, $ingresosRoot, true);
        $createAccount('4.2', 'Servicios', $ingresos, $ingresosRoot, true);
        $createAccount('4.3', 'Otros Ingresos', $ingresos, $ingresosRoot, true);

        $gastosRoot = $createAccount('5', 'Gastos', $gastos);
        $createAccount('5.1', 'Costo de Ventas', $gastos, $gastosRoot, true);
        $createAccount('5.2', 'Sueldos y Salarios', $gastos, $gastosRoot, true);
        $createAccount('5.3', 'Alquileres', $gastos, $gastosRoot, true);
        $createAccount('5.4', 'Servicios Públicos', $gastos, $gastosRoot, true);
        $createAccount('5.5', 'Publicidad y Marketing', $gastos, $gastosRoot, true);
        $createAccount('5.6', 'Gastos de Administración', $gastos, $gastosRoot, true);
    }
}
