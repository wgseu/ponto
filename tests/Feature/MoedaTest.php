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
use App\Models\Moeda;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MoedaTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateMoeda()
    {
        $headers = PrestadorTest::auth();
        $seed_moeda =  factory(Moeda::class)->create();
        $response = $this->graphfl('create_moeda', [
            'input' => [
                'nome' => 'Teste',
                'simbolo' => 'Teste',
                'codigo' => 'Teste',
                'divisao' => 1,
                'formato' => 'Teste',
            ]
        ], $headers);

        $found_moeda = Moeda::findOrFail($response->json('data.CreateMoeda.id'));
        $this->assertEquals('Teste', $found_moeda->nome);
        $this->assertEquals('Teste', $found_moeda->simbolo);
        $this->assertEquals('Teste', $found_moeda->codigo);
        $this->assertEquals(1, $found_moeda->divisao);
        $this->assertEquals('Teste', $found_moeda->formato);
    }

    public function testUpdateMoeda()
    {
        $headers = PrestadorTest::auth();
        $moeda = factory(Moeda::class)->create();
        $this->graphfl('update_moeda', [
            'id' => $moeda->id,
            'input' => [
                'nome' => 'Atualizou',
                'simbolo' => 'Atualizou',
                'codigo' => 'Atualizou',
                'divisao' => 1,
                'formato' => 'Atualizou',
            ]
        ], $headers);
        $moeda->refresh();
        $this->assertEquals('Atualizou', $moeda->nome);
        $this->assertEquals('Atualizou', $moeda->simbolo);
        $this->assertEquals('Atualizou', $moeda->codigo);
        $this->assertEquals(1, $moeda->divisao);
        $this->assertEquals('Atualizou', $moeda->formato);
    }

    public function testDeleteMoeda()
    {
        $headers = PrestadorTest::auth();
        $moeda_to_delete = factory(Moeda::class)->create();
        $moeda_to_delete = $this->graphfl('delete_moeda', ['id' => $moeda_to_delete->id], $headers);
        $moeda = Moeda::find($moeda_to_delete->id);
        $this->assertNull($moeda);
    }

    public function testQueryMoeda()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Moeda::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_moeda', [], $headers);
        $this->assertEquals(10, $response->json('data.moedas.total'));
    }
}
