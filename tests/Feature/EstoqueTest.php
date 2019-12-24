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
use App\Models\Compra;
use App\Models\Setor;
use App\Exceptions\ValidationException;

class EstoqueTest extends TestCase
{
    public function testCreateEstoque()
    {
        $headers = PrestadorTest::authOwner();
        $seed_estoque =  factory(Estoque::class)->make();
        $response = $this->graphfl('create_estoque', [
            'input' => [
                'produto_id' => $seed_estoque->produto_id,
                'setor_id' => $seed_estoque->setor_id,
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
        $headers = PrestadorTest::authOwner();
        $estoque = factory(Estoque::class)->create();
        $this->graphfl('update_estoque', [
            'id' => $estoque->id,
            'input' => [
                'lote' => 'ACB123',
            ]
        ], $headers);
        $estoque->refresh();
        $this->assertEquals('ACB123', $estoque->lote);
    }

    public function testFindEstoque()
    {
        $headers = PrestadorTest::authOwner();
        $estoque = factory(Estoque::class)->create();
        $response = $this->graphfl('query_estoque', [ 'id' => $estoque->id ], $headers);
        $this->assertEquals($estoque->id, $response->json('data.estoques.data.0.id'));
    }

    public function testValidateDoisAtributosEstoque()
    {
        $transacao = factory(Item::class)->make();
        $transacao->save();
        $this->expectException(ValidationException::class);
        factory(Estoque::class)->create(['transacao_id' => $transacao->id]);
    }

    public function testValidateProdutoTipoCannotPacote()
    {
        $produto = factory(Produto::class)->create(['tipo' => Produto::TIPO_PACOTE]);
        $this->expectException(ValidationException::class);
        factory(Estoque::class)->create(['produto_id' => $produto->id]);
    }

    public function testValidateProdutoNaoFracionado()
    {
        $this->expectException(ValidationException::class);
        factory(Estoque::class)->create(['quantidade' => 2.1]);
    }

    public function testValidateEntradaProdutoNegativa()
    {
        $this->expectException(ValidationException::class);
        factory(Estoque::class)->create(['quantidade' => -2]);
    }

    public function testValidateSaidaProdutoPositiva()
    {
        $transacao = factory(Item::class)->make();
        $transacao->save();
        $this->expectException(ValidationException::class);
        factory(Estoque::class)->create([
            'compra_id' => null,
            'transacao_id' => $transacao->id,
            'quantidade' => 2
        ]);
    }

    public function testValidateCreateEstoqueCancelado()
    {
        $this->expectException(ValidationException::class);
        factory(Estoque::class)->create(['cancelado' => true]);
    }

    public function testValidateEstoqueValorCompraNegativo()
    {
        $this->expectException(ValidationException::class);
        factory(Estoque::class)->create(['preco_compra' => -5]);
    }

    public function testBelongToSetor()
    {
        $estoque = factory(Estoque::class)->create();
        $expected = Setor::find($estoque->setor_id);
        $result = $estoque->setor;
        $this->assertEquals($expected, $result);
    }

    public function testBelongToCompra()
    {
        $estoque = factory(Estoque::class)->create();
        $expected = Compra::find($estoque->compra_id);
        $result = $estoque->compra;
        $this->assertEquals($expected, $result);
    }

    public function testBelongToTransacao()
    {
        $transacao = factory(Item::class)->make();
        $transacao->save();
        $estoque = factory(Estoque::class)->make([
            'compra_id' => null,
            'transacao_id' => $transacao->id,
            'quantidade' => -2,
        ]);
        factory(Estoque::class)->create([
            'setor_id' => $estoque->setor_id,
            'produto_id' => $estoque->produto_id,
        ]);
        $estoque->save();
        $result = $estoque->transacao;
        $expected = Item::find($estoque->transacao_id);
        $this->assertEquals($expected, $result);
    }

    public function testBelongToFornecedor()
    {
        $fornecedor = factory(Fornecedor::class)->create();
        $estoque = factory(Estoque::class)->create(['fornecedor_id' => $fornecedor->id]);
        $result = $estoque->fornecedor;
        $expected = Fornecedor::find($estoque->fornecedor_id);
        $this->assertEquals($expected, $result);
    }

    public function testBelongToPrestador()
    {
        $prestador = factory(Prestador::class)->create();
        $estoque = factory(Estoque::class)->create(['prestador_id' => $prestador->id]);
        $result = $estoque->prestador;
        $expected = Prestador::find($estoque->prestador_id);
        $this->assertEquals($expected, $result);
    }
}
