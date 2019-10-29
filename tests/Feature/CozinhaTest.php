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
use App\Models\Cozinha;

class CozinhaTest extends TestCase
{
    public function testCreateCozinha()
    {
        $headers = PrestadorTest::auth();
        $seed_cozinha =  factory(Cozinha::class)->create();
        $response = $this->graphfl('create_cozinha', [
            'input' => [
                'nome' => 'Teste',
            ]
        ], $headers);

        $found_cozinha = Cozinha::findOrFail($response->json('data.CreateCozinha.id'));
        $this->assertEquals('Teste', $found_cozinha->nome);
    }

    public function testUpdateCozinha()
    {
        $headers = PrestadorTest::auth();
        $cozinha = factory(Cozinha::class)->create();
        $this->graphfl('update_cozinha', [
            'id' => $cozinha->id,
            'input' => [
                'nome' => 'Atualizou',
            ]
        ], $headers);
        $cozinha->refresh();
        $this->assertEquals('Atualizou', $cozinha->nome);
    }

    public function testDeleteCozinha()
    {
        $headers = PrestadorTest::auth();
        $cozinha_to_delete = factory(Cozinha::class)->create();
        $this->graphfl('delete_cozinha', ['id' => $cozinha_to_delete->id], $headers);
        $cozinha = Cozinha::find($cozinha_to_delete->id);
        $this->assertNull($cozinha);
    }

    public function testFindCozinha()
    {
        $headers = PrestadorTest::auth();
        $cozinha = factory(Cozinha::class)->create();
        $response = $this->graphfl('query_cozinha', [ 'id' => $cozinha->id ], $headers);
        $this->assertEquals($cozinha->id, $response->json('data.cozinhas.data.0.id'));
    }
}
