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
        Schema::create('pessoais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('codigoUsuario')->unique()->constrained('users')->onDelete('cascade');
            $table->string('primeiroNomePessoa');
            $table->string('sobrenomeNomePessoa');
            $table->string('cpfPessoa', 14)->nullable();
            $table->string('rgPessoa', 20)->nullable();
            $table->date('dataNascimentoPessoa')->nullable();
            $table->string('sexoPessoa', 20)->nullable();
            $table->string('celularPessoa', 20);
            $table->string('passaportePessoa')->nullable();
            $table->date('passaporteValidadePessoa')->nullable();            
            $table->unsignedBigInteger('codigoPessoaCriacao');
            $table->unsignedBigInteger('codigoPessoaAlteracao');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoais');
    }
};
