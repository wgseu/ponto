<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInitialTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('empresas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fantasia', 70);
            $table->string('razao_social', 70)->nullable();
            $table->string('email', 90);
            $table->string('senha', 30);
            $table->dateTime('data_criacao');
            $table->string('cnpj', 20)->nullable();
            $table->string('fone1', 12);
            $table->string('fone2', 12)->nullable();
            $table->string('imagem_url', 100)->nullable();
            $table->unique(['fantasia']);
        });

        Schema::create('colaboradores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empresa_id');
            $table->string('nome', 20);
            $table->string('sobrenome', 30);
            $table->string('email', 90);
            $table->string('senha', 255);
            $table->double('carga_horaria');
            $table->enum('status', ['Trabalho', 'Ferias', 'Encostado'])->default('Trabalho');
            $table->double('acumulado')->nullable();
            $table->boolean('ativo')->default(false);
            $table->unique(['email']);

            $table->index(['empresa_id']);
            $table->foreign('empresa_id')
                ->references('id')->on('empresas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('pontos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('colaborador_id');
            $table->dateTime('data_ponto');
            $table->string('latitude', 255)->nullable();
            $table->string('longitude', 255)->nullable();
            $table->string('anexo_url', 100)->nullable();
            $table->string('descricao', 255)->nullable();
            $table->enum('tipo', ['ponto', 'correção'])->default('ponto');

            $table->index(['colaborador_id']);
            $table->foreign('colaborador_id')
                ->references('id')->on('colaboradores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

  
        Schema::enableForeignKeyConstraints();
        //(new DatabaseSeeder())->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('empresas');
        Schema::dropIfExists('colaboradores');
        Schema::dropIfExists('pontos');
        Schema::enableForeignKeyConstraints();
    }
}
