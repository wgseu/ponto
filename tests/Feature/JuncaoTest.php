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
use App\Models\Juncao;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JuncaoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateJuncao()
    {
        $headers = PrestadorTest::auth();
        $seed_juncao =  factory(Juncao::class)->create();
        $response = $this->graphfl('create_juncao', [
            'input' => [
                'mesa_id' => $seed_juncao->mesa_id,
                'pedido_id' => $seed_juncao->pedido_id,
                'data_movimento' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $found_juncao = Juncao::findOrFail($response->json('data.CreateJuncao.id'));
        $this->assertEquals($seed_juncao->mesa_id, $found_juncao->mesa_id);
        $this->assertEquals($seed_juncao->pedido_id, $found_juncao->pedido_id);
        $this->assertEquals('2016-12-25 12:15:00', $found_juncao->data_movimento);
    }

    public function testUpdateJuncao()
    {
        $headers = PrestadorTest::auth();
        $juncao = factory(Juncao::class)->create();
        $this->graphfl('update_juncao', [
            'id' => $juncao->id,
            'input' => [
                'data_movimento' => '2016-12-28 12:30:00',
            ]
        ], $headers);
        $juncao->refresh();
        $this->assertEquals('2016-12-28 12:30:00', $juncao->data_movimento);
    }

    public function testDeleteJuncao()
    {
        $headers = PrestadorTest::auth();
        $juncao_to_delete = factory(Juncao::class)->create();
        $juncao_to_delete = $this->graphfl('delete_juncao', ['id' => $juncao_to_delete->id], $headers);
        $juncao = Juncao::find($juncao_to_delete->id);
        $this->assertNull($juncao);
    }

    public function testQueryJuncao()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Juncao::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_juncao', [], $headers);
        $this->assertEquals(10, $response->json('data.juncoes.total'));
    }
}
