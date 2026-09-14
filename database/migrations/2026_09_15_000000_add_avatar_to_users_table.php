<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Foto de perfil do usuário do painel.
 *
 * Guarda o caminho relativo dentro de storage/app (ex.: avatars/1-a1b2c3.jpg),
 * e NÃO uma URL: o arquivo fica fora da raiz pública de propósito, para nunca
 * ser executado como script. A imagem é servida pela rota perfil.avatar.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
    }
};
