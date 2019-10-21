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
use App\Models\Associacao;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssociacaoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateAssociacao()
    {
        $headers = PrestadorTest::auth();
        $seed_associacao =  factory(Associacao::class)->create();
        $response = $this->graphfl('create_associacao', [
            'input' => [
                'integracao_id' => $seed_associacao->integracao_id,
                'codigo' => 'Teste',
                'cliente' => 'Teste',
                'chave' => 'Teste',
                'pedido' => 'Teste',
                'quantidade' => 1.0,
                'servicos' => 1.50,
                'produtos' => 1.50,
                'descontos' => 1.50,
                'pago' => 1.50,
                'status' => Associacao::STATUS_AGENDADO,
                'sincronizado' => true,
                'integrado' => true,
                'data_pedido' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $found_associacao = Associacao::findOrFail($response->json('data.CreateAssociacao.id'));
        $this->assertEquals($seed_associacao->integracao_id, $found_associacao->integracao_id);
        $this->assertEquals('Teste', $found_associacao->codigo);
        $this->assertEquals('Teste', $found_associacao->cliente);
        $this->assertEquals('Teste', $found_associacao->chave);
        $this->assertEquals('Teste', $found_associacao->pedido);
        $this->assertEquals(1.0, $found_associacao->quantidade);
        $this->assertEquals(1.50, $found_associacao->servicos);
        $this->assertEquals(1.50, $found_associacao->produtos);
        $this->assertEquals(1.50, $found_associacao->descontos);
        $this->assertEquals(1.50, $found_associacao->pago);
        $this->assertEquals(Associacao::STATUS_AGENDADO, $found_associacao->status);
        $this->assertEquals(true, $found_associacao->sincronizado);
        $this->assertEquals(true, $found_associacao->integrado);
        $this->assertEquals('2016-12-25 12:15:00', $found_associacao->data_pedido);
    }

    public function testUpdateAssociacao()
    {
        $headers = PrestadorTest::auth();
        $associacao = factory(Associacao::class)->create();
        $this->graphfl('update_associacao', [
            'id' => $associacao->id,
            'input' => [
                'codigo' => 'Atualizou',
                'cliente' => 'Atualizou',
                'chave' => 'Atualizou',
                'pedido' => 'Atualizou',
                'quantidade' => 1.0,
                'servicos' => 1.50,
                'produtos' => 1.50,
                'descontos' => 1.50,
                'pago' => 1.50,
                'status' => Associacao::STATUS_AGENDADO,
                'sincronizado' => true,
                'integrado' => true,
                'data_pedido' => '2016-12-28 12:30:00',
            ]
        ], $headers);
        $associacao->refresh();
        $this->assertEquals('Atualizou', $associacao->codigo);
        $this->assertEquals('Atualizou', $associacao->cliente);
        $this->assertEquals('Atualizou', $associacao->chave);
        $this->assertEquals('Atualizou', $associacao->pedido);
        $this->assertEquals(1.0, $associacao->quantidade);
        $this->assertEquals(1.50, $associacao->servicos);
        $this->assertEquals(1.50, $associacao->produtos);
        $this->assertEquals(1.50, $associacao->descontos);
        $this->assertEquals(1.50, $associacao->pago);
        $this->assertEquals(Associacao::STATUS_AGENDADO, $associacao->status);
        $this->assertEquals(true, $associacao->sincronizado);
        $this->assertEquals(true, $associacao->integrado);
        $this->assertEquals('2016-12-28 12:30:00', $associacao->data_pedido);
    }

    public function testDeleteAssociacao()
    {
        $headers = PrestadorTest::auth();
        $associacao_to_delete = factory(Associacao::class)->create();
        $associacao_to_delete = $this->graphfl('delete_associacao', ['id' => $associacao_to_delete->id], $headers);
        $associacao = Associacao::find($associacao_to_delete->id);
        $this->assertNull($associacao);
    }

    public function testQueryAssociacao()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Associacao::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_associacao', [], $headers);
        $this->assertEquals(10, $response->json('data.associacoes.total'));
    }
}