<?php

/**
 * Copyright 2014 da GrandChef - GrandChef Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Restaurantes e Afins.
 * O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
 * DISPOSIÇÕES GERAIS
 * O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
 * ou outros avisos ou restrições de propriedade do GrandChef.
 *
 * O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
 * ou descompilação do GrandChef.
 *
 * PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA
 *
 * GrandChef é a especialidade do desenvolvedor e seus
 * licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
 * de leis de propriedade.
 *
 * O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
 * direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
 *
 * @author Equipe GrandChef <desenvolvimento@grandchef.com.br>
 */

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pais;
use App\Models\Conta;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Prestador;
use App\Models\Pagamento;
use App\Models\Classificacao;
use Illuminate\Support\Carbon;
use App\Exceptions\ValidationException;
use App\Models\Carteira;

class ContaTest extends TestCase
{
    public function testCreateConta()
    {
        $headers = PrestadorTest::auth();
        $classificacao = factory(Classificacao::class)->create();
        $response = $this->graphfl('create_conta', [
            'input' => [
                'classificacao_id' => $classificacao->id,
                'descricao' => 'Teste',
                'valor' => 40,
                'vencimento' => '2020-12-25 12:15:00',
                'data_emissao' => '2019-10-25 12:15:00',
                'frequencia' => 1,
            ]
        ], $headers);

        $found_conta = Conta::findOrFail($response->json('data.CreateConta.id'));
        $this->assertEquals($classificacao->id, $found_conta->classificacao_id);
        $this->assertEquals('Teste', $found_conta->descricao);
        $this->assertEquals(40, $found_conta->valor);
        $this->assertEquals(1, $found_conta->frequencia);
        $this->assertEquals('2020-12-25 12:15:00', $found_conta->vencimento);
        $this->assertEquals('2019-10-25 12:15:00', $found_conta->data_emissao);
    }

    public function testUpdateConta()
    {
        $headers = PrestadorTest::auth();
        $conta = factory(Conta::class)->create();
        $this->graphfl('update_conta', [
            'id' => $conta->id,
            'input' => [
                'descricao' => 'Atualizou',
                'valor' => 50,
                'vencimento' => '2020-12-28 12:30:00',
                'data_emissao' => '2019-12-28 12:30:00',
            ]
        ], $headers);
        $conta->refresh();
        $this->assertEquals('Atualizou', $conta->descricao);
        $this->assertEquals(50, $conta->valor);
        $this->assertEquals('2020-12-28 12:30:00', $conta->vencimento);
        $this->assertEquals('2019-12-28 12:30:00', $conta->data_emissao);
    }

    public function testDeleteConta()
    {
        $headers = PrestadorTest::auth();
        $conta_to_delete = factory(Conta::class)->create();
        $this->graphfl('delete_conta', ['id' => $conta_to_delete->id], $headers);
        $conta = Conta::find($conta_to_delete->id);
        $this->assertNull($conta);
    }

    public function testFindConta()
    {
        $headers = PrestadorTest::auth();
        $conta = factory(Conta::class)->create();
        $response = $this->graphfl('query_conta', [ 'id' => $conta->id ], $headers);
        $this->assertEquals($conta->id, $response->json('data.contas.data.0.id'));
    }

    public function testValorMaiorZero()
    {
        $this->expectException(ValidationException::class);
        factory(Conta::class)->create([
            'valor' => 0,
        ]);
    }

    public function testReceitaValorNegativo()
    {
        $this->expectException(ValidationException::class);
        factory(Conta::class)->create([
            'valor' => -1,
            'tipo' => Conta::TIPO_RECEITA,
        ]);
    }

    public function testDespesaValorPositivo()
    {
        $this->expectException(ValidationException::class);
        factory(Conta::class)->create([
            'valor' => 30,
            'tipo' => Conta::TIPO_DESPESA,
        ]);
    }

    public function testReceitaAcrescimoNegativo()
    {
        $this->expectException(ValidationException::class);
        factory(Conta::class)->create([
            'acrescimo' => -2,
            'tipo' => Conta::TIPO_RECEITA,
        ]);
    }

