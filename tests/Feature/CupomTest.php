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
use App\Models\Cupom;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CupomTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCupom()
    {
        $headers = PrestadorTest::auth();
        $seed_cupom =  factory(Cupom::class)->create();
        $response = $this->graphfl('create_cupom', [
            'input' => [
                'codigo' => 'Teste',
                'quantidade' => 1,
                'tipo_desconto' => Cupom::TIPO_DESCONTO_VALOR,
                'incluir_servicos' => true,
                'validade' => '2016-12-25 12:15:00',
                'data_registro' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $found_cupom = Cupom::findOrFail($response->json('data.CreateCupom.id'));
        $this->assertEquals('Teste', $found_cupom->codigo);
        $this->assertEquals(1, $found_cupom->quantidade);
        $this->assertEquals(Cupom::TIPO_DESCONTO_VALOR, $found_cupom->tipo_desconto);
        $this->assertEquals(true, $found_cupom->incluir_servicos);
        $this->assertEquals('2016-12-25 12:15:00', $found_cupom->validade);
        $this->assertEquals('2016-12-25 12:15:00', $found_cupom->data_registro);
    }

    public function testUpdateCupom()
    {
        $headers = PrestadorTest::auth();
        $cupom = factory(Cupom::class)->create();
        $this->graphfl('update_cupom', [
            'id' => $cupom->id,
            'input' => [
                'codigo' => 'Atualizou',
                'quantidade' => 1,
                'tipo_desconto' => Cupom::TIPO_DESCONTO_VALOR,
                'incluir_servicos' => true,
                'validade' => '2016-12-28 12:30:00',
                'data_registro' => '2016-12-28 12:30:00',
            ]
        ], $headers);
        $cupom->refresh();
        $this->assertEquals('Atualizou', $cupom->codigo);
        $this->assertEquals(1, $cupom->quantidade);
        $this->assertEquals(Cupom::TIPO_DESCONTO_VALOR, $cupom->tipo_desconto);
        $this->assertEquals(true, $cupom->incluir_servicos);
        $this->assertEquals('2016-12-28 12:30:00', $cupom->validade);
        $this->assertEquals('2016-12-28 12:30:00', $cupom->data_registro);
    }

    public function testDeleteCupom()
    {
        $headers = PrestadorTest::auth();
        $cupom_to_delete = factory(Cupom::class)->create();
        $cupom_to_delete = $this->graphfl('delete_cupom', ['id' => $cupom_to_delete->id], $headers);
        $cupom = Cupom::find($cupom_to_delete->id);
        $this->assertNull($cupom);
    }

    public function testFindCupom()
    {
        $headers = PrestadorTest::auth();
        $cupom = factory(Cupom::class)->create();
        $response = $this->graphfl('query_cupom', [ 'id' => $cupom->id ], $headers);
        $this->assertEquals($cupom->id, $response->json('data.cupons.data.0.id'));
    }
}
