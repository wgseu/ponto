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
use App\Models\Pagamento;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PagamentoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePagamento()
    {
        $headers = PrestadorTest::auth();
        $seed_pagamento =  factory(Pagamento::class)->create();
        $response = $this->graphfl('create_pagamento', [
            'input' => [
                'carteira_id' => $seed_pagamento->carteira_id,
                'moeda_id' => $seed_pagamento->moeda_id,
                'valor' => 1.50,
                'lancado' => 1.50,
            ]
        ], $headers);

        $found_pagamento = Pagamento::findOrFail($response->json('data.CreatePagamento.id'));
        $this->assertEquals($seed_pagamento->carteira_id, $found_pagamento->carteira_id);
        $this->assertEquals($seed_pagamento->moeda_id, $found_pagamento->moeda_id);
        $this->assertEquals(1.50, $found_pagamento->valor);
        $this->assertEquals(1.50, $found_pagamento->lancado);
    }

    public function testUpdatePagamento()
    {
        $headers = PrestadorTest::auth();
        $pagamento = factory(Pagamento::class)->create();
        $this->graphfl('update_pagamento', [
            'id' => $pagamento->id,
            'input' => [
                'valor' => 1.50,
                'lancado' => 1.50,
            ]
        ], $headers);
        $pagamento->refresh();
        $this->assertEquals(1.50, $pagamento->valor);
        $this->assertEquals(1.50, $pagamento->lancado);
    }

    public function testDeletePagamento()
    {
        $headers = PrestadorTest::auth();
        $pagamento_to_delete = factory(Pagamento::class)->create();
        $pagamento_to_delete = $this->graphfl('delete_pagamento', ['id' => $pagamento_to_delete->id], $headers);
        $pagamento = Pagamento::find($pagamento_to_delete->id);
        $this->assertNull($pagamento);
    }

    public function testFindPagamento()
    {
        $headers = PrestadorTest::auth();
        $pagamento = factory(Pagamento::class)->create();
        $response = $this->graphfl('query_pagamento', [ 'id' => $pagamento->id ], $headers);
        $this->assertEquals($pagamento->id, $response->json('data.pagamentos.data.0.id'));
    }
}
