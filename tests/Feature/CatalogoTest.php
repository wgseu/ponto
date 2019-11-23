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
use App\Models\Catalogo;
use App\Models\Fornecedor;
use App\Models\Produto;

class CatalogoTest extends TestCase
{
    public function testCreateCatalogo()
    {
        $headers = PrestadorTest::authOwner();
        $produto_id = factory(Produto::class)->create();
        $fornecedor_id = factory(Fornecedor::class)->create();
        $response = $this->graphfl('create_catalogo', [
            'input' => [
                'produto_id' => $produto_id->id,
                'fornecedor_id' => $fornecedor_id->id,
                'preco_compra' => 1.50,
            ]
        ], $headers);

        $found_catalogo = Catalogo::findOrFail($response->json('data.CreateCatalogo.id'));
        $this->assertEquals($produto_id->id, $found_catalogo->produto_id);
        $this->assertEquals($fornecedor_id->id, $found_catalogo->fornecedor_id);
        $this->assertEquals(1.50, $found_catalogo->preco_compra);
    }

    public function testUpdateCatalogo()
    {
        $headers = PrestadorTest::authOwner();
        $catalogo = factory(Catalogo::class)->create();
        $this->graphfl('update_catalogo', [
            'id' => $catalogo->id,
            'input' => [
                'preco_compra' => 1.50,
            ]
        ], $headers);
        $catalogo->refresh();
        $this->assertEquals(1.50, $catalogo->preco_compra);
    }

    public function testDeleteCatalogo()
    {
        $headers = PrestadorTest::authOwner();
        $catalogo_to_delete = factory(Catalogo::class)->create();
        $this->graphfl('delete_catalogo', ['id' => $catalogo_to_delete->id], $headers);
        $catalogo = Catalogo::find($catalogo_to_delete->id);
        $this->assertNull($catalogo);
    }

    public function testFindCatalogo()
    {
        $headers = PrestadorTest::authOwner();
        $catalogo = factory(Catalogo::class)->create();
        $response = $this->graphfl('query_catalogo', [ 'id' => $catalogo->id ], $headers);
        $this->assertEquals($catalogo->id, $response->json('data.catalogos.data.0.id'));
    }
}
