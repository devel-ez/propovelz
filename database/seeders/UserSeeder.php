<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Cria (ou atualiza) o usuário administrador do painel.
 *
 * Antes de rodar, defina no .env:
 *   ADMIN_EMAIL=seu@email.com
 *   ADMIN_PASSWORD=uma-senha-forte
 *
 * Depois:  php artisan db:seed --class=UserSeeder
 *
 * Rodar de novo apenas troca a senha — não duplica o usuário.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $senha = env('ADMIN_PASSWORD');

        if (! $email || ! $senha) {
            $this->command->error('Defina ADMIN_EMAIL e ADMIN_PASSWORD no .env antes de rodar.');
            return;
        }

        if (strlen($senha) < 10) {
            $this->command->error('Use uma senha com pelo menos 10 caracteres.');
            return;
        }

        $usuario = User::updateOrCreate(
            ['email' => $email],
            [
                'name'     => env('ADMIN_NAME', 'Administrador'),
                'password' => Hash::make($senha),
            ]
        );

        $this->command->info("Usuario administrador pronto: {$usuario->email}");
    }
}
