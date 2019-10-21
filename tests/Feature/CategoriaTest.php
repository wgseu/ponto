<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Categoria;
use Tests\TestCase;

class CategoriaTest extends TestCase
{
    use RefreshDatabase;
    
    public function testCreateCategoria()
    {
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('create_categoria', [
            'input' => [
                'descricao' => 'Teste',
            ]
        ], $headers);

        $found_categoria = Categoria::findOrFail($response->json('data.CreateCategoria.id'));
        $this->assertEquals('Teste', $found_categoria->descricao);
    }


    public function testFindCategoria()
    {
        $headers = PrestadorTest::auth();
        $categoria = factory(Categoria::class)->create();
        $response = $this->graphfl('find_categoria_id', [
            'id' => $categoria->id,
        ], $headers);

        $this->assertEquals(
            $categoria->descricao,
            $response->json('data.categorias.data.0.descricao')
        );
    }

    public function testUpdateCategoria()
    {
        $headers = PrestadorTest::auth();
        $categoria = factory(Categoria::class)->create();
        $this->graphfl('update_categoria', [
            'id' => $categoria->id,
            'input' => [
                'descricao' => 'Drinks',
                'ordem' => 3,
            ]
        ], $headers);
        $categoria->refresh();
        $this->assertEquals(
            'Drinks',
            $categoria->descricao
        );
        $this->assertEquals(
            3,
            $categoria->ordem
        );
    }
    
    public function testDeleteCategoria()
    {
        $headers = PrestadorTest::auth();
        $categoria_to_delete = factory(Categoria::class)->create();
        $this->graphfl('delete_categoria', ['id' => $categoria_to_delete->id], $headers);
        $bairro = Categoria::find($categoria_to_delete->id);
        $this->assertNull($bairro);
    }

    public function testValidateCategoriaInvalidSubcategoria()
    {
        $categoria = factory(Categoria::class)->create();
        $categoria->delete();
        $categoria->categoria_id = 100;
        $this->expectException('\Exception');
        $categoria->save();
    }

    public function testValidateCategoriaCreateSubcategoriaDeSubcategoria()
    {
        $categoriaPai = factory(Categoria::class)->create();
        $subcategoria = factory(Categoria::class)->create();
        $subcategoria->categoria_id = $categoriaPai->id;
        $subcategoria->save();
        $categoria = factory(Categoria::class)->create();
        $categoria->delete();
        $categoria->categoria_id = $subcategoria->id;
        $this->expectException('\Exception');
        $categoria->save();
    }

    public function testValidateCategoriaUpdateSubcategoriaElaMesma()
    {
        $categoria = factory(Categoria::class)->create();
        $categoria->categoria_id = $categoria->id;
        $this->expectException('\Exception');
        $categoria->save();
    }

    public function testValidateCategoriaUpdateSubcategoriaDaCategoriaPai()
    {
        $categoriaPai = factory(Categoria::class)->create();
        $subcategoria = factory(Categoria::class)->create();
        $subcategoria->categoria_id = $categoriaPai->id;
        $subcategoria->save();
        $categoria = factory(Categoria::class)->create();
        $categoriaPai->categoria_id = $categoria->id;
        $this->expectException('\Exception');
        $categoriaPai->save();
    }
}
