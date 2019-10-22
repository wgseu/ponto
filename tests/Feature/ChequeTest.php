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
use App\Models\Cheque;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChequeTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCheque()
    {
        $headers = PrestadorTest::auth();
        $seed_cheque =  factory(Cheque::class)->create();
        $response = $this->graphfl('create_cheque', [
            'input' => [
                'cliente_id' => $seed_cheque->cliente_id,
                'banco_id' => $seed_cheque->banco_id,
                'agencia' => 'Teste',
                'conta' => 'Teste',
                'numero' => 'Teste',
                'valor' => 1.50,
                'vencimento' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $found_cheque = Cheque::findOrFail($response->json('data.CreateCheque.id'));
        $this->assertEquals($seed_cheque->cliente_id, $found_cheque->cliente_id);
        $this->assertEquals($seed_cheque->banco_id, $found_cheque->banco_id);
        $this->assertEquals('Teste', $found_cheque->agencia);
        $this->assertEquals('Teste', $found_cheque->conta);
        $this->assertEquals('Teste', $found_cheque->numero);
        $this->assertEquals(1.50, $found_cheque->valor);
        $this->assertEquals('2016-12-25 12:15:00', $found_cheque->vencimento);
    }

    public function testUpdateCheque()
    {
        $headers = PrestadorTest::auth();
        $cheque = factory(Cheque::class)->create();
        $this->graphfl('update_cheque', [
            'id' => $cheque->id,
            'input' => [
                'agencia' => 'Atualizou',
                'conta' => 'Atualizou',
                'numero' => 'Atualizou',
                'valor' => 1.50,
                'vencimento' => '2016-12-28 12:30:00',
            ]
        ], $headers);
        $cheque->refresh();
        $this->assertEquals('Atualizou', $cheque->agencia);
        $this->assertEquals('Atualizou', $cheque->conta);
        $this->assertEquals('Atualizou', $cheque->numero);
        $this->assertEquals(1.50, $cheque->valor);
        $this->assertEquals('2016-12-28 12:30:00', $cheque->vencimento);
    }

    public function testDeleteCheque()
    {
        $headers = PrestadorTest::auth();
        $cheque_to_delete = factory(Cheque::class)->create();
        $cheque_to_delete = $this->graphfl('delete_cheque', ['id' => $cheque_to_delete->id], $headers);
        $cheque = Cheque::find($cheque_to_delete->id);
        $this->assertNull($cheque);
    }

    public function testFindCheque()
    {
        $headers = PrestadorTest::auth();
        $cheque = factory(Cheque::class)->create();
        $response = $this->graphfl('query_cheque', [ 'id' => $cheque->id ], $headers);
        $this->assertEquals($cheque->id, $response->json('data.cheques.data.0.id'));
    }
}
