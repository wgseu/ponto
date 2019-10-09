<?php

namespace Tests\Feature;

use App\Models\Produto;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProdutoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateProduct()
    {
        $headers = PrestadorTest::auth();
        $categoria = $this->graphfl('create_category', [
            "input" => [
                "descricao" => "Bebida"
            ]
        ], $headers);
        $unidade = $this->graphfl('create_unity', [
            "input" => [
                "nome" => "Unidade",
                "sigla" => "Un"
            ]
        ], $headers);
        $produto = $this->graphfl('create_product', [
            "input" => [
                "codigo" => 14,
                "categoria_id" => $categoria->json("data.CreateCategoria.id"),
                "unidade_id" => $unidade->json("data.CreateUnidade.id"),
                "descricao" => "Pepsi",
                "preco_venda" => 3.5,
                "custo_producao" => 0
            ]
        ], $headers);
        $response = $this->graphfl('query_last_product_code');

        $this->assertEquals(
            $produto->json("data.CreateProduto.codigo"),
            $response->json("data.produtos.data.0.codigo")
        );
    }

    public function testUpdateProduct()
    {
        $headers = PrestadorTest::auth();
        $old_produto = factory(Produto::class)->create();
        $produto = $this->graphfl('update_product', [
            "id" => $old_produto->id,
            "input" => [
                "descricao" => "Vinho",
            ]
        ], $headers);
        $old_produto->refresh();
        $this->assertEquals(
            $produto->json("data.UpdateProduto.descricao"),
            $old_produto->descricao
        );
    }

    public function testDeleteProduct()
    {
        $headers = PrestadorTest::auth();
        $produto_to_delete = factory(Produto::class)->create();
        $this->graphfl('delete_product', [
            'id' => $produto_to_delete->id,
        ], $headers);
        $produto_to_delete->refresh();
        $this->assertTrue($produto_to_delete->trashed());
        $this->assertNotNull($produto_to_delete->data_arquivado);
    }
    
    public function testeQueryProduct()
    {
        $headers = PrestadorTest::auth();
        for ($i=0; $i < 10; $i++) {
            factory(Produto::class)->create();
        }

        $response = $this->graphfl('query_stock_product_filter', [], $headers);
        $this->assertTrue($response->json('data.produtos.data.0.estoque') > 10);
        $response = $this->graphfl('query_first_product_id', [], $headers);
        $this->assertTrue($response->json('data.produtos.data.0.id') == 1);
        
    }
}
