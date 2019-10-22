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
use App\Models\Requisito;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RequisitoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateRequisito()
    {
        $headers = PrestadorTest::auth();
        $seed_requisito =  factory(Requisito::class)->create();
        $response = $this->graphfl('create_requisito', [
            'input' => [
                'lista_id' => $seed_requisito->lista_id,
                'produto_id' => $seed_requisito->produto_id,
            ]
        ], $headers);

        $found_requisito = Requisito::findOrFail($response->json('data.CreateRequisito.id'));
        $this->assertEquals($seed_requisito->lista_id, $found_requisito->lista_id);
        $this->assertEquals($seed_requisito->produto_id, $found_requisito->produto_id);
    }

    public function testUpdateRequisito()
    {
        $headers = PrestadorTest::auth();
        $requisito = factory(Requisito::class)->create();
        $this->graphfl('update_requisito', [
            'id' => $requisito->id,
            'input' => [
            ]
        ], $headers);
        $requisito->refresh();
    }

    public function testDeleteRequisito()
    {
        $headers = PrestadorTest::auth();
        $requisito_to_delete = factory(Requisito::class)->create();
        $requisito_to_delete = $this->graphfl('delete_requisito', ['id' => $requisito_to_delete->id], $headers);
        $requisito = Requisito::find($requisito_to_delete->id);
        $this->assertNull($requisito);
    }

    public function testFindRequisito()
    {
        $headers = PrestadorTest::auth();
        $requisito = factory(Requisito::class)->create();
        $response = $this->graphfl('query_requisito', [ 'id' => $requisito->id ], $headers);
        $this->assertEquals($requisito->id, $response->json('data.produtos_das_listas.data.0.id'));
    }
}
