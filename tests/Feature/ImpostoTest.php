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
use App\Models\Imposto;

class ImpostoTest extends TestCase
{
    public function testCreateImposto()
    {
        $headers = PrestadorTest::auth();
        $seed_imposto =  factory(Imposto::class)->create();
        $response = $this->graphfl('create_imposto', [
            'input' => [
                'grupo' => Imposto::GRUPO_ICMS,
                'simples' => true,
                'substituicao' => true,
                'codigo' => 1,
                'descricao' => 'Teste',
            ]
        ], $headers);

        $found_imposto = Imposto::findOrFail($response->json('data.CreateImposto.id'));
        $this->assertEquals(Imposto::GRUPO_ICMS, $found_imposto->grupo);
        $this->assertEquals(true, $found_imposto->simples);
        $this->assertEquals(true, $found_imposto->substituicao);
        $this->assertEquals(1, $found_imposto->codigo);
        $this->assertEquals('Teste', $found_imposto->descricao);
    }

    public function testUpdateImposto()
    {
        $headers = PrestadorTest::auth();
        $imposto = factory(Imposto::class)->create();
        $this->graphfl('update_imposto', [
            'id' => $imposto->id,
            'input' => [
                'grupo' => Imposto::GRUPO_ICMS,
                'simples' => true,
                'substituicao' => true,
                'codigo' => 1,
                'descricao' => 'Atualizou',
            ]
        ], $headers);
        $imposto->refresh();
        $this->assertEquals(Imposto::GRUPO_ICMS, $imposto->grupo);
        $this->assertEquals(true, $imposto->simples);
        $this->assertEquals(true, $imposto->substituicao);
        $this->assertEquals(1, $imposto->codigo);
        $this->assertEquals('Atualizou', $imposto->descricao);
    }

    public function testDeleteImposto()
    {
        $headers = PrestadorTest::auth();
        $imposto_to_delete = factory(Imposto::class)->create();
        $this->graphfl('delete_imposto', ['id' => $imposto_to_delete->id], $headers);
        $imposto = Imposto::find($imposto_to_delete->id);
        $this->assertNull($imposto);
    }

    public function testFindImposto()
    {
        $headers = PrestadorTest::auth();
        $imposto = factory(Imposto::class)->create();
        $response = $this->graphfl('query_imposto', [ 'id' => $imposto->id ], $headers);
        $this->assertEquals($imposto->id, $response->json('data.impostos.data.0.id'));
    }
}
