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

use App\Exceptions\ValidationException;
use App\Models\Compra;
use App\Models\Fornecedor;
use App\Models\Lista;
use App\Models\Produto;
use Tests\TestCase;
use App\Models\Requisito;

class RequisitoTest extends TestCase
{
    public function testCreateRequisito()
    {
        $headers = PrestadorTest::authOwner();
        $seed_requisito =  factory(Requisito::class)->create();
        $response = $this->graphfl('create_requisito', [
            'input' => [
                'lista_id' => $seed_requisito->lista_id,
                'produto_id' => $seed_requisito->produto_id,
            ]
        ], $headers);

        $found_requisito = Requisito::findOrFail($response->json('data.CreateRequisito.id'));
        $this->assertEquals($seed_requisito->lista_id, $found_requisito->lista_id);
        $this->assertEquals($seed_requisito->produto_id, $found_requisito->produto_id);
    }

    public function testUpdateRequisito()
    {
        $headers = PrestadorTest::authOwner();
        $requisito = factory(Requisito::class)->create();
        $produto = factory(Produto::class)->create();
        $this->graphfl('update_requisito', [
            'id' => $requisito->id,
            'input' => [
                'produto_id' => $produto->id,
            ]
        ], $headers);
        $requisito->refresh();
        $this->assertEquals($produto->id, $requisito->produto_id);
    }

    public function testDeleteRequisito()
    {
        $headers = PrestadorTest::authOwner();
        $requisito_to_delete = factory(Requisito::class)->create();
        $this->graphfl('delete_requisito', ['id' => $requisito_to_delete->id], $headers);
        $requisito = Requisito::find($requisito_to_delete->id);
        $this->assertNull($requisito);
    }

    public function testFindRequisito()
    {
        $headers = PrestadorTest::authOwner();
        $requisito = factory(Requisito::class)->create();
        $response = $this->graphfl('query_requisito', [ 'id' => $requisito->id ], $headers);

        $listaExpect = Lista::find($response->json('data.requisitos.data.0.lista_id'));
        $listaResult = $requisito->lista;
        $this->assertEquals($listaExpect, $listaResult);

        $produtoExpect = Produto::find($response->json('data.requisitos.data.0.produto_id'));
        $produtoResult = $requisito->produto;
        $this->assertEquals($produtoExpect, $produtoResult);

        $this->assertEquals($requisito->id, $response->json('data.requisitos.data.0.id'));
    }

    public function testValidateRequisitoFornecedorDiferenteCompra()
    {
        $compra = factory(Compra::class)->create();
        $fornecedor = factory(Fornecedor::class)->create();
        $this->expectException(ValidationException::class);
        factory(Requisito::class)->create([
            'compra_id' => $compra->id,
            'fornecedor_id' => $fornecedor->id
        ]);
    }

    public function testValidateRequisitoCompradoMaiorQuantidade()
    {
        $this->expectException(ValidationException::class);
        factory(Requisito::class)->create(['comprado' => 10, 'quantidade' => 2]);
    }

    public function testValidateRequisitoQuantidadeNegativa()
    {
        $this->expectException(ValidationException::class);
        factory(Requisito::class)->create(['quantidade' => -72]);
    }

    public function testValidateRequisitoCompradoNegativo()
    {
        $this->expectException(ValidationException::class);
        factory(Requisito::class)->create(['comprado' => -10]);
    }

    public function testValidateRequisitoPrecoMaximoNegativo()
    {
        $this->expectException(ValidationException::class);
        factory(Requisito::class)->create(['preco_maximo' => -50]);
    }

    public function testValidateRequisitoPrecoNegativo()
    {
        $this->expectException(ValidationException::class);
        factory(Requisito::class)->create(['preco' => -9]);
    }

    public function testRequisitoBelongToFornecedor()
    {
        $fornecedor = factory(Fornecedor::class)->create();
        $requisito = factory(Requisito::class)->create(['fornecedor_id' => $fornecedor->id]);
        $expective = Fornecedor::find($requisito->fornecedor_id);
        $result = $requisito->fornecedor;
        $this->assertEquals($expective, $result);
    }
}
