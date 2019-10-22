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
use App\Models\Resumo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResumoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateResumo()
    {
        $headers = PrestadorTest::auth();
        $seed_resumo =  factory(Resumo::class)->create();
        $response = $this->graphfl('create_resumo', [
            'input' => [
                'movimentacao_id' => $seed_resumo->movimentacao_id,
                'forma_id' => $seed_resumo->forma_id,
                'valor' => 1.50,
            ]
        ], $headers);

        $found_resumo = Resumo::findOrFail($response->json('data.CreateResumo.id'));
        $this->assertEquals($seed_resumo->movimentacao_id, $found_resumo->movimentacao_id);
        $this->assertEquals($seed_resumo->forma_id, $found_resumo->forma_id);
        $this->assertEquals(1.50, $found_resumo->valor);
    }

    public function testUpdateResumo()
    {
        $headers = PrestadorTest::auth();
        $resumo = factory(Resumo::class)->create();
        $this->graphfl('update_resumo', [
            'id' => $resumo->id,
            'input' => [
                'valor' => 1.50,
            ]
        ], $headers);
        $resumo->refresh();
        $this->assertEquals(1.50, $resumo->valor);
    }

    public function testDeleteResumo()
    {
        $headers = PrestadorTest::auth();
        $resumo_to_delete = factory(Resumo::class)->create();
        $resumo_to_delete = $this->graphfl('delete_resumo', ['id' => $resumo_to_delete->id], $headers);
        $resumo = Resumo::find($resumo_to_delete->id);
        $this->assertNull($resumo);
    }

    public function testFindResumo()
    {
        $headers = PrestadorTest::auth();
        $resumo = factory(Resumo::class)->create();
        $response = $this->graphfl('query_resumo', [ 'id' => $resumo->id ], $headers);
        $this->assertEquals($resumo->id, $response->json('data.resumos.data.0.id'));
    }
}
