<?php

/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
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
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
 */

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Cardapio;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CardapioTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCardapio()
    {
        $headers = PrestadorTest::auth();
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
        $headers = PrestadorTest::auth();
        $cardapio = factory(Cardapio::class)->create();
        $this->graphfl('update_cardapio', [
            'id' => $cardapio->id,
            'input' => [
                'produto_id' => $cardapio->produto_id,
            ]
        ], $headers);
        $cardapio->refresh();
        $this->assertEquals(1, $cardapio->id);
    }

    public function testDeleteCardapio()
    {
        $headers = PrestadorTest::auth();
        $cardapio_to_delete = factory(Cardapio::class)->create();
        $this->graphfl('delete_cardapio', ['id' => $cardapio_to_delete->id], $headers);
        $cardapio = Cardapio::find($cardapio_to_delete->id);
        $this->assertNull($cardapio);
    }

    public function testFindCardapio()
    {
        $headers = PrestadorTest::auth();
        $cardapio = factory(Cardapio::class)->create();
        $response = $this->graphfl('query_cardapio', [ 'id' => $cardapio->id ], $headers);
        $this->assertEquals($cardapio->id, $response->json('data.cardapios.data.0.id'));
    }
}