    public function testDespesaAcrescimoPositivo()
    {
        $this->expectException(ValidationException::class);
        factory(Conta::class)->create([
            'acrescimo' => 2,
            'tipo' => Conta::TIPO_DESPESA,
        ]);
    }

    public function testReceitaMultaNegativa()
    {
        $this->expectException(ValidationException::class);
        factory(Conta::class)->create([
            'multa' => -2,
            'tipo' => Conta::TIPO_RECEITA,
        ]);
    }

    public function testDespesaMultaPositiva()
    {
        $this->expectException(ValidationException::class);
        factory(Conta::class)->create([
            'multa' => 2,
            'tipo' => Conta::TIPO_DESPESA,
        ]);
    }

    public function testJurosNegativo()
    {
        $this->expectException(ValidationException::class);
        factory(Conta::class)->create([
            'juros' => -2,
        ]);
    }

    public function testContaGrandSon()
    {
        $old_conta = factory(Conta::class)->create();
        $seed_conta = factory(Conta::class)->create([
            'conta_id' => $old_conta->id,
        ]);
        $this->expectException(ValidationException::class);
        $conta = factory(Conta::class)->create([
            'conta_id' => $seed_conta->id,
        ]);
    }

    public function testAgrupamentoCancelado()
    {
        $seed_conta = factory(Conta::class)->create();
        $conta = factory(Conta::class)->create([
            'estado' => Conta::ESTADO_CANCELADA,
            'agrupamento_id' => $seed_conta->id,
        ]);
        $this->expectException(ValidationException::class);
        factory(Conta::class)->create([
            'agrupamento_id' => $conta->id,
        ]);
    }

    public function testContaEstadoPago()
    {
        $conta = factory(Conta::class)->create([
            'estado' => Conta::ESTADO_PAGA,
        ]);
        $conta->estado = Conta::ESTADO_ANALISE;
        $this->expectException(ValidationException::class);
        $conta->save();
    }

    public function testContaEstadoCancelada()
    {
        $conta = factory(Conta::class)->create([
            'estado' => Conta::ESTADO_CANCELADA,
        ]);
        $conta->estado = Conta::ESTADO_ATIVA;
        $this->expectException(ValidationException::class);
        $conta->save();
    }

    public function testContaVerificacaoValor()
    {
        $conta = factory(Conta::class)->create([
            'estado' => Conta::ESTADO_ATIVA,
        ]);
        $conta->estado = Conta::ESTADO_PAGA;
        $conta->consolidado = 10;
        $conta->valor = 20;
        $this->expectException(ValidationException::class);
        $conta->save();
    }

    public function testContaVerificacaoAtualValor()
    {
        $conta = factory(Conta::class)->create([
            'estado' => Conta::ESTADO_PAGA,
            'consolidado' => 10,
            'valor' => 20,
        ]);
        $this->expectException(ValidationException::class);
        $conta->estado = Conta::ESTADO_ATIVA;
        $conta->save();
    }

    public function testContaLimiteCompra()
    {
        $cliente = factory(Cliente::class)->create([
            'limite_compra' => 3,
        ]);
        $this->expectException(ValidationException::class);
        $conta = factory(Conta::class)->create([
            'cliente_id' => $cliente->id,
            'valor' => 10,
        ]);
    }

    public function testContaAgrupamento()
    {
        $old_conta = factory(Conta::class)->create();
        $conta = factory(Conta::class)->create();
        $conta->agrupamento_id = $old_conta->id;
        $conta->save();
        $seed_conta = $old_conta = factory(Conta::class)->create();
        $conta->agrupamento_id = $seed_conta->id;
        $this->expectException(ValidationException::class);
        $conta->save();
    }

    public function testPedidoDiferenteCancelado()
    {
        $pedido = factory(Pedido::class)->create([
            'estado' => Pedido::ESTADO_ABERTO,
        ]);
        $this->expectException(ValidationException::class);
        factory(Conta::class)->create([
            'pedido_id' => $pedido->id,
            'estado' => Conta::ESTADO_CANCELADA,
        ]);
    }

