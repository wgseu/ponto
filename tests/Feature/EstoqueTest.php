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
use App\Models\Estoque;

class EstoqueTest extends TestCase
{
    public function testCreateEstoque()
    {
        $headers = PrestadorTest::auth();
        $seed_estoque =  factory(Estoque::class)->create();
        $response = $this->graphfl('create_estoque', [
            'input' => [
                'produto_id' => $seed_estoque->produto_id,
                'setor_id' => $seed_estoque->setor_id,
                'quantidade' => 1.0,
            ]
        ], $headers);

        $found_estoque = Estoque::findOrFail($response->json('data.CreateEstoque.id'));
        $this->assertEquals($seed_estoque->produto_id, $found_estoque->produto_id);
        $this->assertEquals($seed_estoque->setor_id, $found_estoque->setor_id);
        $this->assertEquals(1.0, $found_estoque->quantidade);
    }

    public function testUpdateEstoque()
    {
        $headers = PrestadorTest::auth();
        $estoque = factory(Estoque::class)->create();
        $this->graphfl('update_estoque', [
            'id' => $estoque->id,
            'input' => [
                'quantidade' => 5,
            ]
        ], $headers);
        $estoque->refresh();
        $this->assertEquals(5, $estoque->quantidade);
    }

    public function testDeleteEstoque()
    {
        $headers = PrestadorTest::auth();
        $estoque_to_delete = factory(Estoque::class)->create();
        $this->graphfl('delete_estoque', ['id' => $estoque_to_delete->id], $headers);
        $estoque = Estoque::find($estoque_to_delete->id);
        $this->assertNull($estoque);
    }

    public function testFindEstoque()
    {
        $headers = PrestadorTest::auth();
        $estoque = factory(Estoque::class)->create();
        $response = $this->graphfl('query_estoque', [ 'id' => $estoque->id ], $headers);
        $this->assertEquals($estoque->id, $response->json('data.estoques.data.0.id'));
    }
}
