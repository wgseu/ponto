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
use App\Models\Origem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrigemTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateOrigem()
    {
        $headers = PrestadorTest::auth();
        $seed_origem =  factory(Origem::class)->create();
        $response = $this->graphfl('create_origem', [
            'input' => [
                'codigo' => 1,
                'descricao' => 'Teste',
            ]
        ], $headers);

        $found_origem = Origem::findOrFail($response->json('data.CreateOrigem.id'));
        $this->assertEquals(1, $found_origem->codigo);
        $this->assertEquals('Teste', $found_origem->descricao);
    }

    public function testUpdateOrigem()
    {
        $headers = PrestadorTest::auth();
        $origem = factory(Origem::class)->create();
        $this->graphfl('update_origem', [
            'id' => $origem->id,
            'input' => [
                'codigo' => 1,
                'descricao' => 'Atualizou',
            ]
        ], $headers);
        $origem->refresh();
        $this->assertEquals(1, $origem->codigo);
        $this->assertEquals('Atualizou', $origem->descricao);
    }

    public function testDeleteOrigem()
    {
        $headers = PrestadorTest::auth();
        $origem_to_delete = factory(Origem::class)->create();
        $origem_to_delete = $this->graphfl('delete_origem', ['id' => $origem_to_delete->id], $headers);
        $origem = Origem::find($origem_to_delete->id);
        $this->assertNull($origem);
    }

    public function testFindOrigem()
    {
        $headers = PrestadorTest::auth();
        $origem = factory(Origem::class)->create();
        $response = $this->graphfl('query_origem', [ 'id' => $origem->id ], $headers);
        $this->assertEquals($origem->id, $response->json('data.origens.data.0.id'));
    }
}
