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
use App\Models\Compra;

class CompraTest extends TestCase
{
    public function testCreateCompra()
    {
        $headers = PrestadorTest::authOwner();
        $seed_compra =  factory(Compra::class)->create();
        $response = $this->graphfl('create_compra', [
            'input' => [
                'comprador_id' => $seed_compra->comprador_id,
                'fornecedor_id' => $seed_compra->fornecedor_id,
                'data_compra' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $found_compra = Compra::findOrFail($response->json('data.CreateCompra.id'));
        $this->assertEquals($seed_compra->comprador_id, $found_compra->comprador_id);
        $this->assertEquals($seed_compra->fornecedor_id, $found_compra->fornecedor_id);
        $this->assertEquals('2016-12-25 12:15:00', $found_compra->data_compra);
    }

    public function testUpdateCompra()
    {
        $headers = PrestadorTest::authOwner();
        $compra = factory(Compra::class)->create();
        $this->graphfl('update_compra', [
            'id' => $compra->id,
            'input' => [
                'data_compra' => '2016-12-28 12:30:00',
            ]
        ], $headers);
        $compra->refresh();
        $this->assertEquals('2016-12-28 12:30:00', $compra->data_compra);
    }

    public function testDeleteCompra()
    {
        $headers = PrestadorTest::authOwner();
        $compra_to_delete = factory(Compra::class)->create();
        $this->graphfl('delete_compra', ['id' => $compra_to_delete->id], $headers);
        $compra = Compra::find($compra_to_delete->id);
        $this->assertNull($compra);
    }

    public function testFindCompra()
    {
        $headers = PrestadorTest::authOwner();
        $compra = factory(Compra::class)->create();
        $response = $this->graphfl('query_compra', [ 'id' => $compra->id ], $headers);
        $this->assertEquals($compra->id, $response->json('data.compras.data.0.id'));
    }
}
