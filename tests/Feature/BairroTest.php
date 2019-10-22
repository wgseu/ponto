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
use App\Models\Bairro;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BairroTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateBairro()
    {
        $headers = PrestadorTest::auth();
        $seed_bairro =  factory(Bairro::class)->create();
        $response = $this->graphfl('create_bairro', [
            'input' => [
                'cidade_id' => $seed_bairro->cidade_id,
                'nome' => 'Teste',
                'valor_entrega' => 1.50,
            ]
        ], $headers);

        $found_bairro = Bairro::findOrFail($response->json('data.CreateBairro.id'));
        $this->assertEquals($seed_bairro->cidade_id, $found_bairro->cidade_id);
        $this->assertEquals('Teste', $found_bairro->nome);
        $this->assertEquals(1.50, $found_bairro->valor_entrega);
    }

    public function testUpdateBairro()
    {
        $headers = PrestadorTest::auth();
        $bairro = factory(Bairro::class)->create();
        $this->graphfl('update_bairro', [
            'id' => $bairro->id,
            'input' => [
                'nome' => 'Atualizou',
                'valor_entrega' => 1.50,
            ]
        ], $headers);
        $bairro->refresh();
        $this->assertEquals('Atualizou', $bairro->nome);
        $this->assertEquals(1.50, $bairro->valor_entrega);
    }

    public function testDeleteBairro()
    {
        $headers = PrestadorTest::auth();
        $bairro_to_delete = factory(Bairro::class)->create();
        $bairro_to_delete = $this->graphfl('delete_bairro', ['id' => $bairro_to_delete->id], $headers);
        $bairro = Bairro::find($bairro_to_delete->id);
        $this->assertNull($bairro);
    }

    public function testFindBairro()
    {
        $headers = PrestadorTest::auth();
        $bairro = factory(Bairro::class)->create();
        $response = $this->graphfl('query_bairro', [ 'id' => $bairro->id ], $headers);
        $this->assertEquals($bairro->id, $response->json('data.bairros.data.0.id'));
    }
}
