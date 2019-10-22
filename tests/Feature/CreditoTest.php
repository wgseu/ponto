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
use App\Models\Credito;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreditoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCredito()
    {
        $headers = PrestadorTest::auth();
        $seed_credito =  factory(Credito::class)->create();
        $response = $this->graphfl('create_credito', [
            'input' => [
                'cliente_id' => $seed_credito->cliente_id,
                'valor' => 1.50,
                'detalhes' => 'Teste',
            ]
        ], $headers);

        $found_credito = Credito::findOrFail($response->json('data.CreateCredito.id'));
        $this->assertEquals($seed_credito->cliente_id, $found_credito->cliente_id);
        $this->assertEquals(1.50, $found_credito->valor);
        $this->assertEquals('Teste', $found_credito->detalhes);
    }

    public function testUpdateCredito()
    {
        $headers = PrestadorTest::auth();
        $credito = factory(Credito::class)->create();
        $this->graphfl('update_credito', [
            'id' => $credito->id,
            'input' => [
                'valor' => 1.50,
                'detalhes' => 'Atualizou',
            ]
        ], $headers);
        $credito->refresh();
        $this->assertEquals(1.50, $credito->valor);
        $this->assertEquals('Atualizou', $credito->detalhes);
    }

    public function testDeleteCredito()
    {
        $headers = PrestadorTest::auth();
        $credito_to_delete = factory(Credito::class)->create();
        $credito_to_delete = $this->graphfl('delete_credito', ['id' => $credito_to_delete->id], $headers);
        $credito = Credito::find($credito_to_delete->id);
        $this->assertNull($credito);
    }

    public function testFindCredito()
    {
        $headers = PrestadorTest::auth();
        $credito = factory(Credito::class)->create();
        $response = $this->graphfl('query_credito', [ 'id' => $credito->id ], $headers);
        $this->assertEquals($credito->id, $response->json('data.creditos.data.0.id'));
    }
}
