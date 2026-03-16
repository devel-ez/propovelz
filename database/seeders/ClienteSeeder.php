<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = [
            ['nome' => 'Acme Tecnologia Ltda',    'email' => 'contato@acme.com.br',       'documento' => '12.345.678/0001-90'],
            ['nome' => 'Indústria Vieira S.A.',   'email' => 'financeiro@vieira.com.br',   'documento' => '98.765.432/0001-11'],
            ['nome' => 'Startup Nexus',           'email' => 'hello@nexus.io',             'documento' => '56.789.012/0001-33'],
            ['nome' => 'Maria Silva',              'email' => 'maria.silva@gmail.com',      'documento' => '123.456.789-00'],
            ['nome' => 'Consultoria Bergmann',    'email' => 'bergmann@bergmann.com.br',   'documento' => '45.678.901/0001-55'],
        ];

        foreach ($clientes as $data) {
            Cliente::firstOrCreate(
                ['email' => $data['email'], 'tenant_id' => 1],
                array_merge($data, ['tenant_id' => 1])
            );
        }
    }
}
