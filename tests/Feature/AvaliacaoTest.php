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
use App\Models\Pedido;
use App\Models\Metrica;
use App\Models\Produto;
use App\Models\Cliente;
use App\Models\Avaliacao;
use Illuminate\Support\Carbon;
use App\Exceptions\ValidationException;

class AvaliacaoTest extends TestCase
{
    public function testCreateAvaliacao()
    {
        $headers = PrestadorTest::authOwner();
        $cliente = Cliente::where('status', Cliente::STATUS_ATIVO)->first();
        $metrica = factory(Metrica::class)->create();
        $pedido = factory(Pedido::class)->create([
            'cliente_id' => $cliente->id,
            'data_criacao' => Carbon::now(),
        ]);
        $response = $this->graphfl('create_avaliacao', [
            'input' => [
                'pedido_id' => $pedido->id,
                'metrica_id' => $metrica->id,
                'estrelas' => 1,
                'data_avaliacao' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $found_avaliacao = Avaliacao::findOrFail($response->json('data.CreateAvaliacao.id'));
        $this->assertEquals($pedido->id, $found_avaliacao->pedido_id);
        $this->assertEquals($metrica->id, $found_avaliacao->metrica_id);
        $this->assertEquals(1, $found_avaliacao->estrelas);
        $this->assertEquals('2016-12-25 12:15:00', $found_avaliacao->data_avaliacao);
    }

    public function testUpdateAvaliacao()
    {
        $headers = PrestadorTest::authOwner();
        $cliente = Cliente::where('status', Cliente::STATUS_ATIVO)->first();
        $metrica = factory(Metrica::class)->create();
        $pedido = factory(Pedido::class)->create([
            'cliente_id' => $cliente->id,
            'data_criacao' => Carbon::now(),
        ]);
        $response = $this->graphfl('create_avaliacao', [
            'input' => [
                'pedido_id' => $pedido->id,
                'metrica_id' => $metrica->id,
                'estrelas' => 1,
                'data_avaliacao' => '2016-12-25 12:15:00',
            ]
        ], $headers);
        $avaliacao = Avaliacao::findOrFail($response->json('data.CreateAvaliacao.id'));
        $this->graphfl('update_avaliacao', [
            'id' => $avaliacao->id,
            'input' => [
                'estrelas' => 2,
                'data_avaliacao' => '2016-12-28 12:30:00',
            ]
        ], $headers);
        $avaliacao->refresh();
        $this->assertEquals(2, $avaliacao->estrelas);
        $this->assertEquals('2016-12-28 12:30:00', $avaliacao->data_avaliacao);
    }

    public function testDeleteAvaliacao()
    {
        $headers = PrestadorTest::authOwner();
        $cliente = Cliente::where('status', Cliente::STATUS_ATIVO)->first();
        $metrica = factory(Metrica::class)->create();
        $pedido = factory(Pedido::class)->create([
            'cliente_id' => $cliente->id,
            'data_criacao' => Carbon::now(),
        ]);
        $avaliacao = factory(Avaliacao::class)->create([
            'cliente_id' => $cliente->id,
            'pedido_id' => $pedido->id,
            'metrica_id' => $metrica->id,
            'data_avaliacao' => '2016-12-25 12:15:00',
        ]);
        $this->graphfl('delete_avaliacao', ['id' => $avaliacao->id], $headers);
        $avaliacao = Avaliacao::find($avaliacao->id);
        $this->assertNull($avaliacao);
    }

    public function testFindAvaliacao()
    {
        $headers = PrestadorTest::authOwner();
        $cliente = Cliente::where('status', Cliente::STATUS_ATIVO)->first();
        $metrica = factory(Metrica::class)->create();
        $pedido = factory(Pedido::class)->create([
            'cliente_id' => $cliente->id,
            'data_criacao' => Carbon::now(),
        ]);
        $avaliacao = factory(Avaliacao::class)->create([
            'cliente_id' => $cliente->id,
            'pedido_id' => $pedido->id,
            'metrica_id' => $metrica->id,
            'data_avaliacao' => '2016-12-25 12:15:00',
        ]);
        $response = $this->graphfl('query_avaliacao', [ 'id' => $avaliacao->id ], $headers);
        $this->assertEquals($avaliacao->id, $response->json('data.avaliacoes.data.0.id'));
    }

    public function testAvaliacaoProduto()
    {
        $headers = PrestadorTest::authOwner();
        $cliente = Cliente::where('status', Cliente::STATUS_ATIVO)->first();
        $metrica = factory(Metrica::class)->create();
        $pedido = factory(Pedido::class)->create([
            'cliente_id' => $cliente->id,
            'data_criacao' => Carbon::now(),
        ]);
        $produto = factory(Produto::class)->create();
        $response = $this->graphfl('create_avaliacao', [
            'input' => [
                'metrica_id' => $metrica->id,
                'produto_id' => $produto->id,
                'pedido_id' => $pedido->id,
                'estrelas' => 1,
                'data_avaliacao' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $avaliacao = Avaliacao::findOrFail($response->json('data.CreateAvaliacao.id'));
        $this->assertEquals($produto->id, $avaliacao->produto_id);
        $this->assertEquals($avaliacao->cliente_id, $cliente->id);
        $this->assertEquals($avaliacao->pedido_id, $pedido->id);
    }

    public function testAvaliacaoDuplicada()
    {
        $headers = PrestadorTest::authOwner();
        $cliente = Cliente::where('status', Cliente::STATUS_ATIVO)->first();
        $metrica = factory(Metrica::class)->create();
        $pedido = factory(Pedido::class)->create([
            'cliente_id' => $cliente->id,
            'data_criacao' => Carbon::now(),
        ]);
        $this->graphfl('create_avaliacao', [
            'input' => [
                'pedido_id' => $pedido->id,
                'metrica_id' => $metrica->id,
                'estrelas' => 1,
                'data_avaliacao' => '2016-12-25 12:15:00',
            ]
        ], $headers);
        $this->expectException(ValidationException::class);
        factory(Avaliacao::class)->create([
            'pedido_id' => $pedido->id,
            'metrica_id' => $metrica->id,
            'estrelas' => 1,
            'data_avaliacao' => '2016-12-25 12:15:00',
        ]);
    }

    public function testAvaliacaoAposSeteDias()
    {
        PrestadorTest::authOwner();
        $cliente = Cliente::where('status', Cliente::STATUS_ATIVO)->first();
        $metrica = factory(Metrica::class)->create();
        $pedido = factory(Pedido::class)->create([
            'cliente_id' => $cliente->id,
            'data_criacao' => '2019-11-20 12:15:00',
        ]);
        $this->expectException(ValidationException::class);
        factory(Avaliacao::class)->create([
            'pedido_id' => $pedido->id,
            'metrica_id' => $metrica->id,
            'cliente_id' => $cliente->id,
            'estrelas' => 1,
            'data_avaliacao' => Carbon::now(),
        ]);
    }
}
