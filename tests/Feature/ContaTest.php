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
use App\Models\Dispositivo;
use App\Models\Forma;
use App\Models\Movimentacao;
use App\Models\Saldo;

class ContaTest extends TestCase
{
    // falta vereficar o saldo na criação da despesa
    public function testCreateContaReceita()
    {
        $headers = PrestadorTest::authOwner();
        $classificacao = factory(Classificacao::class)->create();
        $response = $this->graphfl('create_conta', [
            'input' => [
                'classificacao_id' => $classificacao->id,
                'descricao' => 'Teste',
                'valor' => 40,
                'vencimento' => '2020-12-25T12:15:00-02:00',
                'data_emissao' => '2020-12-25T12:15:00-02:00',
                'frequencia' => 1,
                'estado' => Conta::ESTADO_ATIVA,
                'tipo' => Conta::TIPO_RECEITA,
            ]
        ], $headers);

        $found_conta = Conta::findOrFail($response->json('data.CreateConta.id'));
        $this->assertEquals($classificacao->id, $found_conta->classificacao_id);
        $this->assertEquals('Teste', $found_conta->descricao);
        $this->assertEquals(40, $found_conta->valor);
        $this->assertEquals(1, $found_conta->frequencia);
    }

    public function testCreateContaDespesaPaga()
    {
        $dispositivoAuth = DispositivoTest::auth();
        $prestadorAuth = PrestadorTest::authOwner();
        $prestador = Prestador::first();
        $dispositivo = Dispositivo::first();
        $movimentacao = factory(Movimentacao::class)->create([
            'iniciador_id' => $prestador->id,
            'caixa_id' => $dispositivo->caixa_id,
            'aberta' => true
        ]);
        factory(Pagamento::class)->create([
            'movimentacao_id' => $movimentacao->id,
            'carteira_id' => $dispositivo->caixa->carteira_id,
            'estado' => Pagamento::ESTADO_PAGO,
            'valor' => 500,
            'lancado' => 500,
        ]);
        $headers = ClienteTest::mergeAuth($prestadorAuth, $dispositivoAuth);
        $classificacao = factory(Classificacao::class)->create();
        factory(Forma::class)->create();
        $response = $this->graphfl('create_despesa', [
            'input' => [
                'descricao' => 'Teste',
                'valor' => -40,
                'carteira_id' => $dispositivo->caixa->carteira_id,
            ]
        ], $headers);

        $found_conta = Conta::findOrFail($response->json('data.CreateDespesa.id'));
        $this->assertEquals('Teste', $found_conta->descricao);
        $this->assertEquals(-40, $found_conta->valor);
    }

    public function testCreateContaSemSaldo()
    {
        $dispositivoAuth = DispositivoTest::auth();
        $prestadorAuth = PrestadorTest::authOwner();
        $prestador = Prestador::first();
        $dispositivo = Dispositivo::first();
        $movimentacao = factory(Movimentacao::class)->create([
            'iniciador_id' => $prestador->id,
            'caixa_id' => $dispositivo->caixa_id,
            'aberta' => true
        ]);
        factory(Pagamento::class)->create([
            'movimentacao_id' => $movimentacao->id,
            'carteira_id' => $dispositivo->caixa->carteira_id,
            'estado' => Pagamento::ESTADO_PAGO,
            'valor' => 30,
            'lancado' => 30,
        ]);
        $headers = ClienteTest::mergeAuth($prestadorAuth, $dispositivoAuth);
        $classificacao = factory(Classificacao::class)->create();
        factory(Forma::class)->create();
        $this->expectException('Exception');
        $response = $this->graphfl('create_despesa', [
            'input' => [
                'descricao' => 'Teste',
                'valor' => 40 * -1,
                'carteira_id' => $dispositivo->caixa->carteira_id,
            ]
        ], $headers);
    }


    public function testUpdateConta()
    {
        $headers = PrestadorTest::authOwner();
        $conta = factory(Conta::class)->create();
        $this->graphfl('update_conta', [
            'id' => $conta->id,
            'input' => [
                'descricao' => 'Atualizou',
                'valor' => 50,
                'vencimento' => '2020-12-28T12:30:00Z',
            ]
        ], $headers);
        $conta->refresh();
        $this->assertEquals('Atualizou', $conta->descricao);
        $this->assertEquals(50, $conta->valor);
        $this->assertEquals('2020-12-28 12:30:00', $conta->vencimento);
    }

    public function testDeleteConta()
    {
        $headers = PrestadorTest::authOwner();
        $conta_to_delete = factory(Conta::class)->create();
        $this->graphfl('delete_conta', ['id' => $conta_to_delete->id], $headers);
        $conta = Conta::find($conta_to_delete->id);
        $this->assertNull($conta);
    }

    public function testFindConta()
    {
        $headers = PrestadorTest::authOwner();
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
        $headers = PrestadorTest::authOwner();
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
        $pagamento_pai = factory(Pagamento::class)->create([
            'carteira_id' => $conta_pai->carteira_id,
            'moeda_id' => $pais->moeda_id,
            'valor' => $conta_pai->valor,
            'lancado' => $conta_pai->valor,
            'estado' => Pagamento::ESTADO_PAGO,
            'conta_id' => $conta_pai->id,
            'data_pagamento' => Carbon::now(),
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
        $headers = PrestadorTest::authOwner();
        $this->graphfl('update_conta', [
            'id' => $conta_pai->id,
            'input' => [
                'estado' => Conta::ESTADO_CANCELADA,
            ],
        ], $headers);
        $conta_pai->refresh();
        $conta1->refresh();
        $conta2->refresh();
        $pagamento_pai->refresh();
        $pagamento1->refresh();
        $pagamento2->refresh();
        $this->assertEquals($conta_pai->estado, Conta::ESTADO_CANCELADA);
        $this->assertEquals($pagamento_pai->estado, Pagamento::ESTADO_CANCELADO);
        $this->assertEquals($conta1->agrupamento_id, null);
        $this->assertEquals($conta2->agrupamento_id, null);
    }
}
