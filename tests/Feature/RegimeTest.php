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
use App\Models\Regime;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegimeTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateRegime()
    {
        $headers = PrestadorTest::auth();
        $seed_regime =  factory(Regime::class)->create();
        $response = $this->graphfl('create_regime', [
            'input' => [
                'codigo' => 1,
                'descricao' => 'Teste',
            ]
        ], $headers);

        $found_regime = Regime::findOrFail($response->json('data.CreateRegime.id'));
        $this->assertEquals(1, $found_regime->codigo);
        $this->assertEquals('Teste', $found_regime->descricao);
    }

    public function testUpdateRegime()
    {
        $headers = PrestadorTest::auth();
        $regime = factory(Regime::class)->create();
        $this->graphfl('update_regime', [
            'id' => $regime->id,
            'input' => [
                'codigo' => 1,
                'descricao' => 'Atualizou',
            ]
        ], $headers);
        $regime->refresh();
        $this->assertEquals(1, $regime->codigo);
        $this->assertEquals('Atualizou', $regime->descricao);
    }

    public function testDeleteRegime()
    {
        $headers = PrestadorTest::auth();
        $regime_to_delete = factory(Regime::class)->create();
        $regime_to_delete = $this->graphfl('delete_regime', ['id' => $regime_to_delete->id], $headers);
        $regime = Regime::find($regime_to_delete->id);
        $this->assertNull($regime);
    }

    public function testFindRegime()
    {
        $headers = PrestadorTest::auth();
        $regime = factory(Regime::class)->create();
        $response = $this->graphfl('query_regime', [ 'id' => $regime->id ], $headers);
        $this->assertEquals($regime->id, $response->json('data.regimes.data.0.id'));
    }
}
