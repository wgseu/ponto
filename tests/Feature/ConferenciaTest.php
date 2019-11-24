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
use App\Models\Conferencia;

class ConferenciaTest extends TestCase
{
    public function testCreate()
    {
        $headers = PrestadorTest::authOwner();
        $conferencia_data =  factory(Conferencia::class)->raw();
        $response = $this->graphfl('create_conferencia', ['input' => $conferencia_data], $headers);
        $conferencia = Conferencia::find($response->json('data.CreateConferencia.id'));
        $this->assertNotNull($conferencia);
    }

    public function testUpdate()
    {
        $headers = PrestadorTest::authOwner();
        $response = $this->graphfl('create_conferencia', ['input' => factory(Conferencia::class)->raw()], $headers);
        $conferencia = Conferencia::find($response->json('data.CreateConferencia.id'));
        $this->graphfl('update_conferencia', [
            'id' => $conferencia->id,
            'input' => [
                'numero' => 1,
                'conferido' => 1.0,
            ]
        ], $headers);
        $conferencia->refresh();
        $this->assertEquals(1, $conferencia->numero);
        $this->assertEquals(1.0, $conferencia->conferido);
    }

    public function testFind()
    {
        $headers = PrestadorTest::authOwner();
        $response = $this->graphfl('create_conferencia', ['input' => factory(Conferencia::class)->raw()], $headers);
        $conferencia = Conferencia::find($response->json('data.CreateConferencia.id'));
        $response = $this->graphfl('query_conferencia', [ 'id' => $conferencia->id ], $headers);
        $this->assertEquals($conferencia->id, $response->json('data.conferencias.data.0.id'));
    }
}
