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
use App\Models\Integracao;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IntegracaoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateIntegracao()
    {
        $headers = PrestadorTest::auth();
        $seed_integracao =  factory(Integracao::class)->create();
        $response = $this->graphfl('create_integracao', [
            'input' => [
                'nome' => 'Teste',
            ]
        ], $headers);

        $found_integracao = Integracao::findOrFail($response->json('data.CreateIntegracao.id'));
        $this->assertEquals('Teste', $found_integracao->nome);
    }

    public function testUpdateIntegracao()
    {
        $headers = PrestadorTest::auth();
        $integracao = factory(Integracao::class)->create();
        $this->graphfl('update_integracao', [
            'id' => $integracao->id,
            'input' => [
                'nome' => 'Atualizou',
            ]
        ], $headers);
        $integracao->refresh();
        $this->assertEquals('Atualizou', $integracao->nome);
    }

    public function testDeleteIntegracao()
    {
        $headers = PrestadorTest::auth();
        $integracao_to_delete = factory(Integracao::class)->create();
        $integracao_to_delete = $this->graphfl('delete_integracao', ['id' => $integracao_to_delete->id], $headers);
        $integracao = Integracao::find($integracao_to_delete->id);
        $this->assertNull($integracao);
    }

    public function testQueryIntegracao()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Integracao::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_integracao', [], $headers);
        $this->assertEquals(10, $response->json('data.integracoes.total'));
    }
}
