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
use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriaTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCategoria()
    {
        $headers = PrestadorTest::auth();
        $seed_categoria =  factory(Categoria::class)->create();
        $response = $this->graphfl('create_categoria', [
            'input' => [
                'descricao' => 'Teste',
            ]
        ], $headers);

        $found_categoria = Categoria::findOrFail($response->json('data.CreateCategoria.id'));
        $this->assertEquals('Teste', $found_categoria->descricao);
    }

    public function testUpdateCategoria()
    {
        $headers = PrestadorTest::auth();
        $categoria = factory(Categoria::class)->create();
        $this->graphfl('update_categoria', [
            'id' => $categoria->id,
            'input' => [
                'descricao' => 'Atualizou',
            ]
        ], $headers);
        $categoria->refresh();
        $this->assertEquals('Atualizou', $categoria->descricao);
    }

    public function testDeleteCategoria()
    {
        $headers = PrestadorTest::auth();
        $categoria_to_delete = factory(Categoria::class)->create();
        $categoria_to_delete = $this->graphfl('delete_categoria', ['id' => $categoria_to_delete->id], $headers);
        $categoria_to_delete->refresh();
        $this->assertTrue($categoria_to_delete->trashed());
        $this->assertNotNull($categoria_to_delete->data_arquivado);
    }

    public function testQueryCategoria()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Categoria::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_categoria', [], $headers);
        $this->assertEquals(10, $response->json('data.categorias.total'));
    }
}
