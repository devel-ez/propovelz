<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ativo/inativo no projeto.
     *
     * Inativar o cliente desce para os projetos dele: os projetos ficam
     * marcados, nao apagados. Reativar o cliente sobe de volta.
     *
     * O campo existe no projeto (e nao e so herdado do cliente) para que
     * mais adiante dê para inativar um projeto sozinho, sem mexer no cliente.
     */
    public function up(): void
    {
        Schema::table('projetos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('status')->index();
        });
    }

    public function down(): void
    {
        Schema::table('projetos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
    }
};
