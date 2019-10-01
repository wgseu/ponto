<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Produto;
use App\Models\Unidade;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProdutoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateProduct()
    {
        factory(Categoria::class)->create();
        factory(Unidade::class)->create();
        $produto = factory(Produto::class)->create();
    
        $response = $this->graphfl('query_last_product_code');

        $this->assertEquals(
            $produto->codigo,
            $response->json("data.produtos.data.0.codigo")
        );
    }
}
