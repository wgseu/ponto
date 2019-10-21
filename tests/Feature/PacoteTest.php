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
use App\Models\Pacote;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PacoteTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePacote()
    {
        $headers = PrestadorTest::auth();
        $seed_pacote =  factory(Pacote::class)->create();
        $response = $this->graphfl('create_pacote', [
            'input' => [
                'pacote_id' => $seed_pacote->pacote_id,
                'grupo_id' => $seed_pacote->grupo_id,
                'acrescimo' => 1.50,
            ]
        ], $headers);

        $found_pacote = Pacote::findOrFail($response->json('data.CreatePacote.id'));
        $this->assertEquals($seed_pacote->pacote_id, $found_pacote->pacote_id);
        $this->assertEquals($seed_pacote->grupo_id, $found_pacote->grupo_id);
        $this->assertEquals(1.50, $found_pacote->acrescimo);
    }

    public function testUpdatePacote()
    {
        $headers = PrestadorTest::auth();
        $pacote = factory(Pacote::class)->create();
        $this->graphfl('update_pacote', [
            'id' => $pacote->id,
            'input' => [
                'acrescimo' => 1.50,
            ]
        ], $headers);
        $pacote->refresh();
        $this->assertEquals(1.50, $pacote->acrescimo);
    }

    public function testDeletePacote()
    {
        $headers = PrestadorTest::auth();
        $pacote_to_delete = factory(Pacote::class)->create();
        $pacote_to_delete = $this->graphfl('delete_pacote', ['id' => $pacote_to_delete->id], $headers);
        $pacote_to_delete->refresh();
        $this->assertTrue($pacote_to_delete->trashed());
        $this->assertNotNull($pacote_to_delete->data_arquivado);
    }

    public function testQueryPacote()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Pacote::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_pacote', [], $headers);
        $this->assertEquals(10, $response->json('data.pacotes.total'));
    }
}
