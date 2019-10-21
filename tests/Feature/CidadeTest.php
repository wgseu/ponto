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
use App\Models\Cidade;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CidadeTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCidade()
    {
        $headers = PrestadorTest::auth();
        $seed_cidade =  factory(Cidade::class)->create();
        $response = $this->graphfl('create_cidade', [
            'input' => [
                'estado_id' => $seed_cidade->estado_id,
                'nome' => 'Teste',
            ]
        ], $headers);

        $found_cidade = Cidade::findOrFail($response->json('data.CreateCidade.id'));
        $this->assertEquals($seed_cidade->estado_id, $found_cidade->estado_id);
        $this->assertEquals('Teste', $found_cidade->nome);
    }

    public function testUpdateCidade()
    {
        $headers = PrestadorTest::auth();
        $cidade = factory(Cidade::class)->create();
        $this->graphfl('update_cidade', [
            'id' => $cidade->id,
            'input' => [
                'nome' => 'Atualizou',
            ]
        ], $headers);
        $cidade->refresh();
        $this->assertEquals('Atualizou', $cidade->nome);
    }

    public function testDeleteCidade()
    {
        $headers = PrestadorTest::auth();
        $cidade_to_delete = factory(Cidade::class)->create();
        $cidade_to_delete = $this->graphfl('delete_cidade', ['id' => $cidade_to_delete->id], $headers);
        $cidade = Cidade::find($cidade_to_delete->id);
        $this->assertNull($cidade);
    }

    public function testQueryCidade()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Cidade::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_cidade', [], $headers);
        $this->assertEquals(10, $response->json('data.cidades.total'));
    }
}
