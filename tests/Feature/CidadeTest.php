<?php

namespace Tests\Feature;

use App\Models\Cidade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CidadeTest extends TestCase
{
    use RefreshDatabase;

    public function create()
    {
        $estado = new EstadoTest();
        $estado->create();
        $cidade = factory(Cidade::class)->create();
        return $cidade;
    }

    public function biuld()
    {
        $estado = new EstadoTest();
        $est = $estado->create();
        return $est;
    }

    public function testCreateCidade()
    {
        $headers = PrestadorTest::auth();
        $estado = self::biuld();
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
        $cidade = $this->create();
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
        $estado = self::biuld();
        $cidade = factory(Cidade::class)->create();
        $response = $this->graphfl('update_cidade', [
            "ID" => $cidade->id,
            "CidadeUpdateInput" => [
                "estado_id" => $estado->id,
                "nome" => "Paranavaí",
              ]
        ], $headers);
        $cidade->refresh();
        $this->assertEquals(
            $cidade->nome,
            $response->json('data.UpdateCidade.nome')
        );
        $this->assertEquals(
            $cidade->estado_id,
            $response->json('data.UpdateCidade.estado_id')
        );
    }
    
    public function testDeleteCidade()
    {
        $headers = PrestadorTest::auth();
        $cidade = $this->create();
        $response = $this->graphfl('delete_cidade', [
            "ID" => $cidade->id
        ], $headers);
        $this->assertEquals(
            $cidade->id,
            $response->json("data.DeleteCidade.id")
        );
    }
}
