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
use App\Models\Estoque;
use App\Models\Fornecedor;
use App\Models\Item;
use App\Models\Prestador;
use App\Models\Produto;
use App\Models\Requisito;
use App\Models\Setor;
use App\Exceptions\SafeValidationException;

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
            ]
        ], $headers);

        $found_estoque = Estoque::findOrFail($response->json('data.CreateEstoque.id'));
        $this->assertEquals($seed_estoque->produto_id, $found_estoque->produto_id);
        $this->assertEquals($seed_estoque->setor_id, $found_estoque->setor_id);
        $this->assertEquals(1.0, $found_estoque->quantidade);
    }

    public function testUpdateEstoque()
    {
        $headers = PrestadorTest::auth();
        $estoque = factory(Estoque::class)->create();
        $this->graphfl('update_estoque', [
            'id' => $estoque->id,
            'input' => [
                'quantidade' => 5,
            ]
        ], $headers);
        $estoque->refresh();
        $this->assertEquals(5, $estoque->quantidade);
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
        $this->expectException(SafeValidationException::class);
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
        $this->expectException(SafeValidationException::class);
        $estoque->save();
    }

    public function testValidateProdutoNaoFracionado()
    {
        $estoque = factory(Estoque::class)->create();
        $estoque->quantidade = 2.1;
        $this->expectException(SafeValidationException::class);
        $estoque->save();
    }

    public function testValidateEntradaProdutoNegativa()
    {
        $estoque = factory(Estoque::class)->create();
        $estoque->quantidade = -2;
        $this->expectException(SafeValidationException::class);
        $estoque->save();
    }

    public function testValidateSaidaProdutoPositiva()
    {
        $estoque = factory(Estoque::class)->create();
        $transacao = factory(Item::class)->create();
        $estoque->requisito_id = null;
        $estoque->transacao_id = $transacao->id;
        $estoque->quantidade = 2;
        $this->expectException(SafeValidationException::class);
        $estoque->save();
    }

    public function testValidateCreateEstoqueCancelado()
    {
        $estoque = factory(Estoque::class)->create();
        $estoque->delete();
        $estoque->cancelado = true;
        $this->expectException(SafeValidationException::class);
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
