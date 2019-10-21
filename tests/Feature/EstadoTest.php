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
use App\Models\Estado;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EstadoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateEstado()
    {
        $headers = PrestadorTest::auth();
        $seed_estado =  factory(Estado::class)->create();
        $response = $this->graphfl('create_estado', [
            'input' => [
                'pais_id' => $seed_estado->pais_id,
                'nome' => 'Teste',
                'uf' => 'Teste',
            ]
        ], $headers);

        $found_estado = Estado::findOrFail($response->json('data.CreateEstado.id'));
        $this->assertEquals($seed_estado->pais_id, $found_estado->pais_id);
        $this->assertEquals('Teste', $found_estado->nome);
        $this->assertEquals('Teste', $found_estado->uf);
    }

    public function testUpdateEstado()
    {
        $headers = PrestadorTest::auth();
        $estado = factory(Estado::class)->create();
        $this->graphfl('update_estado', [
            'id' => $estado->id,
            'input' => [
                'nome' => 'Atualizou',
                'uf' => 'Atualizou',
            ]
        ], $headers);
        $estado->refresh();
        $this->assertEquals('Atualizou', $estado->nome);
        $this->assertEquals('Atualizou', $estado->uf);
    }

    public function testDeleteEstado()
    {
        $headers = PrestadorTest::auth();
        $estado_to_delete = factory(Estado::class)->create();
        $estado_to_delete = $this->graphfl('delete_estado', ['id' => $estado_to_delete->id], $headers);
        $estado = Estado::find($estado_to_delete->id);
        $this->assertNull($estado);
    }

    public function testQueryEstado()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Estado::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_estado', [], $headers);
        $this->assertEquals(10, $response->json('data.estados.total'));
    }
}