<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('propostas', function (Blueprint $table) {
            $table->longText('conteudo')->nullable()->after('titulo');
            $table->string('token_publico', 64)->unique()->nullable()->after('data_validade');
            $table->timestamp('assinado_em')->nullable()->after('token_publico');
            $table->string('assinado_por_nome')->nullable()->after('assinado_em');
        });
    }

    public function down(): void
    {
        Schema::table('propostas', function (Blueprint $table) {
            $table->dropColumn(['conteudo', 'token_publico', 'assinado_em', 'assinado_por_nome']);
        });
    }
};
