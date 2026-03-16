<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed tenant MVP (id=1)
        DB::table('tenants')->insertOrIgnore([
            'id'    => 1,
            'nome'  => 'Propovelz Demo',
            'email' => 'demo@propovelz.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed a few test clientes
        $clientes = [
            ['nome' => 'Empresa Alfa Ltda',      'email' => 'contato@alfa.com.br',  'documento' => '11.222.333/0001-44'],
            ['nome' => 'Beta Soluções S.A.',     'email' => 'vendas@beta.com',       'documento' => '55.666.777/0001-88'],
            ['nome' => 'Carlos Eduardo Souza',   'email' => 'carlos@email.com',      'documento' => '123.456.789-00'],
            ['nome' => 'Delta Tecnologia ME',    'email' => 'admin@delta.tech',      'documento' => null],
        ];

        foreach ($clientes as $c) {
            DB::table('clientes')->insertOrIgnore([
                'tenant_id'  => 1,
                'nome'       => $c['nome'],
                'email'      => $c['email'],
                'documento'  => $c['documento'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
