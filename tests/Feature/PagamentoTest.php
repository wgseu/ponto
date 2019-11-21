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
use App\Models\Pagamento;

class PagamentoTest extends TestCase
{
    public function testUpdatePagamento()
    {
        $headers = PrestadorTest::auth();
        $pagamento = factory(Pagamento::class)->make()->calculate();
        $pagamento->save();
        $this->graphfl('update_pagamento', [
            'id' => $pagamento->id,
            'input' => [
                'lancado' => 1.50,
            ]
        ], $headers);
        $pagamento->refresh();
        $this->assertEquals(1.50, $pagamento->lancado);
    }

    public function testFindPagamento()
    {
        $headers = PrestadorTest::auth();
        $pagamento = factory(Pagamento::class)->make()->calculate();
        $pagamento->save();
        $response = $this->graphfl('query_pagamento', [ 'id' => $pagamento->id ], $headers);
        $this->assertEquals($pagamento->id, $response->json('data.pagamentos.data.0.id'));
    }
}
