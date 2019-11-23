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

use App\Models\Grupo;
use Tests\TestCase;
use App\Models\Propriedade;

class PropriedadeTest extends TestCase
{
    public function testCreatePropriedade()
    {
        $headers = PrestadorTest::authOwner();
        $seed_propriedade =  factory(Propriedade::class)->create();
        $response = $this->graphfl('create_propriedade', [
            'input' => [
                'grupo_id' => $seed_propriedade->grupo_id,
                'nome' => 'Teste',
            ]
        ], $headers);

        $found_propriedade = Propriedade::findOrFail($response->json('data.CreatePropriedade.id'));
        $this->assertEquals($seed_propriedade->grupo_id, $found_propriedade->grupo_id);
        $this->assertEquals('Teste', $found_propriedade->nome);
    }

    public function testUpdatePropriedade()
    {
        $headers = PrestadorTest::authOwner();
        $propriedade = factory(Propriedade::class)->create();
        $this->graphfl('update_propriedade', [
            'id' => $propriedade->id,
            'input' => [
                'nome' => 'Atualizou',
            ]
        ], $headers);
        $propriedade->refresh();
        $this->assertEquals('Atualizou', $propriedade->nome);
    }

    public function testDeletePropriedade()
    {
        $headers = PrestadorTest::authOwner();
        $propriedade_to_delete = factory(Propriedade::class)->create();
        $this->graphfl('delete_propriedade', ['id' => $propriedade_to_delete->id], $headers);
        $propriedade = Propriedade::find($propriedade_to_delete->id);
        $this->assertNull($propriedade);
    }

    public function testFindPropriedade()
    {
        $headers = PrestadorTest::authOwner();
        $propriedade = factory(Propriedade::class)->create();
        $response = $this->graphfl('query_propriedade', [ 'id' => $propriedade->id ], $headers);
        $this->assertEquals($propriedade->id, $response->json('data.propriedades.data.0.id'));

        $expectedGrupo = Grupo::find($response->json('data.propriedades.data.0.grupo_id'));
        $resultGrupo = $propriedade->grupo;
        $this->assertEquals($expectedGrupo, $resultGrupo);
    }
}
