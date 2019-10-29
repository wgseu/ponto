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
use App\Models\Mesa;

class MesaTest extends TestCase
{
    public function testCreateMesa()
    {
        $headers = PrestadorTest::auth();
        $seed_mesa =  factory(Mesa::class)->create();
        $response = $this->graphfl('create_mesa', [
            'input' => [
                'setor_id' => $seed_mesa->setor_id,
                'numero' => 1,
            ]
        ], $headers);

        $found_mesa = Mesa::findOrFail($response->json('data.CreateMesa.id'));
        $this->assertEquals($seed_mesa->setor_id, $found_mesa->setor_id);
        $this->assertEquals(1, $found_mesa->numero);
    }

    public function testUpdateMesa()
    {
        $headers = PrestadorTest::auth();
        $mesa = factory(Mesa::class)->create();
        $this->graphfl('update_mesa', [
            'id' => $mesa->id,
            'input' => [
                'numero' => 1,
            ]
        ], $headers);
        $mesa->refresh();
        $this->assertEquals(1, $mesa->numero);
    }

    public function testDeleteMesa()
    {
        $headers = PrestadorTest::auth();
        $mesa_to_delete = factory(Mesa::class)->create();
        $this->graphfl('delete_mesa', ['id' => $mesa_to_delete->id], $headers);
        $mesa = Mesa::find($mesa_to_delete->id);
        $this->assertNull($mesa);
    }

    public function testFindMesa()
    {
        $headers = PrestadorTest::auth();
        $mesa = factory(Mesa::class)->create();
        $response = $this->graphfl('query_mesa', [ 'id' => $mesa->id ], $headers);
        $this->assertEquals($mesa->id, $response->json('data.mesas.data.0.id'));
    }
}
