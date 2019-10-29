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
use App\Models\Estoque;
use App\Models\Fornecedor;
use App\Models\Item;
use App\Models\Prestador;
use App\Models\Produto;
use App\Models\Requisito;
use App\Models\Setor;
use Illuminate\Validation\ValidationException;

class EstoqueTest extends TestCase
{

    public function testCreateEstoque()
    {
        $headers = PrestadorTest::auth();
        $seed_estoque =  factory(Estoque::class)->create();
        $response = $this->graphfl('create_estoque', [
            'input' => [
                'produto_id' => $seed_estoque->produto_id,
                'setor_id' => $seed_estoque->setor_id,
                'requisito_id' => $seed_estoque->requisito_id,
                'quantidade' => 1.0,
                'data_movimento' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $found_estoque = Estoque::findOrFail($response->json('data.CreateEstoque.id'));
        $this->assertEquals($seed_estoque->produto_id, $found_estoque->produto_id);
        $this->assertEquals($seed_estoque->setor_id, $found_estoque->setor_id);
        $this->assertEquals(1.0, $found_estoque->quantidade);
        $this->assertEquals('2016-12-25 12:15:00', $found_estoque->data_movimento);
    }

    public function testUpdateEstoque()
    {
        $headers = PrestadorTest::auth();
        $estoque = factory(Estoque::class)->create();
        $this->graphfl('update_estoque', [
            'id' => $estoque->id,
            'input' => [
                'quantidade' => 1.0,
                'data_movimento' => '2016-12-28 12:30:00',
            ]
        ], $headers);
        $estoque->refresh();
        $this->assertEquals(1.0, $estoque->quantidade);
        $this->assertEquals('2016-12-28 12:30:00', $estoque->data_movimento);
    }

    public function testDeleteEstoque()
    {
        $headers = PrestadorTest::auth();
        $estoque_to_delete = factory(Estoque::class)->create();
        $this->graphfl('delete_estoque', ['id' => $estoque_to_delete->id], $headers);
        $estoque = Estoque::find($estoque_to_delete->id);
        $this->assertNull($estoque);
    }

    public function testFindEstoque()
    {
        $headers = PrestadorTest::auth();
        $estoque = factory(Estoque::class)->create();
        $response = $this->graphfl('query_estoque', [ 'id' => $estoque->id ], $headers);
        $this->assertEquals($estoque->id, $response->json('data.estoques.data.0.id'));
    }

    public function testValidateDoisAtributosEstoque()
    {
        $estoque = factory(Estoque::class)->create();
        $transacao = factory(Item::class)->create();
        $estoque->transacao_id = $transacao->id;
        $this->expectException(ValidationException::class);
        $estoque->save();
    }

    public function testValidateEntradaCannotTransacao()
    {
        $oldEstoque = factory(Estoque::class)->create();
        $transacao = factory(Item::class)->create();
        $oldEstoque->requisito_id = null;
        $oldEstoque->transacao_id = $transacao->id;
        $oldEstoque->quantidade = -2;
        $oldEstoque->save();
        
        $estoque = new Estoque();
        $estoque->produto_id = $oldEstoque->produto_id;
        $estoque->setor_id = $oldEstoque->setor_id;
        $estoque->entrada_id = $oldEstoque->id;
        $estoque->quantidade = 1.0;
        $estoque->data_movimento = '2016-12-25 12:15:00';
        $this->expectException(ValidationException::class);
        $estoque->save();
    }

    public function testValidateProdutoTipoCannotPacote()
    {
        $oldEstoque = factory(Estoque::class)->create();
        $produto = factory(Produto::class)->create();
        $produto->tipo = Produto::TIPO_PACOTE;
        $produto->save();

        $estoque = new Estoque();
        $estoque->produto_id = $produto->id;
        $estoque->setor_id = $oldEstoque->setor_id;
        $estoque->requisito_id = $oldEstoque->requisito_id;
        $estoque->quantidade = 1.0;
        $estoque->data_movimento = '2016-12-25 12:15:00';
        $this->expectException(ValidationException::class);
        $estoque->save();
    }

    public function testValidateProdutoNaoFracionado()
    {
        $estoque = factory(Estoque::class)->create();
        $estoque->quantidade = 2.1;
        $this->expectException(ValidationException::class);
        $estoque->save();
    }

    public function testValidateEntradaProdutoNegativa()
    {
        $estoque = factory(Estoque::class)->create();
        $estoque->quantidade = -2;
        $this->expectException(ValidationException::class);
        $estoque->save();
    }

    public function testValidateSaidaProdutoPositiva()
    {
        $estoque = factory(Estoque::class)->create();
        $transacao = factory(Item::class)->create();
        $estoque->requisito_id = null;
        $estoque->transacao_id = $transacao->id;
        $estoque->quantidade = 2;
        $this->expectException(ValidationException::class);
        $estoque->save();
    }

    public function testValidateAlteracaoEstoqueCancelado()
    {
        $estoque = factory(Estoque::class)->create();
        $estoque->cancelado = true;
        $estoque->save();
        $estoque->quantidade = 7;
        $this->expectException(ValidationException::class);
        $estoque->save();
    }

    public function testValidateCancelandoEntrada()
    {
        $estoque = factory(Estoque::class)->create();
        $entrada = factory(Estoque::class)->create();
        $transacao = factory(Item::class)->create();
        $estoque->requisito_id = null;
        $estoque->transacao_id = $transacao->id;
        $estoque->entrada_id = $entrada->id;
        $estoque->quantidade = -7;
        $estoque->save();
        $entrada->cancelado = true;
        $this->expectException(ValidationException::class);
        $entrada->save();
    }

    public function testValidateCreateEstoqueCancelado()
    {
        $estoque = factory(Estoque::class)->create();
        $estoque->delete();
        $estoque->cancelado = true;
        $this->expectException(ValidationException::class);
        $estoque->save();
    }

    public function testBelongToSetor()
    {
        $estoque = factory(Estoque::class)->create();
        $expected = Setor::find($estoque->setor_id);
        $result = $estoque->setor;
        $this->assertEquals($expected, $result);
    }

    public function testBelongToRequisito()
    {
        $estoque = factory(Estoque::class)->create();
        $expected = Requisito::find($estoque->requisito_id);
        $result = $estoque->requisito;
        $this->assertEquals($expected, $result);
    }

    public function testBelongToTransacao()
    {
        $transacao = factory(Item::class)->create();
        $estoque = factory(Estoque::class)->create();
        $estoque->requisito_id = null;
        $estoque->transacao_id = $transacao->id;
        $estoque->quantidade = -2;
        $estoque->save();
        $result = $estoque->transacao;
        $expected = Item::find($estoque->transacao_id);
        $this->assertEquals($expected, $result);
    }

    public function testBelongToFornecedor()
    {
        $estoque = factory(Estoque::class)->create();
        $fornecedor = factory(Fornecedor::class)->create();
        $estoque->fornecedor_id = $fornecedor->id;
        $estoque->save();
        $result = $estoque->fornecedor;
        $expected = Fornecedor::find($estoque->fornecedor_id);
        $this->assertEquals($expected, $result);
    }

    public function testBelongToPrestador()
    {
        $estoque = factory(Estoque::class)->create();
        $prestador = factory(Prestador::class)->create();
        $estoque->prestador_id = $prestador->id;
        $estoque->save();
        $result = $estoque->prestador;
        $expected = Prestador::find($estoque->prestador_id);
        $this->assertEquals($expected, $result);
    }
}
