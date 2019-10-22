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
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProdutoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateProduto()
    {
        $headers = PrestadorTest::auth();
        $seed_produto =  factory(Produto::class)->create();
        $response = $this->graphfl('create_produto', [
            'input' => [
                'codigo' => 'abc123',
                'categoria_id' => $seed_produto->categoria_id,
                'unidade_id' => $seed_produto->unidade_id,
                'descricao' => 'Pepsi',
            ]
        ], $headers);

        $found_produto = Produto::findOrFail($response->json('data.CreateProduto.id'));
        $this->assertEquals('abc123', $found_produto->codigo);
        $this->assertEquals($seed_produto->categoria_id, $found_produto->categoria_id);
        $this->assertEquals($seed_produto->unidade_id, $found_produto->unidade_id);
        $this->assertEquals('Pepsi', $found_produto->descricao);
    }

    public function testUpdateProduto()
    {
        $headers = PrestadorTest::auth();
        $produto = factory(Produto::class)->create();
        $this->graphfl('update_produto', [
            'id' => $produto->id,
            'input' => [
                'codigo' => '111ddd',
                'descricao' => 'Coca Cola',
            ]
        ], $headers);
        $produto->refresh();
        $this->assertEquals('111ddd', $produto->codigo);
        $this->assertEquals('Coca Cola', $produto->descricao);
    }

    public function testDeleteProduto()
    {
        $headers = PrestadorTest::auth();
        $produto_to_delete = factory(Produto::class)->create();
        $produto_to_delete = $this->graphfl('delete_produto', ['id' => $produto_to_delete->id], $headers);
        $produto_to_delete->refresh();
        $this->assertTrue($produto_to_delete->trashed());
        $this->assertNotNull($produto_to_delete->data_arquivado);
    }

    public function testFindProduto()
    {
        $headers = PrestadorTest::auth();
        $produto = factory(Produto::class)->create();
        $response = $this->graphfl('query_produto', [ 'id' => $produto->id ], $headers);
        $this->assertEquals($produto->id, $response->json('data.produtos.data.0.id'));
    }
}
