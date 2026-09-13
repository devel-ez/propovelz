<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Cria o tenant padrao usado pelo painel.
 *
 * Por que isto existe separado do DatabaseSeeder:
 * o DatabaseSeeder cria o tenant E quatro clientes de demonstracao. Em
 * producao a gente quer o tenant (a coluna clientes.tenant_id tem chave
 * estrangeira para tenants, entao sem ele nenhum cliente pode ser salvo),
 * mas nao quer clientes falsos.
 *
 * Rodar:  php artisan db:seed --class=TenantSeeder
 * Rodar de novo nao duplica nem sobrescreve.
 */
class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $id = (int) env('DEFAULT_TENANT_ID', 1);

        $existe = DB::table('tenants')->where('id', $id)->exists();

        if ($existe) {
            $this->command->info("Tenant {$id} ja existe - nada a fazer.");
            return;
        }

        DB::table('tenants')->insert([
            'id'         => $id,
            'nome'       => env('DEFAULT_TENANT_NOME', 'Crie Sites Pro'),
            'email'      => env('DEFAULT_TENANT_EMAIL'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info("Tenant {$id} criado.");
    }
}