    public function testContaReceitaAutomatica()
    {
        $pedido = factory(Pedido::class)->create();
        $pedido->update(['estado' => Pedido::ESTADO_CANCELADO]);
        $this->expectException(ValidationException::class);
        factory(Conta::class)->create([
            'pedido_id' => $pedido->id,
            'automatico' => true,
            'tipo' => Conta::TIPO_DESPESA,
        ]);
    }

    public function testFrequenciaNegativaOuZero()
    {
        $this->expectException(ValidationException::class);
        factory(Conta::class)->create([
            'modo' => Conta::MODO_DIARIO,
            'frequencia' => -1,
        ]);
    }

    public function testCancelarConta()
    {
        $conta_pai = factory(Conta::class)->create();
        $carteira = factory(Carteira::class)->create();
        $conta = factory(Conta::class)->create([
            'agrupamento_id' => $conta_pai->id,
            'carteira_id' => $carteira->id,
        ]);
        $pais = factory(Pais::class)->create();
        $pagamento = factory(Pagamento::class)->create([
            'carteira_id' => $conta->carteira_id,
            'moeda_id' => $pais->moeda_id,
            'valor' => $conta->valor,
            'lancado' => $conta->valor,
            'estado' => Pagamento::ESTADO_PAGO,
            'conta_id' => $conta_pai->id,
            'data_pagamento' => Carbon::now(),
        ]);
        $headers = PrestadorTest::auth();
        $this->graphfl('update_conta', [
            'id' => $conta_pai->id,
            'input' => [
                'estado' => Conta::ESTADO_CANCELADA,
            ]
        ], $headers);
        $conta_pai->refresh();
        $pagamento->refresh();
        $this->assertEquals($conta->estado, Conta::ESTADO_ATIVA);
        $this->assertEquals($conta_pai->estado, Conta::ESTADO_CANCELADA);
        $this->assertEquals($pagamento->estado, Pagamento::ESTADO_CANCELADO);
    }

    public function testContaCancelaRecursive()
    {
        $conta_pai = factory(Conta::class)->create();
        $carteira = factory(Carteira::class)->create();
        $pais = factory(Pais::class)->create();
        $conta1 = factory(Conta::class)->create([
            'agrupamento_id' => $conta_pai->id,
            'carteira_id' => $carteira->id,
        ]);
        $conta2 = factory(Conta::class)->create([
            'agrupamento_id' => $conta_pai->id,
            'carteira_id' => $carteira->id,
        ]);
        $pagamento1 = factory(Pagamento::class)->create([
            'carteira_id' => $conta1->carteira_id,
            'moeda_id' => $pais->moeda_id,
            'valor' => $conta1->valor,
            'lancado' => $conta1->valor,
            'estado' => Pagamento::ESTADO_PAGO,
            'conta_id' => $conta1->id,
            'data_pagamento' => Carbon::now(),
        ]);
        $pagamento2 = factory(Pagamento::class)->create([
            'carteira_id' => $conta2->carteira_id,
            'moeda_id' => $pais->moeda_id,
            'valor' => $conta2->valor,
            'lancado' => $conta2->valor,
            'estado' => Pagamento::ESTADO_PAGO,
            'conta_id' => $conta2->id,
            'data_pagamento' => Carbon::now(),
        ]);
        $headers = PrestadorTest::auth();
        $this->graphfl('update_conta', [
            'id' => $conta_pai->id,
            'input' => [
                'estado' => Conta::ESTADO_CANCELADA,
            ],
            'desagrupar' => true,
        ], $headers);
        $conta_pai->refresh();
        $conta1->refresh();
        $conta2->refresh();
        $pagamento1->refresh();
        $pagamento2->refresh();
        $this->assertEquals($conta_pai->estado, Conta::ESTADO_CANCELADA);
        $this->assertEquals($conta1->agrupamento_id, null);
        $this->assertEquals($conta2->agrupamento_id, null);
    }
}
