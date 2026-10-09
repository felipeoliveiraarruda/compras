<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pcas', function (Blueprint $table) {
            $table->id();
            $table->integer('numeroArtefato')->nullable();
            $table->integer('anoArtefato')->nullable();
            $table->string('numeroContratacao', 10)->nullable()->comment('Número da contratação');
            $table->string('statusContratacao', 100)->nullable()->comment('Status da contratação');
            $table->string('situacaoExecucao', 100)->nullable()->comment('Situação da Execução');
            $table->string('tituloContratacao', 255)->nullable()->comment('Título da contratação');
            $table->string('categoriaContratacao', 100)->nullable()->comment('Categoria da contratação');
            $table->integer('codigoUASG')->nullable()->comment('UASG Atual');
            $table->date('dataEstimadaInicioContratacao')->nullable()->comment('Data estimada para o início do processo de contratação');
            $table->date('dataEstimadaConclusaoContratacao')->nullable()->comment('Data estimada para a conclusão do processo de contratação');
            $table->integer('prazoEstimadoContratacao')->nullable()->comment('Prazo estimado de duração do processo de contratação (dias)');
            $table->string('areaRequisitante', 255)->nullable()->comment('Área requisitante');
            $table->string('numeroDfd', 20)->nullable()->comment('Nº DFD');
            $table->string('prioridadeDfd', 50)->nullable()->comment('Prioridade');
            $table->integer('itemDfd')->nullable()->comment('Nº do Item no DFD');
            $table->date('dataConclusaoDfd')->nullable()->comment('Data da conclusão da Contratação no DFD');
            $table->string('classificacaoContratacao', 100)->nullable()->comment('Classificação da Contratação');
            $table->integer('codigoClasse')->nullable()->comment('Código Classe/Grupo');
            $table->string('nomeClasse', 255)->nullable()->comment('Nome Classe/Grupo');
            $table->integer('codigoPdm')->nullable()->comment('Código PDM material');
            $table->string('nomePdm', 100)->nullable()->comment('Nome do PDM material');
            $table->integer('codigoMaterial')->nullable()->comment('Código material/serviço');
            $table->string('descricaoMaterial', 1000)->nullable()->comment('Descrição material/serviço');
            $table->string('unidadeFornecimento', 100)->comment('Unidade Fornecimento');
            $table->decimal('valorUnitario', 10, 2)->nullable()->comment('Valor Unitário');
            $table->integer('quantidadeTotal')->nullable()->comment('Quantidade');
            $table->decimal('valorTotal', 10, 2)->nullable()->comment('Valor Total');
            $table->string('processoSei', 255)->nullable()->comment('Número do Processo SEI');
            $table->string('situacaoContratacao', 100)->nullable()->comment('Status da PCa de acordo com a sessão de Compras');
            $table->integer('codigoPessoaCriacao')->nullable();
            $table->integer('codigoPessoaAlteracao')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pca');
    }
};
