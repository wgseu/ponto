<?php

namespace Tests\Feature;

use App\Models\Moeda;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoedaTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateMoeda()
    {
        $headers = PrestadorTest::auth();
        $moeda = $this->graphfl('create_moeda', [
            "MoedaInput" => [
                "nome" => "Cruzado",
                "simbolo" => "X",
                "codigo" => "1",
                "divisao" => 100,
                "formato" => "X {value}",
                "ativa" => true,
                "conversao" => 1
            ]
        ], $headers);

        $this->assertEquals(
            1,
            $moeda->json("data.CreateMoeda.id")
        );
    }

    public function testFindMoeda()
    {
        $headers = PrestadorTest::auth();
        $moeda = factory(Moeda::class)->create();
        $response = $this->graphfl('find_moeda_id',[
            "ID" => $moeda->id,
        ], $headers);
        $this->assertEquals(
            $moeda->nome,
            $response->json('data.moedas.data.0.nome')
        );
    }
    
    public function testUpdateMoeda()
    {
        $headers = PrestadorTest::auth();
        $moeda = factory(Moeda::class)->create();
        $response = $this->graphfl('update_moeda', [
            "ID" => $moeda->id,
            "MoedaUpdateInput" => [
                "nome" => "Cruzado",
                "simbolo" => "X",
                "codigo" => "1",
                "divisao" => 100,
                "formato" => "X {value}",
                "ativa" => true,
                "conversao" => 1
            ]
        ], $headers);
        $moeda->refresh();
        $this->assertEquals(
            $moeda->nome,
            $response->json('data.UpdateMoeda.nome')
        );
    }
    
    public function testDeleteMoeda()
    {
        $headers = PrestadorTest::auth();
        $moeda = factory(Moeda::class)->create();
        $response = $this->graphfl('delete_moeda', [
            "ID" => $moeda->id
        ], $headers);

        $this->assertEquals(
            $moeda->nome,
            $response->json("data.DeleteMoeda.nome")
        );
    }
}
