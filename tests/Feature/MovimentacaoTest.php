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
use App\Models\Movimentacao;

class MovimentacaoTest extends TestCase
{
    public function testCreateMovimentacao()
    {
        $headers = PrestadorTest::authOwner();
        $seed_movimentacao =  factory(Movimentacao::class)->create();
        $response = $this->graphfl('create_movimentacao', [
            'input' => [
                'sessao_id' => $seed_movimentacao->sessao_id,
                'caixa_id' => $seed_movimentacao->caixa_id,
                'iniciador_id' => $seed_movimentacao->iniciador_id,
                'data_abertura' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $found_movimentacao = Movimentacao::findOrFail($response->json('data.CreateMovimentacao.id'));
        $this->assertEquals($seed_movimentacao->sessao_id, $found_movimentacao->sessao_id);
        $this->assertEquals($seed_movimentacao->caixa_id, $found_movimentacao->caixa_id);
        $this->assertEquals($seed_movimentacao->iniciador_id, $found_movimentacao->iniciador_id);
        $this->assertEquals('2016-12-25 12:15:00', $found_movimentacao->data_abertura);
    }

    public function testUpdateMovimentacao()
    {
        $headers = PrestadorTest::authOwner();
        $movimentacao = factory(Movimentacao::class)->create();
        $this->graphfl('update_movimentacao', [
            'id' => $movimentacao->id,
            'input' => [
                'data_abertura' => '2016-12-28 12:30:00',
            ]
        ], $headers);
        $movimentacao->refresh();
        $this->assertEquals('2016-12-28 12:30:00', $movimentacao->data_abertura);
    }

    public function testFindMovimentacao()
    {
        $headers = PrestadorTest::authOwner();
        $movimentacao = factory(Movimentacao::class)->create();
        $response = $this->graphfl('query_movimentacao', [ 'id' => $movimentacao->id ], $headers);
        $this->assertEquals($movimentacao->id, $response->json('data.movimentacoes.data.0.id'));
    }
}
