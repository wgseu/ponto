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
use App\Models\Conta;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContaTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateConta()
    {
        $headers = PrestadorTest::auth();
        $seed_conta =  factory(Conta::class)->create();
        $response = $this->graphfl('create_conta', [
            'input' => [
                'classificacao_id' => $seed_conta->classificacao_id,
                'funcionario_id' => $seed_conta->funcionario_id,
                'descricao' => 'Teste',
                'valor' => 1.50,
                'vencimento' => '2016-12-25 12:15:00',
                'data_emissao' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $found_conta = Conta::findOrFail($response->json('data.CreateConta.id'));
        $this->assertEquals($seed_conta->classificacao_id, $found_conta->classificacao_id);
        $this->assertEquals($seed_conta->funcionario_id, $found_conta->funcionario_id);
        $this->assertEquals('Teste', $found_conta->descricao);
        $this->assertEquals(1.50, $found_conta->valor);
        $this->assertEquals('2016-12-25 12:15:00', $found_conta->vencimento);
        $this->assertEquals('2016-12-25 12:15:00', $found_conta->data_emissao);
    }

    public function testUpdateConta()
    {
        $headers = PrestadorTest::auth();
        $conta = factory(Conta::class)->create();
        $this->graphfl('update_conta', [
            'id' => $conta->id,
            'input' => [
                'descricao' => 'Atualizou',
                'valor' => 1.50,
                'vencimento' => '2016-12-28 12:30:00',
                'data_emissao' => '2016-12-28 12:30:00',
            ]
        ], $headers);
        $conta->refresh();
        $this->assertEquals('Atualizou', $conta->descricao);
        $this->assertEquals(1.50, $conta->valor);
        $this->assertEquals('2016-12-28 12:30:00', $conta->vencimento);
        $this->assertEquals('2016-12-28 12:30:00', $conta->data_emissao);
    }

    public function testDeleteConta()
    {
        $headers = PrestadorTest::auth();
        $conta_to_delete = factory(Conta::class)->create();
        $conta_to_delete = $this->graphfl('delete_conta', ['id' => $conta_to_delete->id], $headers);
        $conta = Conta::find($conta_to_delete->id);
        $this->assertNull($conta);
    }

    public function testFindConta()
    {
        $headers = PrestadorTest::auth();
        $conta = factory(Conta::class)->create();
        $response = $this->graphfl('query_conta', [ 'id' => $conta->id ], $headers);
        $this->assertEquals($conta->id, $response->json('data.contas.data.0.id'));
    }
}
