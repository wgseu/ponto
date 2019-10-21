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
use App\Models\Evento;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateEvento()
    {
        $headers = PrestadorTest::auth();
        $seed_evento =  factory(Evento::class)->create();
        $response = $this->graphfl('create_evento', [
            'input' => [
                'nota_id' => $seed_evento->nota_id,
                'estado' => Evento::ESTADO_ABERTO,
                'mensagem' => 'Teste',
                'codigo' => 'Teste',
            ]
        ], $headers);

        $found_evento = Evento::findOrFail($response->json('data.CreateEvento.id'));
        $this->assertEquals($seed_evento->nota_id, $found_evento->nota_id);
        $this->assertEquals(Evento::ESTADO_ABERTO, $found_evento->estado);
        $this->assertEquals('Teste', $found_evento->mensagem);
        $this->assertEquals('Teste', $found_evento->codigo);
    }

    public function testUpdateEvento()
    {
        $headers = PrestadorTest::auth();
        $evento = factory(Evento::class)->create();
        $this->graphfl('update_evento', [
            'id' => $evento->id,
            'input' => [
                'estado' => Evento::ESTADO_ABERTO,
                'mensagem' => 'Atualizou',
                'codigo' => 'Atualizou',
            ]
        ], $headers);
        $evento->refresh();
        $this->assertEquals(Evento::ESTADO_ABERTO, $evento->estado);
        $this->assertEquals('Atualizou', $evento->mensagem);
        $this->assertEquals('Atualizou', $evento->codigo);
    }

    public function testDeleteEvento()
    {
        $headers = PrestadorTest::auth();
        $evento_to_delete = factory(Evento::class)->create();
        $evento_to_delete = $this->graphfl('delete_evento', ['id' => $evento_to_delete->id], $headers);
        $evento = Evento::find($evento_to_delete->id);
        $this->assertNull($evento);
    }

    public function testQueryEvento()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Evento::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_evento', [], $headers);
        $this->assertEquals(10, $response->json('data.eventos.total'));
    }
}
