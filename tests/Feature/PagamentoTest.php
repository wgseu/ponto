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

use App\Models\Caixa;
use App\Models\Carteira;
use App\Models\Dispositivo;
use App\Models\Forma;
use App\Models\Movimentacao;
use Tests\TestCase;
use App\Models\Pagamento;
use App\Models\Prestador;
use App\Models\Saldo;
use App\Models\Sessao;

class PagamentoTest extends TestCase
{
    public function testCreateTransferencia()
    {
        $headers = PrestadorTest::authOwner();
        $origem =  factory(Carteira::class)->create(['tipo' => Carteira::TIPO_LOCAL]);
        $destino =  factory(Carteira::class)->create();
        factory(Caixa::class)->create(['carteira_id' => $origem->id]);
        factory(Forma::class)->create();
        factory(Saldo::class)->create([
            'carteira_id' => $origem->id,
            'valor' => 500,
            'moeda_id' =>  app('currency')->id
        ]);
        $response = $this->graphfl('create_transferencia', [
            'input' => [
                'origem_id' => $origem->id,
                'destino_id' => $destino->id,
                'valor' => 100,
            ]
        ], $headers);

        $pagamento = Pagamento::findOrFail($response->json('data.CreateTransferencia.id'));
        $this->assertEquals(100, $pagamento->valor);
        $this->assertEquals($destino->id, $pagamento->carteira_id);
    }
    
    public function testUpdatePagamento()
    {
        $headers = PrestadorTest::authOwner();
        $pagamento = factory(Pagamento::class)->make();
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
        $headers = PrestadorTest::authOwner();
        $pagamento = factory(Pagamento::class)->make();
        $pagamento->save();
        $response = $this->graphfl('query_pagamento', [ 'id' => $pagamento->id ], $headers);
        $this->assertEquals($pagamento->id, $response->json('data.pagamentos.data.0.id'));
    }
}
