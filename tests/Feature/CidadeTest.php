<?php

namespace Tests\Feature;

use App\Models\Cidade;
use App\Models\Estado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CidadeTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCidade()
    {
        $headers = PrestadorTest::auth();
        $estado = factory(Estado::class)->create();
        $cidade = $this->graphfl('create_cidade', [
            "CidadeInput" => [
              "estado_id" => $estado->id,
              "nome" => "Paranavaí",
            ]
        ], $headers);
        $this->assertEquals(
            1,
            $cidade->json("data.CreateCidade.id")
        );
    }

    public function testFindCidade()
    {
        $headers = PrestadorTest::auth();
        $cidade = factory(Cidade::class)->create();
        $response = $this->graphfl('find_cidade_id',[
            "ID" => $cidade->id,
        ], $headers);

        $this->assertEquals(
            $cidade->nome,
            $response->json('data.cidades.data.0.nome')
        );
    }

    public function testUpdateCidade()
    {
        $headers = PrestadorTest::auth();
        $cidade = factory(Cidade::class)->create();
        $response = $this->graphfl('update_cidade', [
            "ID" => $cidade->id,
            "CidadeUpdateInput" => [
                "nome" => "Paranavaí",
              ]
        ], $headers);
        $cidade->refresh();
        $this->assertEquals(
            $cidade->nome,
            $response->json('data.UpdateCidade.nome')
        );
    }
    
    public function testDeleteCidade()
    {
        $headers = PrestadorTest::auth();
        $cidade = factory(Cidade::class)->create();
        $response = $this->graphfl('delete_cidade', [
            "ID" => $cidade->id
        ], $headers);
        $this->assertEquals(
            $cidade->id,
            $response->json("data.DeleteCidade.id")
        );
    }
}
