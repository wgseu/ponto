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
use App\Models\Promocao;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PromocaoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePromocao()
    {
        $headers = PrestadorTest::auth();
        $seed_promocao =  factory(Promocao::class)->create();
        $response = $this->graphfl('create_promocao', [
            'input' => [
                'inicio' => 1,
                'fim' => 1,
                'valor' => 1.50,
            ]
        ], $headers);

        $found_promocao = Promocao::findOrFail($response->json('data.CreatePromocao.id'));
        $this->assertEquals(1, $found_promocao->inicio);
        $this->assertEquals(1, $found_promocao->fim);
        $this->assertEquals(1.50, $found_promocao->valor);
    }

    public function testUpdatePromocao()
    {
        $headers = PrestadorTest::auth();
        $promocao = factory(Promocao::class)->create();
        $this->graphfl('update_promocao', [
            'id' => $promocao->id,
            'input' => [
                'inicio' => 1,
                'fim' => 1,
                'valor' => 1.50,
            ]
        ], $headers);
        $promocao->refresh();
        $this->assertEquals(1, $promocao->inicio);
        $this->assertEquals(1, $promocao->fim);
        $this->assertEquals(1.50, $promocao->valor);
    }

    public function testDeletePromocao()
    {
        $headers = PrestadorTest::auth();
        $promocao_to_delete = factory(Promocao::class)->create();
        $promocao_to_delete = $this->graphfl('delete_promocao', ['id' => $promocao_to_delete->id], $headers);
        $promocao_to_delete->refresh();
        $this->assertTrue($promocao_to_delete->trashed());
        $this->assertNotNull($promocao_to_delete->data_arquivado);
    }

    public function testQueryPromocao()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Promocao::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_promocao', [], $headers);
        $this->assertEquals(10, $response->json('data.promocoes.total'));
    }
}