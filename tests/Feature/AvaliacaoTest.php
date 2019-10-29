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
use App\Models\Avaliacao;

class AvaliacaoTest extends TestCase
{
    public function testCreateAvaliacao()
    {
        $headers = PrestadorTest::auth();
        $seed_avaliacao =  factory(Avaliacao::class)->create();
        $response = $this->graphfl('create_avaliacao', [
            'input' => [
                'metrica_id' => $seed_avaliacao->metrica_id,
                'estrelas' => 1,
                'data_avaliacao' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $found_avaliacao = Avaliacao::findOrFail($response->json('data.CreateAvaliacao.id'));
        $this->assertEquals($seed_avaliacao->metrica_id, $found_avaliacao->metrica_id);
        $this->assertEquals(1, $found_avaliacao->estrelas);
        $this->assertEquals('2016-12-25 12:15:00', $found_avaliacao->data_avaliacao);
    }

    public function testUpdateAvaliacao()
    {
        $headers = PrestadorTest::auth();
        $avaliacao = factory(Avaliacao::class)->create();
        $this->graphfl('update_avaliacao', [
            'id' => $avaliacao->id,
            'input' => [
                'estrelas' => 1,
                'data_avaliacao' => '2016-12-28 12:30:00',
            ]
        ], $headers);
        $avaliacao->refresh();
        $this->assertEquals(1, $avaliacao->estrelas);
        $this->assertEquals('2016-12-28 12:30:00', $avaliacao->data_avaliacao);
    }

    public function testDeleteAvaliacao()
    {
        $headers = PrestadorTest::auth();
        $avaliacao_to_delete = factory(Avaliacao::class)->create();
        $this->graphfl('delete_avaliacao', ['id' => $avaliacao_to_delete->id], $headers);
        $avaliacao = Avaliacao::find($avaliacao_to_delete->id);
        $this->assertNull($avaliacao);
    }

    public function testFindAvaliacao()
    {
        $headers = PrestadorTest::auth();
        $avaliacao = factory(Avaliacao::class)->create();
        $response = $this->graphfl('query_avaliacao', [ 'id' => $avaliacao->id ], $headers);
        $this->assertEquals($avaliacao->id, $response->json('data.avaliacoes.data.0.id'));
    }
}
