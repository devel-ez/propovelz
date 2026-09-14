<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Marca o cliente como ativo ou inativo.
     *
     * Serve para quem desistiu de manter o site: o cliente continua no
     * sistema, com propostas e faturas, mas a hospedagem e o dominio dele
     * deixam de aparecer como vencimento no dashboard.
     *
     * Inativar nunca apaga nada - e reversivel a qualquer momento.
     */
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('documento')->index();
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
    }
};
