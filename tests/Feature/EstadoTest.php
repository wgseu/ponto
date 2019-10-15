<?php

namespace Tests\Feature;

use App\Models\Estado;
use App\Models\Moeda;
use App\Models\Pais;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EstadoTest extends TestCase
{
    use RefreshDatabase; 

    public function testCreateEstado()
    {
        $headers = PrestadorTest::auth();
        $pais = factory(Pais::class)->create();
        $estado = $this->graphfl('create_estado', [
            "EstadoInput" => [
              "nome" => "Paraná",
              "uf" => "PR",
              "pais_id" => $pais->id
            ]
        ], $headers);
        $this->assertEquals(
            1,
            $estado->json("data.CreateEstado.id")
        );
    }

    public function testFindEstado()
    {
        $headers = PrestadorTest::auth();
        $estado = factory(Estado::class)->create();
        $response = $this->graphfl('find_estado_id',[
            "ID" => $estado->id,
        ], $headers);

        $this->assertEquals(
            $estado->nome,
            $response->json('data.estados.data.0.nome')
        );
    }

    public function testUpdateEstado()
    {
        $headers = PrestadorTest::auth();
        $estado = factory(Estado::class)->create();
        $response = $this->graphfl('update_estado', [
            "ID" => $estado->id,
            "EstadoUpdateInput" => [
                "nome" => "Paraná",
                "uf" => "PR",
            ]
        ], $headers);

        $estado->refresh();
        $this->assertEquals(
            $estado->nome,
            $response->json('data.UpdateEstado.nome')
        );
        $this->assertEquals(
            $estado->uf,
            $response->json('data.UpdateEstado.uf')
        );
    }
    
    public function testDeleteEstado()
    {
        $headers = PrestadorTest::auth();
        $estado = factory(Estado::class)->create();
        $response = $this->graphfl('delete_estado', [
            "ID" => $estado->id
        ], $headers);

        $this->assertEquals(
            $estado->id,
            $response->json("data.DeleteEstado.id")
        );
    }
}
