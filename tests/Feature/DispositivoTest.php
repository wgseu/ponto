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
use App\Models\Dispositivo;

class DispositivoTest extends TestCase
{
    public function testCreateDispositivo()
    {
        $headers = PrestadorTest::authOwner();
        $response = $this->graphfl('create_dispositivo', [
            'input' => [
                'nome' => 'Teste',
                'serial' => 'Teste',
            ]
        ], $headers);

        $found_dispositivo = Dispositivo::findOrFail($response->json('data.CreateDispositivo.id'));
        $this->assertEquals('Teste', $found_dispositivo->nome);
        $this->assertEquals('Teste', $found_dispositivo->serial);
    }

    public function testCreateWithoutValidationAccess()
    {
        $headers = PrestadorTest::auth(['dispositivo:create']);
        $this->expectException(\Exception::class);
        $this->graphfl('create_dispositivo', [
            'input' => [
                'nome' => 'Teste',
                'serial' => 'Teste',
                'validacao' => 'Teste',
            ]
        ], $headers);
    }

    public function testUpdateDispositivo()
    {
        $headers = PrestadorTest::authOwner();
        $dispositivo = factory(Dispositivo::class)->create();
        $this->graphfl('update_dispositivo', [
            'id' => $dispositivo->id,
            'input' => [
                'nome' => 'Atualizou',
                'serial' => 'Atualizou',
            ]
        ], $headers);
        $dispositivo->refresh();
        $this->assertEquals('Atualizou', $dispositivo->nome);
        $this->assertEquals('Atualizou', $dispositivo->serial);
    }

    public function testDeleteDispositivo()
    {
        $headers = PrestadorTest::authOwner();
        $dispositivo_to_delete = factory(Dispositivo::class)->create();
        $this->graphfl('delete_dispositivo', ['id' => $dispositivo_to_delete->id], $headers);
        $dispositivo = Dispositivo::find($dispositivo_to_delete->id);
        $this->assertNull($dispositivo);
    }

    public function testFindDispositivo()
    {
        $headers = PrestadorTest::authOwner();
        $dispositivo = factory(Dispositivo::class)->create();
        $response = $this->graphfl('query_dispositivo', [ 'id' => $dispositivo->id ], $headers);
        $this->assertEquals($dispositivo->id, $response->json('data.dispositivos.data.0.id'));
    }
}
