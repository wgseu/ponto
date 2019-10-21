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
use App\Models\Cartao;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartaoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCartao()
    {
        $headers = PrestadorTest::auth();
        $seed_cartao =  factory(Cartao::class)->create();
        $response = $this->graphfl('create_cartao', [
            'input' => [
                'forma_id' => $seed_cartao->forma_id,
                'bandeira' => 'Teste',
            ]
        ], $headers);

        $found_cartao = Cartao::findOrFail($response->json('data.CreateCartao.id'));
        $this->assertEquals($seed_cartao->forma_id, $found_cartao->forma_id);
        $this->assertEquals('Teste', $found_cartao->bandeira);
    }

    public function testUpdateCartao()
    {
        $headers = PrestadorTest::auth();
        $cartao = factory(Cartao::class)->create();
        $this->graphfl('update_cartao', [
            'id' => $cartao->id,
            'input' => [
                'bandeira' => 'Atualizou',
            ]
        ], $headers);
        $cartao->refresh();
        $this->assertEquals('Atualizou', $cartao->bandeira);
    }

    public function testDeleteCartao()
    {
        $headers = PrestadorTest::auth();
        $cartao_to_delete = factory(Cartao::class)->create();
        $cartao_to_delete = $this->graphfl('delete_cartao', ['id' => $cartao_to_delete->id], $headers);
        $cartao = Cartao::find($cartao_to_delete->id);
        $this->assertNull($cartao);
    }

    public function testQueryCartao()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Cartao::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_cartao', [], $headers);
        $this->assertEquals(10, $response->json('data.cartoes.total'));
    }
}