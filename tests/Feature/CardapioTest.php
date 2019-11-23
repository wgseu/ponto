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

use App\Exceptions\ValidationException;
use Tests\TestCase;
use App\Models\Cardapio;
use App\Models\Cliente;
use App\Models\Composicao;
use App\Models\Integracao;
use App\Models\Pacote;
use App\Models\Produto;

class CardapioTest extends TestCase
{
    public function testCreateCardapio()
    {
        $headers = PrestadorTest::authOwner();
        $seed_cardapio =  factory(Cardapio::class)->create();
        $response = $this->graphfl('create_cardapio', [
            'input' => [
                'produto_id' => $seed_cardapio->produto_id,
            ]
        ], $headers);

        $found_cardapio = Cardapio::findOrFail($response->json('data.CreateCardapio.id'));
        $this->assertEquals($seed_cardapio->produto_id, $found_cardapio->produto_id);
    }

    public function testUpdateCardapio()
    {
        $headers = PrestadorTest::authOwner();
        $pacote = factory(Pacote::class)->create();
        $integracao = factory(Integracao::class)->create();
        $cardapio = factory(Cardapio::class)->create([
            'pacote_id' => $pacote->id,
            'produto_id' => null,
            'integracao_id' => $integracao->id
        ]);
        $this->graphfl('update_cardapio', [
            'id' => $cardapio->id,
            'input' => [
                'produto_id' => $cardapio->produto_id,
            ]
        ], $headers);
        $cardapio->refresh();
        $this->assertEquals($pacote->id, $cardapio->pacote->id);
        $this->assertEquals($integracao->id, $cardapio->integracao->id);
        $this->assertEquals(1, $cardapio->id);
    }

    public function testDeleteCardapio()
    {
        $headers = PrestadorTest::authOwner();
        $cardapio_to_delete = factory(Cardapio::class)->create();
        $this->graphfl('delete_cardapio', ['id' => $cardapio_to_delete->id], $headers);
        $cardapio = Cardapio::find($cardapio_to_delete->id);
        $this->assertNull($cardapio);
    }

    public function testFindCardapio()
    {
        $headers = PrestadorTest::authOwner();
        $composicao = factory(Composicao::class)->create();
        $cliente = factory(Cliente::class)->create();
        $cardapio = factory(Cardapio::class)->create([
            'composicao_id' => $composicao->id,
            'produto_id' => null,
            'cliente_id' => $cliente->id
        ]);
        $response = $this->graphfl('query_cardapio', [ 'id' => $cardapio->id ], $headers);
        $this->assertEquals($composicao->id, $cardapio->composicao->id);
        $this->assertEquals($cliente->id, $cardapio->cliente->id);
        $this->assertEquals($cardapio->id, $response->json('data.cardapios.data.0.id'));
    }

    public function testInserirProdutoEPacote()
    {
        $produto = factory(Produto::class)->create();
        $pacote = factory(Pacote::class)->create();
        $this->expectException(ValidationException::class);
        factory(Cardapio::class)->create(['pacote_id' => $pacote->id, 'produto_id' => $produto->id]);
    }

    public function testDescontoMaiorQuePrecoVenda()
    {
        $produto = factory(Produto::class)->create(['preco_venda' => 10]);
        $this->expectException(ValidationException::class);
        factory(Cardapio::class)->create(['produto_id' => $produto->id, 'acrescimo' => -11]);
    }

    public function testInserirClienteEIntegracao()
    {
        $cliente = factory(Cliente::class)->create();
        $integracao = factory(Integracao::class)->create();
        $this->expectException(ValidationException::class);
        factory(Cardapio::class)->create(['cliente_id' => $cliente->id, 'integracao_id' => $integracao->id]);
    }
}
