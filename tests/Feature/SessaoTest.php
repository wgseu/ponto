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
use App\Models\Sessao;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SessaoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateSessao()
    {
        $headers = PrestadorTest::auth();
        $seed_sessao =  factory(Sessao::class)->create();
        $response = $this->graphfl('create_sessao', [
            'input' => [
                'data_inicio' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $found_sessao = Sessao::findOrFail($response->json('data.CreateSessao.id'));
        $this->assertEquals('2016-12-25 12:15:00', $found_sessao->data_inicio);
    }

    public function testUpdateSessao()
    {
        $headers = PrestadorTest::auth();
        $sessao = factory(Sessao::class)->create();
        $this->graphfl('update_sessao', [
            'id' => $sessao->id,
            'input' => [
                'data_inicio' => '2016-12-28 12:30:00',
            ]
        ], $headers);
        $sessao->refresh();
        $this->assertEquals('2016-12-28 12:30:00', $sessao->data_inicio);
    }

    public function testDeleteSessao()
    {
        $headers = PrestadorTest::auth();
        $sessao_to_delete = factory(Sessao::class)->create();
        $sessao_to_delete = $this->graphfl('delete_sessao', ['id' => $sessao_to_delete->id], $headers);
        $sessao = Sessao::find($sessao_to_delete->id);
        $this->assertNull($sessao);
    }

    public function testFindSessao()
    {
        $headers = PrestadorTest::auth();
        $sessao = factory(Sessao::class)->create();
        $response = $this->graphfl('query_sessao', [ 'id' => $sessao->id ], $headers);
        $this->assertEquals($sessao->id, $response->json('data.sessoes.data.0.id'));
    }
}
