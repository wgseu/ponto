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

        Schema::create('setores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('setor_id')->nullable();
            $table->string('nome', 50);
            $table->string('descricao', 70)->nullable();

            $table->unique(['nome']);
            $table->index(['setor_id']);
            $table->foreign('setor_id')
                ->references('id')->on('setores')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('mesas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('setor_id');
            $table->integer('numero');
            $table->string('nome', 50);
            $table->boolean('ativa')->default(true);

            $table->unique(['nome']);
            $table->unique(['numero']);
            $table->index(['setor_id']);
            $table->foreign('setor_id')
                ->references('id')->on('setores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('cozinhas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 50);
            $table->string('descricao', 255)->nullable();

            $table->unique(['nome']);
        });

        Schema::create('sessoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cozinha_id');
            $table->dateTime('data_inicio');
            $table->dateTime('data_termino')->nullable();
            $table->boolean('aberta')->default(true);

            $table->index(['aberta']);
            $table->index(['data_inicio']);
            $table->index(['data_termino']);
            $table->index(['cozinha_id']);
            $table->foreign('cozinha_id')
                ->references('id')->on('cozinhas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('bancos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero', 40);
            $table->string('fantasia', 200);
            $table->string('razao_social', 200);
            $table->string('agencia_mascara', 45)->nullable();
            $table->string('conta_mascara', 45)->nullable();

            $table->unique(['razao_social']);
            $table->unique(['numero']);
            $table->unique(['fantasia']);
        });

        Schema::create('carteiras', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('tipo', ['bancaria', 'financeira', 'credito', 'local']);
            $table->unsignedInteger('carteira_id')->nullable();
            $table->unsignedInteger('banco_id')->nullable();
            $table->string('descricao', 100);
            $table->string('conta', 100)->nullable();
            $table->string('agencia', 200)->nullable();
            $table->decimal('transacao', 19, 4)->default(0);
            $table->decimal('limite', 19, 4)->nullable();
            $table->string('token', 250)->nullable();
            $table->enum('ambiente', ['teste', 'producao'])->nullable();
            $table->string('logo_url', 100)->nullable();
            $table->string('cor', 20)->nullable();
            $table->boolean('ativa')->default(true);
            $table->dateTime('data_desativada')->nullable();

            $table->index(['banco_id']);
            $table->index(['carteira_id']);
            $table->foreign('carteira_id')
                ->references('id')->on('carteiras')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('banco_id')
                ->references('id')->on('bancos')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('caixas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('carteira_id');
            $table->string('descricao', 50);
            $table->integer('serie')->default(1);
            $table->integer('numero_inicial')->default(1);
            $table->boolean('ativa')->default(true);
            $table->dateTime('data_desativada')->nullable();

            $table->unique(['descricao']);
            $table->unique(['carteira_id']);
            $table->foreign('carteira_id')
                ->references('id')->on('carteiras')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('formas', function (Blueprint $table) {
            $table->increments('id');
            $table->enum(
                'tipo',
                [
                    'dinheiro',
                    'credito',
                    'debito',
                    'vale',
                    'cheque',
                    'crediario',
                    'saldo',
                ]
            );
            $table->unsignedInteger('carteira_id');
            $table->string('descricao', 50);
            $table->integer('min_parcelas')->default(1);
            $table->integer('max_parcelas')->default(1);
            $table->integer('parcelas_sem_juros')->default(1);
            $table->double('juros')->default(0);
            $table->boolean('ativa')->default(true);

            $table->unique(['descricao']);
            $table->index(['carteira_id']);
            $table->foreign('carteira_id')
                ->references('id')->on('carteiras')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('cartoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('forma_id');
            $table->unsignedInteger('carteira_id')->nullable();
            $table->string('bandeira', 50);
            $table->double('taxa')->default(0);
            $table->integer('dias_repasse')->default(30);
            $table->double('taxa_antecipacao')->default(0);
            $table->string('imagem_url', 100)->nullable();
            $table->boolean('ativo')->default(true);

            $table->unique(['forma_id', 'bandeira']);
            $table->index(['carteira_id']);
            $table->foreign('forma_id')
                ->references('id')->on('formas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('carteira_id')
                ->references('id')->on('carteiras')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('funcoes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao', 45);
            $table->decimal('remuneracao', 19, 4);

            $table->unique(['descricao']);
        });

        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('tipo', ['fisica', 'juridica'])->default('fisica');
            $table->unsignedInteger('empresa_id')->nullable();
            $table->string('login', 50)->nullable();
            $table->string('senha', 255)->nullable();
            $table->string('nome', 100);
            $table->string('sobrenome', 100)->nullable();
            $table->enum('genero', ['masculino', 'feminino'])->nullable();
            $table->string('cpf', 20)->nullable();
            $table->string('rg', 20)->nullable();
            $table->string('im', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('slogan', 100)->nullable();
            $table->enum('status', ['inativo', 'ativo', 'bloqueado'])->default('inativo');
            $table->decimal('limite_compra', 19, 4)->nullable();
            $table->string('instagram', 200)->nullable();
            $table->string('facebook_url', 200)->nullable();
            $table->string('imagem_url', 100)->nullable();
            $table->string('linguagem', 20)->nullable();
            $table->string('ip', 60)->nullable();
            $table->dateTime('data_envio')->nullable();
            $table->dateTime('data_atualizacao')->nullable();
            $table->dateTime('data_cadastro');

            $table->unique(['email']);
            $table->unique(['cpf']);
            $table->unique(['login']);
            $table->index(['empresa_id']);
            $table->index(['nome']);
            $table->foreign('empresa_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('prestadores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo', 100);
            $table->string('pin', 200)->nullable();
            $table->unsignedInteger('funcao_id');
            $table->unsignedInteger('cliente_id');
            $table->unsignedInteger('empresa_id')->nullable();
            $table->enum('vinculo', ['funcionario', 'prestador', 'autonomo'])->default('funcionario');
            $table->double('porcentagem')->default(0);
            $table->integer('pontuacao')->default(0);
            $table->decimal('remuneracao', 19, 4)->default(0);
            $table->dateTime('data_termino')->nullable();
            $table->dateTime('data_cadastro');

            $table->unique(['cliente_id']);
            $table->unique(['codigo']);
            $table->index(['funcao_id']);
            $table->index(['empresa_id']);
            $table->foreign('funcao_id')
                ->references('id')->on('funcoes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('cliente_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('empresa_id')
                ->references('id')->on('prestadores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('moedas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 45);
            $table->string('simbolo', 10);
            $table->string('codigo', 45);
            $table->integer('divisao');
            $table->string('fracao', 45)->nullable();
            $table->string('formato', 45);
            $table->double('conversao')->nullable();
            $table->dateTime('data_atualizacao')->nullable();
            $table->boolean('ativa')->default(false);

            $table->unique(['codigo']);
        });

        Schema::create('paises', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 100);
            $table->string('sigla', 10);
            $table->string('codigo', 10);
            $table->unsignedInteger('moeda_id');
            $table->string('idioma', 10);
            $table->string('prefixo', 45)->nullable();
            $table->text('entradas')->nullable();
            $table->boolean('unitario')->default(false);

            $table->unique(['nome']);
            $table->unique(['sigla']);
            $table->unique(['codigo']);
            $table->index(['moeda_id']);
            $table->foreign('moeda_id')
                ->references('id')->on('moedas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('estados', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pais_id');
            $table->string('nome', 64);
            $table->string('uf', 48);

            $table->unique(['pais_id', 'nome']);
            $table->unique(['pais_id', 'uf']);
            $table->foreign('pais_id')
                ->references('id')->on('paises')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('cidades', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('estado_id');
            $table->string('nome', 100);
            $table->string('cep', 8)->nullable();

            $table->unique(['estado_id', 'nome']);
            $table->unique(['cep']);
            $table->foreign('estado_id')
                ->references('id')->on('estados')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('bairros', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cidade_id');
            $table->string('nome', 100);
            $table->decimal('valor_entrega', 19, 4);
            $table->boolean('disponivel')->default(true);
            $table->boolean('mapeado')->default(false);
            $table->integer('entrega_minima')->nullable();
            $table->integer('entrega_maxima')->nullable();

            $table->unique(['cidade_id', 'nome']);
            $table->foreign('cidade_id')
                ->references('id')->on('cidades')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('zonas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bairro_id');
            $table->string('nome', 45);
            $table->decimal('adicional_entrega', 19, 4);
            $table->boolean('disponivel')->default(true);
            $table->text('area')->nullable();
            $table->integer('entrega_minima')->nullable();
            $table->integer('entrega_maxima')->nullable();

            $table->unique(['bairro_id', 'nome']);
            $table->foreign('bairro_id')
                ->references('id')->on('bairros')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('localizacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cliente_id');
            $table->unsignedInteger('bairro_id');
            $table->unsignedInteger('zona_id')->nullable();
            $table->string('cep', 8)->nullable();
            $table->string('logradouro', 100);
            $table->string('numero', 20);
            $table->enum('tipo', ['casa', 'apartamento', 'condominio'])->default('casa');
            $table->string('complemento', 100)->nullable();
            $table->string('condominio', 100)->nullable();
            $table->string('bloco', 20)->nullable();
            $table->string('apartamento', 20)->nullable();
            $table->string('referencia', 200)->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('apelido', 45)->nullable();
            $table->dateTime('data_arquivado')->nullable();

            $table->index(['bairro_id']);
            $table->index(['zona_id']);
            $table->foreign('cliente_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('bairro_id')
                ->references('id')->on('bairros')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('zona_id')
                ->references('id')->on('zonas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('comandas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('numero');
            $table->string('nome', 50);
            $table->boolean('ativa')->default(true);

            $table->unique(['nome']);
            $table->unique(['numero']);
        });

        Schema::create('viagens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('responsavel_id');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->double('quilometragem')->nullable();
            $table->double('distancia')->nullable();
            $table->dateTime('data_atualizacao')->nullable();
            $table->dateTime('data_chegada')->nullable();
            $table->dateTime('data_saida');

            $table->index(['responsavel_id']);
            $table->foreign('responsavel_id')
                ->references('id')->on('prestadores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('integracoes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 45);
            $table->string('codigo', 45);
            $table->string('descricao', 200)->nullable();
            $table->enum(
                'tipo',
                [
                    'pedido',
                    'login',
                    'dispositivo',
                    'pagamento',
                    'outros',
                    ]
                );
            $table->string('login', 200)->nullable();
            $table->string('secret', 200)->nullable();
            $table->text('opcoes')->nullable();
            $table->text('associacoes')->nullable();
            $table->boolean('ativo')->default(false);
            $table->dateTime('data_atualizacao')->nullable();

            $table->unique(['nome']);
        });

        Schema::create('associacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('integracao_id');
            $table->unsignedInteger('entrega_id')->nullable();
            $table->string('codigo', 50);
            $table->string('cliente', 255);
            $table->string('chave', 100);
            $table->text('pedido');
            $table->string('endereco', 255)->nullable();
            $table->double('quantidade');
            $table->decimal('servicos', 19, 4);
            $table->decimal('produtos', 19, 4);
            $table->decimal('descontos', 19, 4);
            $table->decimal('pago', 19, 4);
            $table->enum(
                'status',
                [
                    'agendado',
                    'aberto',
                    'entrega',
                    'concluido',
                    'cancelado',
                ]
            );
            $table->string('motivo', 200)->nullable();
            $table->string('mensagem', 255)->nullable();
            $table->boolean('sincronizado');
            $table->boolean('integrado');
            $table->dateTime('data_confirmacao')->nullable();
            $table->dateTime('data_pedido');

            $table->index(['integracao_id']);
            $table->index(['entrega_id']);
            $table->foreign('integracao_id')
                ->references('id')->on('integracoes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('entrega_id')
                ->references('id')->on('viagens')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pedido_id')->nullable();
            $table->unsignedInteger('mesa_id')->nullable();
            $table->unsignedInteger('comanda_id')->nullable();
            $table->unsignedInteger('sessao_id')->nullable();
            $table->unsignedInteger('prestador_id')->nullable();
            $table->unsignedInteger('cliente_id')->nullable();
            $table->unsignedInteger('localizacao_id')->nullable();
            $table->unsignedInteger('entrega_id')->nullable();
            $table->unsignedInteger('associacao_id')->nullable();
            $table->enum('tipo', ['mesa', 'comanda', 'balcao', 'entrega'])->default('balcao');
            $table->enum(
                'estado',
                [
                    'agendado',
                    'aberto',
                    'entrega',
                    'fechado',
                    'concluido',
                    'cancelado',
                ]
            )->default('aberto');
            $table->decimal('servicos', 19, 4)->default(0);
            $table->decimal('produtos', 19, 4)->default(0);
            $table->decimal('comissao', 19, 4)->default(0);
            $table->decimal('subtotal', 19, 4)->default(0);
            $table->decimal('descontos', 19, 4)->default(0);
            $table->decimal('total', 19, 4)->default(0);
            $table->decimal('pago', 19, 4)->default(0);
            $table->decimal('troco', 19, 4)->default(0);
            $table->decimal('lancado', 19, 4)->default(0);
            $table->integer('pessoas')->default(1);
            $table->string('cpf', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('descricao', 255)->nullable();
            $table->unsignedInteger('fechador_id')->nullable();
            $table->dateTime('data_impressao')->nullable();
            $table->string('motivo', 200)->nullable();
            $table->dateTime('data_conclusao')->nullable();
            $table->integer('data_pronto')->nullable();
            $table->dateTime('data_entrega')->nullable();
            $table->dateTime('data_agendamento')->nullable();
            $table->dateTime('data_criacao');

            $table->unique(['associacao_id']);
            $table->index(['mesa_id']);
            $table->index(['sessao_id']);
            $table->index(['prestador_id']);
            $table->index(['cliente_id']);
            $table->index(['tipo', 'estado']);
            $table->index(['localizacao_id']);
            $table->index(['comanda_id']);
            $table->index(['fechador_id']);
            $table->index(['entrega_id']);
            $table->index(['data_criacao']);
            $table->index(['pedido_id']);
            $table->foreign('pedido_id')
                ->references('id')->on('pedidos')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('mesa_id')
                ->references('id')->on('mesas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('comanda_id')
                ->references('id')->on('comandas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('sessao_id')
                ->references('id')->on('sessoes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('prestador_id')
                ->references('id')->on('prestadores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('cliente_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('localizacao_id')
                ->references('id')->on('localizacoes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('entrega_id')
                ->references('id')->on('viagens')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('associacao_id')
                ->references('id')->on('associacoes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('fechador_id')
                ->references('id')->on('prestadores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('categoria_id')->nullable();
            $table->string('descricao', 45);
            $table->string('detalhes', 200)->nullable();
            $table->string('imagem_url', 100)->nullable();
            $table->integer('ordem')->default(0);
            $table->dateTime('data_atualizacao')->nullable();
            $table->dateTime('data_arquivado')->nullable();

            $table->unique(['descricao']);
            $table->index(['categoria_id']);
            $table->foreign('categoria_id')
                ->references('id')->on('categorias')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('unidades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 45);
            $table->string('descricao', 45)->nullable();
            $table->string('sigla', 10);

            $table->unique(['sigla']);
        });

        Schema::create('origens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('codigo');
            $table->string('descricao', 200);

            $table->unique(['codigo']);
        });

        Schema::create('operacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('codigo');
            $table->string('descricao', 255);
            $table->text('detalhes')->nullable();

            $table->unique(['codigo']);
        });

        Schema::create('impostos', function (Blueprint $table) {
            $table->increments('id');
            $table->enum(
                'grupo',
                [
                    'icms',
                    'pis',
                    'cofins',
                    'ipi',
                    'ii',
                ]
            );
            $table->boolean('simples');
            $table->boolean('substituicao');
            $table->integer('codigo');
            $table->string('descricao', 255);

            $table->unique(['grupo', 'simples', 'substituicao', 'codigo']);
        });

        Schema::create('tributacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ncm', 10);
            $table->string('cest', 20)->nullable();
            $table->unsignedInteger('origem_id');
            $table->unsignedInteger('operacao_id');
            $table->unsignedInteger('imposto_id');

            $table->index(['origem_id']);
            $table->index(['operacao_id']);
            $table->index(['imposto_id']);
            $table->foreign('origem_id')
                ->references('id')->on('origens')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('operacao_id')
                ->references('id')->on('operacoes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('imposto_id')
                ->references('id')->on('impostos')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('produtos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo', 100);
            $table->unsignedInteger('categoria_id');
            $table->unsignedInteger('unidade_id');
            $table->unsignedInteger('setor_estoque_id')->nullable();
            $table->unsignedInteger('setor_preparo_id')->nullable();
            $table->unsignedInteger('tributacao_id')->nullable();
            $table->string('descricao', 75);
            $table->string('abreviacao', 100)->nullable();
            $table->string('detalhes', 255)->nullable();
            $table->double('quantidade_minima')->default(0);
            $table->double('quantidade_maxima')->default(0);
            $table->decimal('preco_venda', 19, 4)->default(0);
            $table->decimal('custo_medio', 19, 4)->nullable();
            $table->decimal('custo_producao', 19, 4)->nullable();
            $table->enum('tipo', ['produto', 'composicao', 'pacote'])->default('produto');
            $table->boolean('cobrar_servico')->default(true);
            $table->boolean('divisivel')->default(false);
            $table->boolean('pesavel')->default(false);
            $table->integer('tempo_preparo')->default(0);
            $table->boolean('disponivel')->default(true);
            $table->boolean('insumo')->default(false);
            $table->double('avaliacao')->nullable();
            $table->double('estoque')->nullable()->default(0);
            $table->string('imagem_url', 100)->nullable();
            $table->dateTime('data_atualizacao')->nullable();
            $table->dateTime('data_arquivado')->nullable();

            $table->unique(['descricao']);
            $table->unique(['codigo']);
            $table->index(['categoria_id']);
            $table->index(['unidade_id']);
            $table->index(['setor_preparo_id']);
            $table->index(['setor_estoque_id']);
            $table->index(['tributacao_id']);
            $table->foreign('categoria_id')
                ->references('id')->on('categorias')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('unidade_id')
                ->references('id')->on('unidades')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('setor_estoque_id')
                ->references('id')->on('setores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('setor_preparo_id')
                ->references('id')->on('setores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('tributacao_id')
                ->references('id')->on('tributacoes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('servicos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 50);
            $table->string('descricao', 100);
            $table->string('detalhes', 200)->nullable();
            $table->enum('tipo', ['evento', 'taxa']);
            $table->boolean('obrigatorio')->default(true);
            $table->dateTime('data_inicio')->nullable();
            $table->dateTime('data_fim')->nullable();
            $table->integer('tempo_limite')->nullable();
            $table->decimal('valor', 19, 4)->default(0);
            $table->boolean('individual')->default(false);
            $table->string('imagem_url', 100)->nullable();
            $table->boolean('ativo')->default(true);

        });

        Schema::create('classificacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('classificacao_id')->nullable();
            $table->string('descricao', 100);
            $table->string('icone_url', 100)->nullable();

            $table->unique(['descricao']);
            $table->index(['classificacao_id']);
            $table->foreign('classificacao_id')
                ->references('id')->on('classificacoes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('contas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('classificacao_id');
            $table->unsignedInteger('funcionario_id');
            $table->unsignedInteger('conta_id')->nullable();
            $table->unsignedInteger('agrupamento_id')->nullable();
            $table->unsignedInteger('carteira_id')->nullable();
            $table->unsignedInteger('cliente_id')->nullable();
            $table->unsignedInteger('pedido_id')->nullable();
            $table->enum('tipo', ['receita', 'despesa'])->default('despesa');
            $table->string('descricao', 200);
            $table->decimal('valor', 19, 4);
            $table->decimal('consolidado', 19, 4)->default(0);
            $table->enum('fonte', ['fixa', 'variavel', 'comissao', 'remuneracao'])->default('fixa');
            $table->integer('numero_parcela')->default(1);
            $table->integer('parcelas')->default(1);
            $table->integer('frequencia')->default(0);
            $table->enum('modo', ['diario', 'mensal'])->default('mensal');
            $table->boolean('automatico')->default(false);
            $table->decimal('acrescimo', 19, 4)->default(0);
            $table->decimal('multa', 19, 4)->default(0);
            $table->double('juros')->default(0);
            $table->enum('formula', ['simples', 'composto'])->default('composto');
            $table->dateTime('vencimento');
            $table->string('numero', 64)->nullable();
            $table->string('anexo_url', 200)->nullable();
            $table->enum(
                'estado',
                [
                    'analise',
                    'ativa',
                    'paga',
                    'cancelada',
                    'desativada',
                ]
            )->default('ativa');
            $table->dateTime('data_calculo')->nullable();
            $table->dateTime('data_emissao');

            $table->index(['cliente_id']);
            $table->index(['funcionario_id']);
            $table->index(['pedido_id']);
            $table->index(['classificacao_id']);
            $table->index(['conta_id']);
            $table->index(['carteira_id']);
            $table->index(['agrupamento_id']);
            $table->index(['vencimento']);
            $table->index(['data_emissao']);
            $table->foreign('classificacao_id')
                ->references('id')->on('classificacoes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('funcionario_id')
                ->references('id')->on('prestadores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('conta_id')
                ->references('id')->on('contas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('agrupamento_id')
                ->references('id')->on('contas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('carteira_id')
                ->references('id')->on('carteiras')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('cliente_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('pedido_id')
                ->references('id')->on('pedidos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('movimentacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sessao_id');
            $table->unsignedInteger('caixa_id');
            $table->boolean('aberta')->default(true);
            $table->unsignedInteger('iniciador_id');
            $table->unsignedInteger('fechador_id')->nullable();
            $table->dateTime('data_fechamento')->nullable();
            $table->dateTime('data_abertura');

            $table->index(['sessao_id']);
            $table->index(['caixa_id']);
            $table->index(['iniciador_id']);
            $table->index(['fechador_id']);
            $table->index(['data_abertura']);
            $table->index(['data_fechamento']);
            $table->foreign('sessao_id')
                ->references('id')->on('sessoes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('caixa_id')
                ->references('id')->on('caixas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('iniciador_id')
                ->references('id')->on('prestadores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('fechador_id')
                ->references('id')->on('prestadores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('creditos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cliente_id');
            $table->decimal('valor', 19, 4);
            $table->string('detalhes', 255);
            $table->boolean('cancelado')->default(false);
            $table->dateTime('data_cadastro');

            $table->index(['cliente_id']);
            $table->foreign('cliente_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('cheques', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cliente_id');
            $table->unsignedInteger('banco_id');
            $table->string('agencia', 45);
            $table->string('conta', 45);
            $table->string('numero', 20);
            $table->decimal('valor', 19, 4);
            $table->dateTime('vencimento');
            $table->boolean('cancelado')->default(false);
            $table->dateTime('recolhimento')->nullable();
            $table->dateTime('data_cadastro');

            $table->index(['vencimento']);
            $table->index(['cliente_id']);
            $table->index(['banco_id']);
            $table->index(['recolhimento']);
            $table->foreign('cliente_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('banco_id')
                ->references('id')->on('bancos')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('pagamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('carteira_id');
            $table->unsignedInteger('moeda_id');
            $table->unsignedInteger('pagamento_id')->nullable();
            $table->unsignedInteger('agrupamento_id')->nullable();
            $table->unsignedInteger('movimentacao_id')->nullable();
            $table->unsignedInteger('funcionario_id')->nullable();
            $table->unsignedInteger('forma_id')->nullable();
            $table->unsignedInteger('pedido_id')->nullable();
            $table->unsignedInteger('conta_id')->nullable();
            $table->unsignedInteger('cartao_id')->nullable();
            $table->unsignedInteger('cheque_id')->nullable();
            $table->unsignedInteger('crediario_id')->nullable();
            $table->unsignedInteger('credito_id')->nullable();
            $table->decimal('valor', 19, 4);
            $table->integer('numero_parcela')->default(1);
            $table->integer('parcelas')->default(1);
            $table->decimal('lancado', 19, 4);
            $table->string('codigo', 100)->nullable();
            $table->string('detalhes', 200)->nullable();
            $table->enum(
                'estado',
                [
                    'aberto',
                    'aguardando',
                    'analise',
                    'pago',
                    'disputa',
                    'devolvido',
                    'cancelado',
                ]
            )->default('aberto');
            $table->dateTime('data_pagamento')->nullable();
            $table->dateTime('data_compensacao')->nullable();
            $table->dateTime('data_lancamento');

            $table->index(['funcionario_id']);
            $table->index(['forma_id']);
            $table->index(['pedido_id']);
            $table->index(['cartao_id']);
            $table->index(['crediario_id']);
            $table->index(['conta_id']);
            $table->index(['movimentacao_id']);
            $table->index(['credito_id']);
            $table->index(['carteira_id']);
            $table->index(['cheque_id']);
            $table->index(['pagamento_id']);
            $table->index(['moeda_id']);
            $table->index(['agrupamento_id']);
            $table->index(['data_compensacao']);
            $table->index(['data_lancamento']);
            $table->index(['data_pagamento']);
            $table->foreign('carteira_id')
                ->references('id')->on('carteiras')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('moeda_id')
                ->references('id')->on('moedas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('pagamento_id')
                ->references('id')->on('pagamentos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('agrupamento_id')
                ->references('id')->on('pagamentos')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('movimentacao_id')
                ->references('id')->on('movimentacoes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('funcionario_id')
                ->references('id')->on('prestadores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('forma_id')
                ->references('id')->on('formas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('pedido_id')
                ->references('id')->on('pedidos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('conta_id')
                ->references('id')->on('contas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('cartao_id')
                ->references('id')->on('cartoes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('cheque_id')
                ->references('id')->on('cheques')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('crediario_id')
                ->references('id')->on('contas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('credito_id')
                ->references('id')->on('creditos')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('itens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pedido_id');
            $table->unsignedInteger('prestador_id')->nullable();
            $table->unsignedInteger('produto_id')->nullable();
            $table->unsignedInteger('servico_id')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('pagamento_id')->nullable();
            $table->string('descricao', 200)->nullable();
            $table->text('composicao')->nullable();
            $table->decimal('preco', 19, 4);
            $table->double('quantidade');
            $table->decimal('subtotal', 19, 4);
            $table->decimal('comissao', 19, 4)->default(0);
            $table->decimal('total', 19, 4);
            $table->decimal('preco_venda', 19, 4);
            $table->decimal('custo_aproximado', 19, 4)->default(0);
            $table->string('detalhes', 255)->nullable();
            $table->enum(
                'estado',
                [
                    'adicionado',
                    'enviado',
                    'processado',
                    'pronto',
                    'disponivel',
                    'entregue',
                ]
            )->default('adicionado');
            $table->boolean('cancelado')->default(false);
            $table->string('motivo', 200)->nullable();
            $table->boolean('desperdicado')->default(false);
            $table->boolean('reservado')->default(false);
            $table->dateTime('data_processamento')->nullable();
            $table->dateTime('data_atualizacao')->nullable();
            $table->dateTime('data_lancamento');

            $table->index(['pedido_id']);
            $table->index(['produto_id']);
            $table->index(['prestador_id']);
            $table->index(['item_id']);
            $table->index(['servico_id']);
            $table->index(['pagamento_id']);
            $table->foreign('pedido_id')
                ->references('id')->on('pedidos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('prestador_id')
                ->references('id')->on('prestadores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('produto_id')
                ->references('id')->on('produtos')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('servico_id')
                ->references('id')->on('servicos')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('item_id')
                ->references('id')->on('itens')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('pagamento_id')
                ->references('id')->on('pagamentos')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('modulos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 50);
            $table->string('descricao', 200);
            $table->boolean('habilitado')->default(true);

            $table->unique(['nome']);
        });

        Schema::create('funcionalidades', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('modulo_id')->nullable();
            $table->unsignedInteger('funcionalidade_id')->nullable();
            $table->string('nome', 64);
            $table->string('descricao', 200);

            $table->unique(['nome']);
            $table->index(['funcionalidade_id']);
            $table->index(['modulo_id']);
            $table->foreign('modulo_id')
                ->references('id')->on('modulos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('funcionalidade_id')
                ->references('id')->on('funcionalidades')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('permissoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('funcionalidade_id');
            $table->string('nome', 45);
            $table->string('descricao', 100);

            $table->unique(['nome']);
            $table->index(['funcionalidade_id']);
            $table->foreign('funcionalidade_id')
                ->references('id')->on('funcionalidades')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('auditorias', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('permissao_id')->nullable();
            $table->unsignedInteger('prestador_id');
            $table->unsignedInteger('autorizador_id');
            $table->enum('tipo', ['financeiro', 'administrativo', 'operacional']);
            $table->enum('prioridade', ['baixa', 'media', 'alta']);
            $table->string('descricao', 255);
            $table->string('autorizacao', 255)->nullable();
            $table->dateTime('data_registro');

            $table->index(['prestador_id']);
            $table->index(['data_registro']);
            $table->index(['autorizador_id']);
            $table->index(['permissao_id']);
            $table->foreign('permissao_id')
                ->references('id')->on('permissoes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('prestador_id')
                ->references('id')->on('prestadores')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('autorizador_id')
                ->references('id')->on('prestadores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('composicoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('composicao_id');
            $table->unsignedInteger('produto_id');
            $table->enum('tipo', ['composicao', 'opcional', 'adicional'])->default('composicao');
            $table->double('quantidade');
            $table->decimal('valor', 19, 4)->default(0);
            $table->integer('quantidade_maxima')->default(1);
            $table->boolean('ativa')->default(true);
            $table->dateTime('data_remocao')->nullable();

            $table->unique(['composicao_id', 'produto_id', 'tipo']);
            $table->index(['produto_id']);
            $table->foreign('composicao_id')
                ->references('id')->on('produtos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('produto_id')
                ->references('id')->on('produtos')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('fornecedores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empresa_id');
            $table->integer('prazo_pagamento')->default(0);
            $table->dateTime('data_cadastro');

            $table->unique(['empresa_id']);
            $table->foreign('empresa_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('compras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero', 100)->nullable();
            $table->unsignedInteger('comprador_id');
            $table->unsignedInteger('fornecedor_id');
            $table->unsignedInteger('conta_id')->nullable();
            $table->string('documento_url', 200)->nullable();
            $table->dateTime('data_compra');

            $table->unique(['numero']);
            $table->index(['fornecedor_id']);
            $table->index(['comprador_id']);
            $table->index(['conta_id']);
            $table->foreign('comprador_id')
                ->references('id')->on('prestadores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('fornecedor_id')
                ->references('id')->on('fornecedores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('conta_id')
                ->references('id')->on('contas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('estoques', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('producao_id')->nullable();
            $table->unsignedInteger('produto_id');
            $table->unsignedInteger('compra_id')->nullable();
            $table->unsignedInteger('transacao_id')->nullable();
            $table->unsignedInteger('fornecedor_id')->nullable();
            $table->unsignedInteger('setor_id');
            $table->unsignedInteger('prestador_id')->nullable();
            $table->double('quantidade');
            $table->decimal('preco_compra', 19, 4)->default(0);
            $table->decimal('custo_medio', 19, 4)->nullable();
            $table->double('estoque')->nullable();
            $table->string('lote', 45)->nullable();
            $table->dateTime('fabricacao')->nullable();
            $table->dateTime('vencimento')->nullable();
            $table->string('detalhes', 100)->nullable();
            $table->boolean('cancelado')->default(false);
            $table->dateTime('data_movimento');

            $table->index(['produto_id']);
            $table->index(['transacao_id']);
            $table->index(['fornecedor_id']);
            $table->index(['prestador_id']);
            $table->index(['setor_id']);
            $table->index(['data_movimento']);
            $table->index(['producao_id']);
            $table->index(['compra_id']);
            $table->foreign('producao_id')
                ->references('id')->on('estoques')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('produto_id')
                ->references('id')->on('produtos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('compra_id')
                ->references('id')->on('compras')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('transacao_id')
                ->references('id')->on('itens')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('fornecedor_id')
                ->references('id')->on('fornecedores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('setor_id')
                ->references('id')->on('setores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('prestador_id')
                ->references('id')->on('prestadores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('grupos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('produto_id');
            $table->string('nome', 100);
            $table->string('descricao', 100);
            $table->enum('tipo', ['inteiro', 'fracionado'])->default('inteiro');
            $table->integer('quantidade_minima')->default(1);
            $table->integer('quantidade_maxima')->default(0);
            $table->enum('funcao', ['minimo', 'media', 'maximo', 'soma'])->default('soma');
            $table->integer('ordem')->default(0);
            $table->dateTime('data_arquivado')->nullable();

            $table->index(['produto_id']);
            $table->foreign('produto_id')
                ->references('id')->on('produtos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('propriedades', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('grupo_id');
            $table->string('nome', 100);
            $table->string('abreviacao', 100)->nullable();
            $table->string('imagem_url', 100)->nullable();
            $table->dateTime('data_atualizacao')->nullable();

            $table->unique(['grupo_id', 'nome']);
            $table->foreign('grupo_id')
                ->references('id')->on('grupos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('pacotes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pacote_id');
            $table->unsignedInteger('grupo_id');
            $table->unsignedInteger('produto_id')->nullable();
            $table->unsignedInteger('propriedade_id')->nullable();
            $table->unsignedInteger('associacao_id')->nullable();
            $table->integer('quantidade_minima')->default(0);
            $table->integer('quantidade_maxima')->default(1);
            $table->decimal('acrescimo', 19, 4);
            $table->boolean('selecionado')->default(false);
            $table->boolean('disponivel')->default(true);
            $table->dateTime('data_arquivado')->nullable();

            $table->index(['pacote_id']);
            $table->index(['produto_id']);
            $table->index(['grupo_id']);
            $table->index(['associacao_id']);
            $table->index(['propriedade_id']);
            $table->foreign('pacote_id')
                ->references('id')->on('produtos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('grupo_id')
                ->references('id')->on('grupos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('produto_id')
                ->references('id')->on('produtos')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('propriedade_id')
                ->references('id')->on('propriedades')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('associacao_id')
                ->references('id')->on('pacotes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('dispositivos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('setor_id')->nullable();
            $table->unsignedInteger('caixa_id')->nullable();
            $table->string('nome', 100);
            $table->enum('tipo', ['computador', 'navegador', 'movel'])->default('computador');
            $table->string('descricao', 45)->nullable();
            $table->text('opcoes')->nullable();
            $table->string('serial', 45);
            $table->string('validacao', 40)->nullable();

            $table->unique(['caixa_id']);
            $table->unique(['serial']);
            $table->index(['setor_id']);
            $table->foreign('setor_id')
                ->references('id')->on('setores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('caixa_id')
                ->references('id')->on('caixas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('impressoras', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('dispositivo_id');
            $table->unsignedInteger('setor_id');
            $table->string('nome', 100);
            $table->string('modelo', 45);
            $table->enum('modo', ['terminal', 'caixa', 'servico', 'estoque'])->default('terminal');
            $table->text('opcoes')->nullable();
            $table->integer('colunas')->default(48);
            $table->integer('avanco')->default(6);

            $table->unique(['dispositivo_id', 'setor_id', 'modo']);
            $table->index(['dispositivo_id']);
            $table->foreign('dispositivo_id')
                ->references('id')->on('dispositivos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('setor_id')
                ->references('id')->on('setores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('promocoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('promocao_id')->nullable();
            $table->unsignedInteger('categoria_id')->nullable();
            $table->unsignedInteger('produto_id')->nullable();
            $table->unsignedInteger('servico_id')->nullable();
            $table->unsignedInteger('bairro_id')->nullable();
            $table->unsignedInteger('zona_id')->nullable();
            $table->unsignedInteger('integracao_id')->nullable();
            $table->enum(
                'local',
                [
                    'local',
                    'mesa',
                    'comanda',
                    'balcao',
                    'entrega',
                    'online',
                ]
            )->nullable();
            $table->integer('inicio');
            $table->integer('fim');
            $table->decimal('valor', 19, 4);
            $table->integer('pontos')->default(0);
            $table->boolean('parcial')->default(false);
            $table->boolean('proibir')->default(false);
            $table->boolean('evento')->default(false);
            $table->boolean('agendamento')->default(false);
            $table->boolean('limitar_vendas')->default(false);
            $table->enum('funcao_vendas', ['menor', 'igual', 'maior'])->default('maior');
            $table->integer('vendas_limite')->default(0);
            $table->boolean('limitar_cliente')->default(false);
            $table->enum('funcao_cliente', ['menor', 'igual', 'maior'])->default('maior');
            $table->decimal('cliente_limite', 19, 4)->default(0);
            $table->boolean('ativa')->default(true);
            $table->string('chamada', 200)->nullable();
            $table->string('banner_url', 100)->nullable();
            $table->dateTime('data_arquivado')->nullable();

            $table->index(['produto_id']);
            $table->index(['servico_id']);
            $table->index(['bairro_id']);
            $table->index(['zona_id']);
            $table->index(['integracao_id']);
            $table->index(['categoria_id']);
            $table->index(['promocao_id']);
            $table->foreign('promocao_id')
                ->references('id')->on('promocoes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('categoria_id')
                ->references('id')->on('categorias')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('produto_id')
                ->references('id')->on('produtos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('servico_id')
                ->references('id')->on('servicos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('bairro_id')
                ->references('id')->on('bairros')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('zona_id')
                ->references('id')->on('zonas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('integracao_id')
                ->references('id')->on('integracoes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('acessos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('funcao_id');
            $table->unsignedInteger('permissao_id');

            $table->unique(['funcao_id', 'permissao_id']);
            $table->index(['permissao_id']);
            $table->foreign('funcao_id')
                ->references('id')->on('funcoes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('permissao_id')
                ->references('id')->on('permissoes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('catalogos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('produto_id');
            $table->unsignedInteger('fornecedor_id');
            $table->decimal('preco_compra', 19, 4);
            $table->decimal('preco_venda', 19, 4)->default(0);
            $table->double('quantidade_minima')->default(1);
            $table->double('estoque')->default(0);
            $table->boolean('limitado')->default(false);
            $table->double('conteudo')->default(1);
            $table->dateTime('data_consulta')->nullable();
            $table->dateTime('data_parada')->nullable();

            $table->unique(['fornecedor_id', 'produto_id']);
            $table->index(['produto_id']);
            $table->foreign('produto_id')
                ->references('id')->on('produtos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('fornecedor_id')
                ->references('id')->on('fornecedores')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('sistemas', function (Blueprint $table) {
            $table->enum('id', ['1'])->default('1');
            $table->string('fuso_horario', 100)->nullable();
            $table->text('opcoes')->nullable();

            $table->primary('id');
        });

        Schema::create('resumos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('movimentacao_id');
            $table->unsignedInteger('forma_id');
            $table->unsignedInteger('cartao_id')->nullable();
            $table->decimal('valor', 19, 4);

            $table->unique(['movimentacao_id', 'forma_id', 'cartao_id']);
            $table->index(['cartao_id']);
            $table->index(['forma_id']);
            $table->foreign('movimentacao_id')
                ->references('id')->on('movimentacoes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('forma_id')
                ->references('id')->on('formas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('cartao_id')
                ->references('id')->on('cartoes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('formacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('pacote_id')->nullable();
            $table->unsignedInteger('composicao_id')->nullable();
            $table->double('quantidade')->default(1);

            $table->unique(['item_id', 'pacote_id', 'composicao_id']);
            $table->index(['pacote_id']);
            $table->index(['composicao_id']);
            $table->foreign('item_id')
                ->references('id')->on('itens')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('pacote_id')
                ->references('id')->on('pacotes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('composicao_id')
                ->references('id')->on('composicoes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('listas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao', 100);
            $table->enum('estado', ['analise', 'fechada', 'comprada'])->default('analise');
            $table->unsignedInteger('encarregado_id');
            $table->unsignedInteger('viagem_id')->nullable();
            $table->dateTime('data_viagem');
            $table->dateTime('data_cadastro');

            $table->index(['encarregado_id']);
            $table->index(['viagem_id']);
            $table->foreign('encarregado_id')
                ->references('id')->on('prestadores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('viagem_id')
                ->references('id')->on('viagens')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('requisitos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lista_id');
            $table->unsignedInteger('produto_id');
            $table->unsignedInteger('compra_id')->nullable();
            $table->unsignedInteger('fornecedor_id')->nullable();
            $table->double('quantidade')->default(0);
            $table->double('comprado')->default(0);
            $table->decimal('preco_maximo', 19, 4)->default(0);
            $table->decimal('preco', 19, 4)->default(0);
            $table->string('observacoes', 100)->nullable();
            $table->dateTime('data_recolhimento')->nullable();

            $table->index(['lista_id']);
            $table->index(['produto_id']);
            $table->index(['fornecedor_id']);
            $table->index(['compra_id']);
            $table->foreign('lista_id')
                ->references('id')->on('listas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('produto_id')
                ->references('id')->on('produtos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('compra_id')
                ->references('id')->on('compras')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('fornecedor_id')
                ->references('id')->on('fornecedores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('enderecos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cidade_id');
            $table->unsignedInteger('bairro_id');
            $table->string('logradouro', 200);
            $table->string('cep', 8);

            $table->unique(['cep']);
            $table->unique(['bairro_id', 'logradouro']);
            $table->index(['cidade_id']);
            $table->foreign('cidade_id')
                ->references('id')->on('cidades')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('bairro_id')
                ->references('id')->on('bairros')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('horarios', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('modo', ['funcionamento', 'operacao', 'entrega'])->default('funcionamento');
            $table->unsignedInteger('funcao_id')->nullable();
            $table->unsignedInteger('prestador_id')->nullable();
            $table->unsignedInteger('cozinha_id')->nullable();
            $table->integer('inicio');
            $table->integer('fim');
            $table->string('mensagem', 200)->nullable();
            $table->integer('entrega_minima')->nullable();
            $table->integer('entrega_maxima')->default(0);
            $table->boolean('fechado')->default(false);

            $table->index(['prestador_id']);
            $table->index(['funcao_id']);
            $table->index(['cozinha_id']);
            $table->foreign('funcao_id')
                ->references('id')->on('funcoes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('prestador_id')
                ->references('id')->on('prestadores')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('cozinha_id')
                ->references('id')->on('cozinhas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('patrimonios', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empresa_id');
            $table->unsignedInteger('fornecedor_id')->nullable();
            $table->string('numero', 45);
            $table->string('descricao', 200);
            $table->double('quantidade');
            $table->double('altura')->default(0);
            $table->double('largura')->default(0);
            $table->double('comprimento')->default(0);
            $table->enum('estado', ['novo', 'conservado', 'ruim'])->default('novo');
            $table->decimal('custo', 19, 4)->default(0);
            $table->decimal('valor', 19, 4)->default(0);
            $table->boolean('ativo')->default(true);
            $table->string('imagem_url', 200)->nullable();
            $table->dateTime('data_atualizacao')->nullable();

            $table->unique(['numero']);
            $table->index(['fornecedor_id']);
            $table->index(['empresa_id']);
            $table->foreign('empresa_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('fornecedor_id')
                ->references('id')->on('fornecedores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('juncoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mesa_id');
            $table->unsignedInteger('pedido_id');
            $table->enum('estado', ['associado', 'liberado', 'cancelado'])->default('associado');
            $table->dateTime('data_movimento');

            $table->index(['pedido_id']);
            $table->index(['mesa_id', 'estado']);
            $table->foreign('mesa_id')
                ->references('id')->on('mesas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('pedido_id')
                ->references('id')->on('pedidos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('regimes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('codigo');
            $table->string('descricao', 200);

            $table->unique(['codigo']);
        });

        Schema::create('emitentes', function (Blueprint $table) {
            $table->enum('id', ['1'])->default('1');
            $table->unsignedInteger('contador_id')->nullable();
            $table->unsignedInteger('regime_id');
            $table->enum('ambiente', ['homologacao', 'producao'])->default('homologacao');
            $table->string('csc_teste', 100)->nullable();
            $table->string('csc', 100)->nullable();
            $table->string('token_teste', 10)->nullable();
            $table->string('token', 10)->nullable();
            $table->string('ibpt', 100)->nullable();
            $table->dateTime('data_expiracao')->nullable();

            $table->primary('id');
            $table->index(['contador_id']);
            $table->index(['regime_id']);
            $table->foreign('contador_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('regime_id')
                ->references('id')->on('regimes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('notas', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('tipo', ['nota', 'inutilizacao']);
            $table->enum('ambiente', ['homologacao', 'producao']);
            $table->enum('acao', ['autorizar', 'cancelar', 'inutilizar']);
            $table->enum(
                'estado',
                [
                    'aberto',
                    'assinado',
                    'pendente',
                    'processamento',
                    'denegado',
                    'rejeitado',
                    'cancelado',
                    'inutilizado',
                    'autorizado',
                ]
            );
            $table->unsignedInteger('ultimo_evento_id')->nullable();
            $table->integer('serie');
            $table->integer('numero_inicial');
            $table->integer('numero_final');
            $table->integer('sequencia');
            $table->string('chave', 50)->nullable();
            $table->string('recibo', 50)->nullable();
            $table->string('protocolo', 80)->nullable();
            $table->unsignedInteger('pedido_id')->nullable();
            $table->string('motivo', 255)->nullable();
            $table->boolean('contingencia');
            $table->string('consulta_url', 255)->nullable();
            $table->text('qrcode')->nullable();
            $table->decimal('tributos', 19, 4)->nullable();
            $table->string('detalhes', 255)->nullable();
            $table->boolean('corrigido')->default(true);
            $table->boolean('concluido')->default(false);
            $table->dateTime('data_autorizacao')->nullable();
            $table->dateTime('data_emissao');
            $table->dateTime('data_lancamento');
            $table->dateTime('data_arquivado')->nullable();

            $table->index(['pedido_id']);
            $table->index(['chave']);
            $table->index(['ultimo_evento_id']);
            $table->foreign('ultimo_evento_id')
                ->references('id')->on('eventos')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('pedido_id')
                ->references('id')->on('pedidos')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('eventos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('nota_id');
            $table->enum(
                'estado',
                [
                    'aberto',
                    'assinado',
                    'validado',
                    'pendente',
                    'processamento',
                    'denegado',
                    'cancelado',
                    'rejeitado',
                    'contingencia',
                    'inutilizado',
                    'autorizado',
                ]
            );
            $table->text('mensagem');
            $table->string('codigo', 20);
            $table->dateTime('data_criacao');

            $table->index(['nota_id']);
            $table->foreign('nota_id')
                ->references('id')->on('notas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('empresas', function (Blueprint $table) {
            $table->enum('id', ['1'])->default('1');
            $table->unsignedInteger('pais_id')->nullable();
            $table->unsignedInteger('empresa_id')->nullable();
            $table->unsignedInteger('parceiro_id')->nullable();
            $table->text('opcoes')->nullable();

            $table->primary('id');
            $table->index(['empresa_id']);
            $table->index(['parceiro_id']);
            $table->index(['pais_id']);
            $table->foreign('pais_id')
                ->references('id')->on('paises')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('empresa_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('parceiro_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('pontuacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('promocao_id');
            $table->unsignedInteger('cliente_id')->nullable();
            $table->unsignedInteger('pedido_id')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->integer('quantidade');
            $table->dateTime('data_cadastro');

            $table->index(['pedido_id']);
            $table->index(['item_id']);
            $table->index(['cliente_id']);
            $table->index(['promocao_id']);
            $table->foreign('promocao_id')
                ->references('id')->on('promocoes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('cliente_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('pedido_id')
                ->references('id')->on('pedidos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('item_id')
                ->references('id')->on('itens')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('telefones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cliente_id');
            $table->unsignedInteger('pais_id');
            $table->string('numero', 12);
            $table->string('operadora', 45)->nullable();
            $table->string('servico', 45)->nullable();
            $table->boolean('principal')->default(false);
            $table->string('codigo_verificacao', 10)->nullable();
            $table->integer('tentativas')->nullable();
            $table->dateTime('data_geracao')->nullable();
            $table->dateTime('data_envio')->nullable();
            $table->dateTime('data_validacao')->nullable();

            $table->unique(['codigo_verificacao']);
            $table->index(['cliente_id']);
            $table->index(['numero']);
            $table->index(['pais_id']);
            $table->foreign('cliente_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('pais_id')
                ->references('id')->on('paises')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('observacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('produto_id');
            $table->integer('grupo')->default(0);
            $table->string('descricao', 100);

            $table->unique(['produto_id', 'descricao']);
            $table->foreign('produto_id')
                ->references('id')->on('produtos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('cupons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cupom_id')->nullable();
            $table->unsignedInteger('pedido_id')->nullable();
            $table->unsignedInteger('cliente_id')->nullable();
            $table->string('codigo', 20);
            $table->integer('quantidade');
            $table->enum('tipo_desconto', ['valor', 'porcentagem']);
            $table->decimal('valor', 19, 4)->default(0);
            $table->double('porcentagem')->default(0);
            $table->boolean('incluir_servicos');
            $table->boolean('limitar_pedidos')->default(false);
            $table->enum('funcao_pedidos', ['menor', 'igual', 'maior'])->default('maior');
            $table->integer('pedidos_limite')->default(0);
            $table->boolean('limitar_valor')->default(false);
            $table->enum('funcao_valor', ['menor', 'igual', 'maior'])->default('maior');
            $table->decimal('valor_limite', 19, 4)->default(0);
            $table->dateTime('validade');
            $table->dateTime('data_registro');

            $table->index(['cupom_id']);
            $table->index(['pedido_id']);
            $table->index(['cliente_id']);
            $table->foreign('cupom_id')
                ->references('id')->on('cupons')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('pedido_id')
                ->references('id')->on('pedidos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('cliente_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('metricas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 100);
            $table->string('descricao', 200)->nullable();
            $table->enum('tipo', ['entrega', 'atendimento', 'producao', 'apresentacao']);
            $table->integer('quantidade')->default(100);
            $table->double('avaliacao')->nullable();
            $table->dateTime('data_processamento')->nullable();
            $table->dateTime('data_arquivado')->nullable();

            $table->unique(['nome']);
        });

        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('metrica_id');
            $table->unsignedInteger('cliente_id')->nullable();
            $table->unsignedInteger('pedido_id');
            $table->unsignedInteger('produto_id')->nullable();
            $table->integer('estrelas');
            $table->string('comentario', 255)->nullable();
            $table->dateTime('data_avaliacao');

            $table->index(['cliente_id']);
            $table->index(['metrica_id']);
            $table->index(['pedido_id']);
            $table->index(['produto_id']);
            $table->foreign('metrica_id')
                ->references('id')->on('metricas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('cliente_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('pedido_id')
                ->references('id')->on('pedidos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('produto_id')
                ->references('id')->on('produtos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('cardapios', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cozinha_id');
            $table->unsignedInteger('produto_id')->nullable();
            $table->unsignedInteger('composicao_id')->nullable();
            $table->unsignedInteger('pacote_id')->nullable();
            $table->unsignedInteger('cliente_id')->nullable();
            $table->unsignedInteger('integracao_id')->nullable();
            $table->enum(
                'local',
                [
                    'local',
                    'mesa',
                    'comanda',
                    'balcao',
                    'entrega',
                    'online',
                ]
            )->nullable();
            $table->decimal('acrescimo', 19, 4)->default(0);
            $table->boolean('disponivel')->default(true);

            $table->unique(
                [
                    'cozinha_id',
                    'produto_id',
                    'composicao_id',
                    'pacote_id',
                    'cliente_id',
                    'integracao_id',
                    'local',
                ],
                'item_destino_unique'
            );
            $table->index(['integracao_id']);
            $table->index(['composicao_id']);
            $table->index(['pacote_id']);
            $table->index(['cliente_id']);
            $table->index(['produto_id']);
            $table->foreign('cozinha_id')
                ->references('id')->on('cozinhas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('produto_id')
                ->references('id')->on('produtos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('composicao_id')
                ->references('id')->on('composicoes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('pacote_id')
                ->references('id')->on('pacotes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('cliente_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('integracao_id')
                ->references('id')->on('integracoes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('contagens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('produto_id');
            $table->unsignedInteger('setor_id');
            $table->double('quantidade');
            $table->dateTime('data_atualizacao')->nullable();

            $table->unique(['produto_id', 'setor_id']);
            $table->index(['setor_id']);
            $table->foreign('produto_id')
                ->references('id')->on('produtos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('setor_id')
                ->references('id')->on('setores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('notificacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('destinatario_id');
            $table->unsignedInteger('remetente_id')->nullable();
            $table->text('mensagem');
            $table->string('categoria', 50)->nullable();
            $table->string('redirecionar', 255)->nullable();
            $table->dateTime('data_visualizacao')->nullable();
            $table->dateTime('data_notificacao');

            $table->index(['destinatario_id']);
            $table->index(['remetente_id']);
            $table->foreign('destinatario_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('remetente_id')
                ->references('id')->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('saldos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('moeda_id');
            $table->unsignedInteger('carteira_id');
            $table->decimal('valor', 19, 4)->default(0);

            $table->unique(['moeda_id', 'carteira_id']);
            $table->index(['carteira_id']);
            $table->foreign('moeda_id')
                ->references('id')->on('moedas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('carteira_id')
                ->references('id')->on('carteiras')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('conferencias', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('funcionario_id');
            $table->integer('numero');
            $table->unsignedInteger('produto_id');
            $table->unsignedInteger('setor_id');
            $table->double('quantidade');
            $table->double('conferido');
            $table->dateTime('data_conferencia');

            $table->unique(['produto_id', 'setor_id', 'numero']);
            $table->index(['setor_id']);
            $table->index(['funcionario_id']);
            $table->foreign('funcionario_id')
                ->references('id')->on('prestadores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('produto_id')
                ->references('id')->on('produtos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('setor_id')
                ->references('id')->on('setores')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
        Schema::enableForeignKeyConstraints();

        if (env('APP_ENV') == 'testing') {
            (new SistemaSeeder())->run();
            (new MoedaSeeder())->run();
            (new PaisSeeder())->run();
            (new EmpresaSeeder())->run();
            (new ModuloSeeder())->run();
            (new FuncionalidadeSeeder())->run();
            (new PermissaoSeeder())->run();
        } elseif (env('APP_ENV') != 'testing') {
            (new DatabaseSeeder())->run();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('setores');
        Schema::dropIfExists('mesas');
        Schema::dropIfExists('cozinhas');
        Schema::dropIfExists('sessoes');
        Schema::dropIfExists('bancos');
        Schema::dropIfExists('carteiras');
        Schema::dropIfExists('caixas');
        Schema::dropIfExists('formas');
        Schema::dropIfExists('cartoes');
        Schema::dropIfExists('funcoes');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('prestadores');
        Schema::dropIfExists('moedas');
        Schema::dropIfExists('paises');
        Schema::dropIfExists('estados');
        Schema::dropIfExists('cidades');
        Schema::dropIfExists('bairros');
        Schema::dropIfExists('zonas');
        Schema::dropIfExists('localizacoes');
        Schema::dropIfExists('comandas');
        Schema::dropIfExists('viagens');
        Schema::dropIfExists('integracoes');
        Schema::dropIfExists('associacoes');
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('unidades');
        Schema::dropIfExists('origens');
        Schema::dropIfExists('operacoes');
        Schema::dropIfExists('impostos');
        Schema::dropIfExists('tributacoes');
        Schema::dropIfExists('produtos');
        Schema::dropIfExists('servicos');
        Schema::dropIfExists('classificacoes');
        Schema::dropIfExists('contas');
        Schema::dropIfExists('movimentacoes');
        Schema::dropIfExists('creditos');
        Schema::dropIfExists('cheques');
        Schema::dropIfExists('pagamentos');
        Schema::dropIfExists('itens');
        Schema::dropIfExists('modulos');
        Schema::dropIfExists('funcionalidades');
        Schema::dropIfExists('permissoes');
        Schema::dropIfExists('auditorias');
        Schema::dropIfExists('composicoes');
        Schema::dropIfExists('fornecedores');
        Schema::dropIfExists('compras');
        Schema::dropIfExists('estoques');
        Schema::dropIfExists('grupos');
        Schema::dropIfExists('propriedades');
        Schema::dropIfExists('pacotes');
        Schema::dropIfExists('dispositivos');
        Schema::dropIfExists('impressoras');
        Schema::dropIfExists('promocoes');
        Schema::dropIfExists('acessos');
        Schema::dropIfExists('catalogos');
        Schema::dropIfExists('sistemas');
        Schema::dropIfExists('resumos');
        Schema::dropIfExists('formacoes');
        Schema::dropIfExists('listas');
        Schema::dropIfExists('requisitos');
        Schema::dropIfExists('enderecos');
        Schema::dropIfExists('horarios');
        Schema::dropIfExists('patrimonios');
        Schema::dropIfExists('juncoes');
        Schema::dropIfExists('regimes');
        Schema::dropIfExists('emitentes');
        Schema::dropIfExists('notas');
        Schema::dropIfExists('eventos');
        Schema::dropIfExists('empresas');
        Schema::dropIfExists('pontuacoes');
        Schema::dropIfExists('telefones');
        Schema::dropIfExists('observacoes');
        Schema::dropIfExists('cupons');
        Schema::dropIfExists('metricas');
        Schema::dropIfExists('avaliacoes');
        Schema::dropIfExists('cardapios');
        Schema::dropIfExists('contagens');
        Schema::dropIfExists('notificacoes');
        Schema::dropIfExists('saldos');
        Schema::dropIfExists('conferencias');
        Schema::enableForeignKeyConstraints();
    }
}
