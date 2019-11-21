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

use App\Exceptions\ValidationException;
use App\Models\Prestador;
use Tests\TestCase;
use App\Models\Viagem;
use Illuminate\Support\Carbon;

class ViagemTest extends TestCase
{
    public function testUpdateViagem()
    {
        $prestador = factory(Prestador::class)->create();
        $headers = PrestadorTest::auth();
        $viagem = factory(Viagem::class)->create();
        $this->graphfl('update_viagem', [
            'id' => $viagem->id,
            'input' => [
                'responsavel_id' => $prestador->id,
            ]
        ], $headers);
        $viagem->refresh();
        $this->assertEquals($prestador->id, $viagem->responsavel_id);
    }

    public function testFindViagem()
    {
        $headers = PrestadorTest::auth();
        $viagem = factory(Viagem::class)->create();
        $response = $this->graphfl('query_viagem', [ 'id' => $viagem->id ], $headers);

        $viagemExpect = Prestador::find($response->json('data.viagens.data.0.responsavel_id'));
        $viagemResult = $viagem->responsavel;
        $this->assertEquals($viagemExpect, $viagemResult);

        $this->assertEquals($viagem->id, $response->json('data.viagens.data.0.id'));
    }

    public function testValidateViagemChegadaAntesSaida()
    {
        $viagem = factory(Viagem::class)->create();
        $viagem->data_chegada = Carbon::create(2018, 10, 12, 10, 20, 30);
        $this->expectException(ValidationException::class);
        $viagem->save();
    }
}
