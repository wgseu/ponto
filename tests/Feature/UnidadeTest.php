<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Unidade;

class UnidadeTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateUnidade()
    {
        $headers = PrestadorTest::auth();
        $unidade = $this->graphfl('create_unidade', [
            "UnidadeInput" => [
              "nome" => "Metro",
              "sigla" => "MT"
            ]
        ], $headers);

        $this->assertEquals(
            1,
            $unidade->json("data.CreateUnidade.id")
        );
    }

    public function testFindUnidade()
    {
        $headers = PrestadorTest::auth();
        $unidade = factory(Unidade::class)->create();
        $response = $this->graphfl('find_unidade_id',[
            "ID" => $unidade->id,
        ], $headers);
        $this->assertEquals(
            $unidade->nome,
            $response->json('data.unidades.data.0.nome')
        );
    }

    public function testUpdateUnidade()
    {
        $headers = PrestadorTest::auth();
        $unidade = factory(Unidade::class)->create(); 
        $response = $this->graphfl('update_unidade', [
            "ID" => $unidade->id,
            "UnidadeUpdateInput" => [
                "nome" => "Kilo",
                "sigla" => "kg"
            ]
        ], $headers);
        $unidade->refresh();
        $this->assertEquals(
            $unidade->nome,
            $response->json('data.UpdateUnidade.nome')
        );
        $this->assertEquals(
            $unidade->sigla,
            $response->json('data.UpdateUnidade.sigla')
        );
    }
    
    public function testDeleteUnidade()
    {
        $headers = PrestadorTest::auth();
        $unidade = factory(Unidade::class)->create();
        $response = $this->graphfl('delete_unidade', [
            "ID" => $unidade->id
        ], $headers);

        $this->assertEquals(
            $unidade->id,
            $response->json("data.DeleteUnidade.id")
        );
    }
        
}

