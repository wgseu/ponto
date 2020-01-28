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
use App\Models\Item;

class AvaliacaoTest extends TestCase
{
    public function testCreate()
    {
        $cliente = factory(Cliente::class)->create();
        $headers = ClienteTest::auth($cliente);

        // avaliação do pedido 1
        $pedido = factory(Pedido::class)->create([
            'cliente_id' => $cliente->id,
        ]);
        $pedido->update(['estado' => Pedido::ESTADO_FECHADO]);
        $subavaliacoes = [
            factory(Avaliacao::class)->raw(),
            factory(Avaliacao::class)->raw(),
            factory(Avaliacao::class)->raw(),
        ];
        $response = $this->graphfl('create_avaliacao', [
            'input' => [
                'pedido_id' => $pedido->id,
                'subavaliacoes' => $subavaliacoes,
            ]
        ], $headers);

        $found_avaliacao = Avaliacao::findOrFail($response->json('data.CreateAvaliacao.id'));
        $estrelas = array_reduce($subavaliacoes, function ($sum, $data) {
            return $sum + $data['estrelas'];
        }, 0) / count($subavaliacoes);
        $this->assertNull($found_avaliacao->metrica_id);
        $this->assertEquals($estrelas, $found_avaliacao->estrelas);

        // avaliação do pedido 2
        $pedido = factory(Pedido::class)->create([
            'cliente_id' => $cliente->id,
        ]);
        $pedido->update(['estado' => Pedido::ESTADO_FECHADO]);
        $subavaliacoes = array_map(function ($item) {
            return array_merge($item, [
                'estrelas' => 6 - $item['estrelas'],
            ]);
        }, $subavaliacoes);
        $response = $this->graphfl('create_avaliacao', [
            'input' => [
                'pedido_id' => $pedido->id,
                'subavaliacoes' => $subavaliacoes,
            ]
        ], $headers);

        foreach ($subavaliacoes as $data) {
            $metrica = Metrica::findOrFail($data['metrica_id']);
            $avaliacao = (6 - $data['estrelas'] + $data['estrelas']) / 2;
            $this->assertEquals($avaliacao, $metrica->avaliacao);
        }
    }

    public function testUpdate()
    {
        $cliente = factory(Cliente::class)->create();
        $headers = ClienteTest::auth($cliente);
        $pedido = factory(Pedido::class)->create([
            'cliente_id' => $cliente->id,
        ]);
        $pedido->update(['estado' => Pedido::ESTADO_FECHADO]);
        $subavaliacoes = [
            factory(Avaliacao::class)->raw(),
            factory(Avaliacao::class)->raw(),
            factory(Avaliacao::class)->raw(),
        ];
        $response = $this->graphfl('create_avaliacao', [
            'input' => [
                'pedido_id' => $pedido->id,
                'subavaliacoes' => $subavaliacoes,
            ]
        ], $headers);

        $id = $response->json('data.CreateAvaliacao.id');
        $subavaliacoes = Avaliacao::where('pedido_id', $pedido->id)
            ->whereNotNull('metrica_id')
            ->get()->toArray();
        $subavaliacoes = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'metrica_id' => $item['metrica_id'],
                'estrelas' => 6 - $item['estrelas'],
            ];
        }, $subavaliacoes);
        $this->graphfl('update_avaliacao', [
            'id' => $id,
            'input' => [
                'subavaliacoes' => $subavaliacoes,
            ]
        ], $headers);

        foreach ($subavaliacoes as $data) {
            $metrica = Metrica::findOrFail($data['metrica_id']);
            $avaliacao = $data['estrelas'];
            $this->assertEquals($avaliacao, $metrica->avaliacao);
        }
    }

    public function testFindAvaliacao()
    {
        $headers = PrestadorTest::authOwner();
        $cliente = Cliente::where('status', Cliente::STATUS_ATIVO)->first();
        $metrica = factory(Metrica::class)->create();
        $pedido = factory(Pedido::class)->create([
            'cliente_id' => $cliente->id,
        ]);
        $pedido->update(['estado' => Pedido::ESTADO_FECHADO]);
        $avaliacao = factory(Avaliacao::class)->create([
            'cliente_id' => $cliente->id,
            'pedido_id' => $pedido->id,
            'metrica_id' => $metrica->id,
        ]);
        $response = $this->graphfl('query_avaliacao', [ 'id' => $avaliacao->id ], $headers);
        $this->assertEquals($avaliacao->id, $response->json('data.avaliacoes.data.0.id'));
    }

    public function testAvaliacaoProduto()
    {
        $cliente = factory(Cliente::class)->create();
        $headers = ClienteTest::auth($cliente);
        $pedido = factory(Pedido::class)->create([
            'cliente_id' => $cliente->id,
        ]);
        $item = factory(Item::class)->create([
            'pedido_id' => $pedido->id,
        ]);
        $pedido->update(['estado' => Pedido::ESTADO_FECHADO]);
        $subavaliacoes = [
            factory(Avaliacao::class)->raw([
                'produto_id' => $item->produto_id,
            ]),
        ];
        $response = $this->graphfl('create_avaliacao', [
            'input' => [
                'pedido_id' => $item->pedido_id,
                'subavaliacoes' => $subavaliacoes,
            ]
        ], $headers);

        $found_avaliacao = Avaliacao::findOrFail($response->json('data.CreateAvaliacao.id'));
        $estrelas = array_reduce($subavaliacoes, function ($sum, $data) {
            return $sum + $data['estrelas'];
        }, 0) / count($subavaliacoes);
        $this->assertEquals($estrelas, $found_avaliacao->estrelas);
        $produto = $item->produto;
        $produto->refresh(); // bug, cached old relation
        $this->assertEquals($estrelas, $produto->avaliacao);
    }

    public function testAvaliacaoDuplicada()
    {
        $cliente = factory(Cliente::class)->create();
        $pedido = factory(Pedido::class)->create([
            'cliente_id' => $cliente->id,
        ]);
        $pedido->update(['estado' => Pedido::ESTADO_FECHADO]);
        $metrica = factory(Metrica::class)->create();
        $data = [
            'pedido_id' => $pedido->id,
            'metrica_id' => $metrica->id,
            'cliente_id' => $cliente->id,
            'estrelas' => 1,
            'data_avaliacao' => Carbon::now(),
        ];
        factory(Avaliacao::class)->create($data);
        $this->expectException(ValidationException::class);
        factory(Avaliacao::class)->create($data);
    }

    public function testAvaliacaoAposTresDias()
    {
        PrestadorTest::authOwner();
        $cliente = Cliente::where('status', Cliente::STATUS_ATIVO)->first();
        $metrica = factory(Metrica::class)->create();
        $pedido = factory(Pedido::class)->create([
            'cliente_id' => $cliente->id,
        ]);
        $pedido->forceFill([
            'estado' => Pedido::ESTADO_FECHADO,
            'data_criacao' => Carbon::now()->subDays(4),
        ])->save();
        $this->expectException(ValidationException::class);
        factory(Avaliacao::class)->create([
            'pedido_id' => $pedido->id,
            'metrica_id' => $metrica->id,
            'cliente_id' => $cliente->id,
            'estrelas' => 1,
        ]);
    }
}
