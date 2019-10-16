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
use App\Models\Catalogo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CatalogoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCatalogo()
    {
        $headers = PrestadorTest::auth();
        $seed_catalogo =  factory(Catalogo::class)->create();
        $response = $this->graphfl('create_catalogo', [
            'input' => [
                'produto_id' => $seed_catalogo->produto_id,
                'fornecedor_id' => $seed_catalogo->fornecedor_id,
                'preco_compra' => 1.50,
            ]
        ], $headers);

        $found_catalogo = Catalogo::findOrFail($response->json('data.CreateCatalogo.id'));
        $this->assertEquals($seed_catalogo->produto_id, $found_catalogo->produto_id);
        $this->assertEquals($seed_catalogo->fornecedor_id, $found_catalogo->fornecedor_id);
        $this->assertEquals(1.50, $found_catalogo->preco_compra);
    }

    public function testUpdateCatalogo()
    {
        $headers = PrestadorTest::auth();
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
        $headers = PrestadorTest::auth();
        $catalogo_to_delete = factory(Catalogo::class)->create();
        $catalogo_to_delete = $this->graphfl('delete_catalogo', ['id' => $catalogo_to_delete->id], $headers);
        $catalogo = Catalogo::find($catalogo_to_delete->id);
        $this->assertNull($catalogo);
    }

    public function testQueryCatalogo()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Catalogo::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_catalogo', [], $headers);
        $this->assertEquals(10, $response->json('data.catalogos_de_produtos.total'));
    }
}
