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
use App\Models\Forma;

class FormaTest extends TestCase
{
    public function testCreateForma()
    {
        $headers = PrestadorTest::authOwner();
        $seed_forma =  factory(Forma::class)->create();
        $response = $this->graphfl('create_forma', [
            'input' => [
                'tipo' => Forma::TIPO_DINHEIRO,
                'carteira_id' => $seed_forma->carteira_id,
                'descricao' => 'Teste',
            ]
        ], $headers);

        $found_forma = Forma::findOrFail($response->json('data.CreateForma.id'));
        $this->assertEquals(Forma::TIPO_DINHEIRO, $found_forma->tipo);
        $this->assertEquals($seed_forma->carteira_id, $found_forma->carteira_id);
        $this->assertEquals('Teste', $found_forma->descricao);
    }

    public function testUpdateForma()
    {
        $headers = PrestadorTest::authOwner();
        $forma = factory(Forma::class)->create();
        $this->graphfl('update_forma', [
            'id' => $forma->id,
            'input' => [
                'tipo' => Forma::TIPO_DINHEIRO,
                'descricao' => 'Atualizou',
            ]
        ], $headers);
        $forma->refresh();
        $this->assertEquals(Forma::TIPO_DINHEIRO, $forma->tipo);
        $this->assertEquals('Atualizou', $forma->descricao);
    }

    public function testDeleteForma()
    {
        $headers = PrestadorTest::authOwner();
        $forma_to_delete = factory(Forma::class)->create();
        $this->graphfl('delete_forma', ['id' => $forma_to_delete->id], $headers);
        $forma = Forma::find($forma_to_delete->id);
        $this->assertNull($forma);
    }

    public function testFindForma()
    {
        $headers = PrestadorTest::authOwner();
        $forma = factory(Forma::class)->create();
        $response = $this->graphfl('query_forma', [ 'id' => $forma->id ], $headers);
        $this->assertEquals($forma->id, $response->json('data.formas.data.0.id'));
    }
}
