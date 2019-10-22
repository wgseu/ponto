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
use App\Models\Metrica;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MetricaTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateMetrica()
    {
        $headers = PrestadorTest::auth();
        $seed_metrica =  factory(Metrica::class)->create();
        $response = $this->graphfl('create_metrica', [
            'input' => [
                'nome' => 'Teste',
                'tipo' => Metrica::TIPO_ENTREGA,
                'quantidade' => 1,
            ]
        ], $headers);

        $found_metrica = Metrica::findOrFail($response->json('data.CreateMetrica.id'));
        $this->assertEquals('Teste', $found_metrica->nome);
        $this->assertEquals(Metrica::TIPO_ENTREGA, $found_metrica->tipo);
        $this->assertEquals(1, $found_metrica->quantidade);
    }

    public function testUpdateMetrica()
    {
        $headers = PrestadorTest::auth();
        $metrica = factory(Metrica::class)->create();
        $this->graphfl('update_metrica', [
            'id' => $metrica->id,
            'input' => [
                'nome' => 'Atualizou',
                'tipo' => Metrica::TIPO_ENTREGA,
                'quantidade' => 1,
            ]
        ], $headers);
        $metrica->refresh();
        $this->assertEquals('Atualizou', $metrica->nome);
        $this->assertEquals(Metrica::TIPO_ENTREGA, $metrica->tipo);
        $this->assertEquals(1, $metrica->quantidade);
    }

    public function testDeleteMetrica()
    {
        $headers = PrestadorTest::auth();
        $metrica_to_delete = factory(Metrica::class)->create();
        $metrica_to_delete = $this->graphfl('delete_metrica', ['id' => $metrica_to_delete->id], $headers);
        $metrica_to_delete->refresh();
        $this->assertTrue($metrica_to_delete->trashed());
        $this->assertNotNull($metrica_to_delete->data_arquivado);
    }

    public function testFindMetrica()
    {
        $headers = PrestadorTest::auth();
        $metrica = factory(Metrica::class)->create();
        $response = $this->graphfl('query_metrica', [ 'id' => $metrica->id ], $headers);
        $this->assertEquals($metrica->id, $response->json('data.metricas.data.0.id'));
    }
}
