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
use App\Models\Estoque;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EstoqueTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateEstoque()
    {
        $headers = PrestadorTest::auth();
        $seed_estoque =  factory(Estoque::class)->create();
        $response = $this->graphfl('create_estoque', [
            'input' => [
                'produto_id' => $seed_estoque->produto_id,
                'setor_id' => $seed_estoque->setor_id,
                'quantidade' => 1.0,
                'data_movimento' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $found_estoque = Estoque::findOrFail($response->json('data.CreateEstoque.id'));
        $this->assertEquals($seed_estoque->produto_id, $found_estoque->produto_id);
        $this->assertEquals($seed_estoque->setor_id, $found_estoque->setor_id);
        $this->assertEquals(1.0, $found_estoque->quantidade);
        $this->assertEquals('2016-12-25 12:15:00', $found_estoque->data_movimento);
    }

    public function testUpdateEstoque()
    {
        $headers = PrestadorTest::auth();
        $estoque = factory(Estoque::class)->create();
        $this->graphfl('update_estoque', [
            'id' => $estoque->id,
            'input' => [
                'quantidade' => 1.0,
                'data_movimento' => '2016-12-28 12:30:00',
            ]
        ], $headers);
        $estoque->refresh();
        $this->assertEquals(1.0, $estoque->quantidade);
        $this->assertEquals('2016-12-28 12:30:00', $estoque->data_movimento);
    }

    public function testDeleteEstoque()
    {
        $headers = PrestadorTest::auth();
        $estoque_to_delete = factory(Estoque::class)->create();
        $estoque_to_delete = $this->graphfl('delete_estoque', ['id' => $estoque_to_delete->id], $headers);
        $estoque = Estoque::find($estoque_to_delete->id);
        $this->assertNull($estoque);
    }

    public function testFindEstoque()
    {
        $headers = PrestadorTest::auth();
        $estoque = factory(Estoque::class)->create();
        $response = $this->graphfl('query_estoque', [ 'id' => $estoque->id ], $headers);
        $this->assertEquals($estoque->id, $response->json('data.estoques.data.0.id'));
    }
}
