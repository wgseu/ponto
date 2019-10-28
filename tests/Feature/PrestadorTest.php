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

use App\Models\Cliente;
use App\Models\Funcao;
use Tests\TestCase;
use App\Models\Prestador;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PrestadorTest extends TestCase
{
    use RefreshDatabase;

    public static function auth()
    {
        $prestador = factory(Prestador::class)->create();
        $user = $prestador->cliente()->first();
        $token = auth()->fromUser($user);
        return [
            'Authorization' => "Bearer $token",
        ];
        $this->assertEquals(1,1);
    }

    /**
     * TODO: remove me
     */
    public function testNothing()
    {
        $this->assertTrue(true);
    }

    public function testCreatePrestador()
    {
        $headers = PrestadorTest::auth();
        $funcao_id = factory(Funcao::class)->create();
        $cliente_id = factory(Cliente::class)->create();
        $response = $this->graphfl('create_prestador', [
            'input' => [
                'codigo' => 'Teste',
                'funcao_id' => $funcao_id->id,
                'cliente_id' => $cliente_id->id,
            ]
        ], $headers);

        $found_prestador = Prestador::findOrFail($response->json('data.CreatePrestador.id'));
        $this->assertEquals('Teste', $found_prestador->codigo);
        $this->assertEquals($response->json('data.CreatePrestador.funcao_id'), $found_prestador->funcao_id);
        $this->assertEquals($response->json('data.CreatePrestador.cliente_id'), $found_prestador->cliente_id);
    }

    public function testUpdatePrestador()
    {
        $headers = PrestadorTest::auth();
        $prestador = factory(Prestador::class)->create();
        $this->graphfl('update_prestador', [
            'id' => $prestador->id,
            'input' => [
                'codigo' => 'Atualizou',
            ]
        ], $headers);
        $prestador->refresh();
        $this->assertEquals('Atualizou', $prestador->codigo);
    }

    public function testDeletePrestador()
    {
        $headers = PrestadorTest::auth();
        $prestador_to_delete = factory(Prestador::class)->create();
        $this->graphfl('delete_prestador', ['id' => $prestador_to_delete->id], $headers);
        $prestador = Prestador::find($prestador_to_delete->id);
        $this->assertNull($prestador);
    }

    public function testQueryPrestador()
    {
        $headers = PrestadorTest::auth();
        $prestador = factory(Prestador::class)->create();
        $response = $this->graphfl('query_prestador', ['id' => $prestador->id], $headers);
        $this->assertEquals($prestador->id, $response->json('data.prestadores.data.0.id'));
    }
}
