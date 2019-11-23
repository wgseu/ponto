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
use App\Models\Horario;

class HorarioTest extends TestCase
{
    public function testCreateHorario()
    {
        $headers = PrestadorTest::authOwner();
        $seed_horario =  factory(Horario::class)->create();
        $response = $this->graphfl('create_horario', [
            'input' => [
                'inicio' => 1,
                'fim' => 1,
            ]
        ], $headers);

        $found_horario = Horario::findOrFail($response->json('data.CreateHorario.id'));
        $this->assertEquals(1, $found_horario->inicio);
        $this->assertEquals(1, $found_horario->fim);
    }

    public function testUpdateHorario()
    {
        $headers = PrestadorTest::authOwner();
        $horario = factory(Horario::class)->create();
        $this->graphfl('update_horario', [
            'id' => $horario->id,
            'input' => [
                'inicio' => 1,
                'fim' => 1,
            ]
        ], $headers);
        $horario->refresh();
        $this->assertEquals(1, $horario->inicio);
        $this->assertEquals(1, $horario->fim);
    }

    public function testDeleteHorario()
    {
        $headers = PrestadorTest::authOwner();
        $horario_to_delete = factory(Horario::class)->create();
        $this->graphfl('delete_horario', ['id' => $horario_to_delete->id], $headers);
        $horario = Horario::find($horario_to_delete->id);
        $this->assertNull($horario);
    }

    public function testFindHorario()
    {
        $headers = PrestadorTest::authOwner();
        $horario = factory(Horario::class)->create();
        $response = $this->graphfl('query_horario', [ 'id' => $horario->id ], $headers);
        $this->assertEquals($horario->id, $response->json('data.horarios.data.0.id'));
    }
}
