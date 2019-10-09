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
        $seed_product = factory(Produto::class)->create();
        $response = $this->graphfl('create_product', [
            "input" => [
                "codigo" => 14,
                "categoria_id" => $seed_product->categoria_id,
                "unidade_id" => $seed_product->unidade_id,
                "descricao" => 'Pepsi',
                "preco_venda" => 3.5
            ]
        ], $headers);
        $found_product = Produto::findOrFail($response->json("data.CreateProduto.id"));
        $this->assertEquals(14, $found_product->codigo);
        $this->assertEquals('Pepsi', $found_product->descricao);
        $this->assertEquals(3.5, $found_product->preco_venda);
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
        for ($i=0; $i < 10; $i++) {
            factory(Produto::class)->create();
        }

        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_products', [], $headers);
        $this->assertEquals(10, $response->json('data.produtos.total'));
    }
}
