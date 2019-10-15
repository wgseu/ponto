<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Categoria;
use Tests\TestCase;

class CategoriaTest extends TestCase
{
    use RefreshDatabase;
    
    public function testCreateCategory()
    {
        $headers = PrestadorTest::auth();
        $oldCategoria = factory(Categoria::class)->create();
        $categoria = $this->graphfl('create_categoria', [
            "CategoriaInput" => [
              "descricao" => "Saladas",
              "categoria_id" => $oldCategoria->id,
              "ordem" => 0
            ]
        ], $headers);

        $this->assertEquals(
            2,
            $categoria->json("data.CreateCategoria.id")
        );
    }

    public function testFindCategory()
    {
        $headers = PrestadorTest::auth();
        $categoria = factory(Categoria::class)->create();
        $response = $this->graphfl('find_categoria_id',[
            "ID" => $categoria->id,
        ], $headers);

        $this->assertEquals(
            $categoria->descricao,
            $response->json('data.categorias.data.0.descricao')
        );
    }

    public function testUpdateCategory()
    {
        $headers = PrestadorTest::auth();
        $categoria = factory(Categoria::class)->create();
        $response = $this->graphfl('update_categoria', [
            "ID" => $categoria->id,
            "CategoriaUpdateInput" => [
                "descricao" => "Drinks",
                "ordem" => 3,
            ]
        ], $headers);
        $categoria->refresh();
        $this->assertEquals(
            $categoria->descricao,
            $response->json('data.UpdateCategoria.descricao')
        );
        $this->assertEquals(
            $categoria->ordem,
            $response->json('data.UpdateCategoria.ordem')
        );
    }
    
    public function testDeleteCategory()
    {
        $headers = PrestadorTest::auth();
        $categoria = factory(Categoria::class)->create();
        $response = $this->graphfl('delete_categoria', [
            "ID" => $categoria->id
        ], $headers);

        $this->assertEquals(
            $categoria->descricao,
            $response->json("data.DeleteCategoria.descricao")
        );
    }
        
}
