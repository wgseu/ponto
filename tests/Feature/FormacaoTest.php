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
use App\Models\Formacao;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormacaoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateFormacao()
    {
        $headers = PrestadorTest::auth();
        $seed_formacao =  factory(Formacao::class)->create();
        $response = $this->graphfl('create_formacao', [
            'input' => [
                'item_id' => $seed_formacao->item_id,
            ]
        ], $headers);

        $found_formacao = Formacao::findOrFail($response->json('data.CreateFormacao.id'));
        $this->assertEquals($seed_formacao->item_id, $found_formacao->item_id);
    }

    public function testUpdateFormacao()
    {
        $headers = PrestadorTest::auth();
        $formacao = factory(Formacao::class)->create();
        $this->graphfl('update_formacao', [
            'id' => $formacao->id,
            'input' => [
            ]
        ], $headers);
        $formacao->refresh();
    }

    public function testDeleteFormacao()
    {
        $headers = PrestadorTest::auth();
        $formacao_to_delete = factory(Formacao::class)->create();
        $formacao_to_delete = $this->graphfl('delete_formacao', ['id' => $formacao_to_delete->id], $headers);
        $formacao = Formacao::find($formacao_to_delete->id);
        $this->assertNull($formacao);
    }

    public function testQueryFormacao()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Formacao::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_formacao', [], $headers);
        $this->assertEquals(10, $response->json('data.formacoes.total'));
    }
}
