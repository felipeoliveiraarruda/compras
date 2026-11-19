<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pca_items', function (Blueprint $table) {
            $table->id();
            $table->string('identificador_unico')->unique(); // Combinação de ano + uasg + numero_item
            $table->integer('ano_pca');
            $table->string('cnpj_orgao', 14)->index();
            $table->string('codigo_uasg', 10)->nullable()->index();
            $table->string('nome_unidade')->nullable();
            $table->string('tipo_item')->nullable(); // Material ou Serviço
            $table->string('codigo_catalogo')->nullable(); // CATMAT / CATSER
            $table->text('descricao');
            $table->decimal('quantidade', 15, 4)->default(0);
            $table->decimal('valor_unitario', 15, 2)->default(0);
            $table->decimal('valor_total', 15, 2)->default(0);
            $table->date('data_desejada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pca_items');
    }
};