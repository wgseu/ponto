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
use App\Models\Funcao;
use Tests\TestCase;
use App\Models\Horario;
use App\Models\Prestador;

class HorarioTest extends TestCase
{
    public function testCreateHorario()
    {
        $headers = PrestadorTest::authOwner();
        $response = $this->graphfl('create_horario', [
            'input' => [
                'inicio' => Horario::MINUTES_PER_DAY,
                'fim' => Horario::MINUTES_PER_DAY + 500,
            ]
        ], $headers);

        $found_horario = Horario::findOrFail($response->json('data.CreateHorario.id'));
        $this->assertEquals(Horario::MINUTES_PER_DAY, $found_horario->inicio);
        $this->assertEquals(Horario::MINUTES_PER_DAY + 500, $found_horario->fim);
    }

    public function testUpdateHorario()
    {
        $headers = PrestadorTest::authOwner();
        $horario = factory(Horario::class)->create();
        $this->graphfl('update_horario', [
            'id' => $horario->id,
            'input' => [
                'inicio' => Horario::MINUTES_PER_DAY + 20,
                'fim' => Horario::MINUTES_PER_DAY + 200,
            ]
        ], $headers);
        $horario->refresh();
        $this->assertEquals(Horario::MINUTES_PER_DAY + 20, $horario->inicio);
        $this->assertEquals(Horario::MINUTES_PER_DAY + 200, $horario->fim);
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

    public function testDuplicadoHorarios()
    {
        $horario = factory(Horario::class)->create();
        $this->expectException(ValidationException::class);
        factory(Horario::class)->create(['inicio' => $horario->inicio + 1, 'fim' => $horario->fim - 1]);
    }


    public function testIntervaloSobrescrito()
    {
        $horario = factory(Horario::class)->create();
        $this->expectException(ValidationException::class);
        factory(Horario::class)->create(['inicio' => $horario->inicio - 1, 'fim' => $horario->fim + 1]);
    }

    public function testMultiplaSelecao()
    {
        $funcao = factory(Funcao::class)->create();
        $prestador = factory(Prestador::class)->create();
        $this->expectException(ValidationException::class);
        factory(Horario::class)->create(['funcao_id' => $funcao->id, 'prestador_id' => $prestador->id]);
    }

    public function testHorarioFuncionamentoInvalido()
    {
        $this->expectException(ValidationException::class);
        factory(Horario::class)->create([
            'inicio' => Horario::MINUTES_PER_DAY * 3,
            'fim' => Horario::MINUTES_PER_DAY * 2,
        ]);
    }

    public function testHorarioInicioInvalido()
    {
        $this->expectException(ValidationException::class);
        factory(Horario::class)->create([
            'inicio' => Horario::MINUTES_PER_DAY - 100,
        ]);
    }

    public function testHorarioTerminimoInvalido()
    {
        $this->expectException(ValidationException::class);
        factory(Horario::class)->create([
            'fim' => (Horario::MINUTES_PER_DAY * 8) + 100,
        ]);
    }

    public function testInvalidIntervalEntrega()
    {
        $this->expectException(ValidationException::class);
        factory(Horario::class)->create([
            'modo' => Horario::MODO_ENTREGA,
            'entrega_minima' => Horario::MINUTES_PER_DAY * 3,
            'entrega_maxima' => Horario::MINUTES_PER_DAY * 2,
        ]);
    }

    public function testTempoEntregaMinimaInvalido()
    {
        $this->expectException(ValidationException::class);
        factory(Horario::class)->create([
            'modo' => Horario::MODO_ENTREGA,
            'entrega_minima' => -10,
        ]);
    }

    public function testFechamentoInvalido()
    {
        $this->expectException(ValidationException::class);
        factory(Horario::class)->create([
            'modo' => Horario::MODO_OPERACAO,
            'fechado' => true,
        ]);
    }

    public function testAlterarDuplicandoHorarios()
    {
        $funcao = factory(Funcao::class)->create();
        factory(Horario::class)->create(['inicio' => 4000, 'fim' => 4500, 'funcao_id' => $funcao->id]);
        $horario = factory(Horario::class)->create(['inicio' => 3000, 'fim' => 3500, 'funcao_id' => $funcao->id]);
        $horario->fim = 4200;
        $this->assertEquals($funcao->id, $horario->funcao->id);
        $this->expectException(ValidationException::class);
        $horario->save();
    }

    public function testAlterarIntervaloSobrescrito()
    {
        $prestador = factory(Prestador::class)->create();
        factory(Horario::class)->create(['inicio' => 3000, 'fim' => 3500, 'prestador_id' => $prestador->id]);
        $horario = factory(Horario::class)->create(['inicio' => 4000, 'fim' => 4500, 'prestador_id' => $prestador->id]);
        $horario->inicio = 3200;
        $this->assertEquals($prestador->id, $horario->prestador->id);
        $this->expectException(ValidationException::class);
        $horario->save();
    }
}
