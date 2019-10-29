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
use App\Models\Viagem;
use Illuminate\Validation\ValidationException;

class ViagemTest extends TestCase
{
    public function testCreateViagem()
    {
        $headers = PrestadorTest::auth();
        $seed_viagem =  factory(Viagem::class)->create();
        $response = $this->graphfl('create_viagem', [
            'input' => [
                'responsavel_id' => $seed_viagem->responsavel_id,
                'data_saida' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $found_viagem = Viagem::findOrFail($response->json('data.CreateViagem.id'));
        $this->assertEquals($seed_viagem->responsavel_id, $found_viagem->responsavel_id);
        $this->assertEquals('2016-12-25 12:15:00', $found_viagem->data_saida);
    }

    public function testUpdateViagem()
    {
        $headers = PrestadorTest::auth();
        $viagem = factory(Viagem::class)->create();
        $this->graphfl('update_viagem', [
            'id' => $viagem->id,
            'input' => [
                'data_saida' => '2016-12-28 12:30:00',
            ]
        ], $headers);
        $viagem->refresh();
        $this->assertEquals('2016-12-28 12:30:00', $viagem->data_saida);
    }

    public function testDeleteViagem()
    {
        $headers = PrestadorTest::auth();
        $viagem_to_delete = factory(Viagem::class)->create();
        $this->graphfl('delete_viagem', ['id' => $viagem_to_delete->id], $headers);
        $viagem = Viagem::find($viagem_to_delete->id);
        $this->assertNull($viagem);
    }

    public function testFindViagem()
    {
        $headers = PrestadorTest::auth();
        $viagem = factory(Viagem::class)->create();
        $response = $this->graphfl('query_viagem', [ 'id' => $viagem->id ], $headers);
        $this->assertEquals($viagem->id, $response->json('data.viagens.data.0.id'));
    }

    public function testValidateViagemChegadaAntesSaida()
    {
        $viagem = factory(Viagem::class)->create();
        $viagem->data_chegada = '2019-01-01 12:00:00';
        $this->expectException(ValidationException::class);
        $viagem->save();
    }
}
