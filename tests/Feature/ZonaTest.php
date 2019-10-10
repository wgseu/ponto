<?php

namespace Tests\Feature;

use App\Models\Zona;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ZonaTest extends TestCase
{
    use RefreshDatabase;

    public function create()
    {
        $bairro = new BairroTest();
        $bar = $bairro->create();
        $zona = factory(Zona::class)->create();
        return $zona;
    }

    public function biuld()
    {
        $bairro = new BairroTest();
        $bar = $bairro->create();
        return $bar;
    }

    public function testCreateBairro()
    {
        $headers = PrestadorTest::auth();
        $bairro = self::biuld();
        $zona = $this->graphfl('create_zona', [
            "ZonaInput" => [
                "bairro_id" => $bairro->id,
                "nome"=> "Zona 3",
                "adicional_entrega" => 1,
                "disponivel" => true,
            ]
        ], $headers);
        $this->assertEquals(
            1,
            $zona->json("data.CreateZona.id")
        );
    }

    public function testFindBairro()
    {
        $headers = PrestadorTest::auth();
        $zona = $this->create();
        $response = $this->graphfl('find_zona_id',[
            "ID" => $zona->id,
        ], $headers);

        $this->assertEquals(
            $zona->nome,
            $response->json('data.zonas.data.0.nome')
        );
    }

    public function testUpdateBairro()
    {
        $headers = PrestadorTest::auth();
        $bairro = self::biuld();
        $zona = factory(Zona::class)->create();
        $response = $this->graphfl('update_zona', [
            "ID" => $bairro->id,
            "ZonaUpdateInput" => [
                "bairro_id" => $zona->id,
                "nome"=> "Area 51",
                "adicional_entrega" => 20,
                "disponivel" => true,
              ]
        ], $headers);
        $zona->refresh();
        $this->assertEquals(
            $zona->nome,
            $response->json('data.UpdateZona.nome')
        );
        $this->assertEquals(
            $zona->bairro_id,
            $response->json('data.UpdateZona.bairro_id')
        );
        $this->assertEquals(
            $zona->adicional_entrega,
            $response->json('data.UpdateZona.adicional_entrega')
        );
        $this->assertEquals(
            $zona->disponivel,
            $response->json('data.UpdateZona.disponivel')
        );
    }
    
    public function testDeleteBairro()
    {
        $headers = PrestadorTest::auth();
        $zona = $this->create();
        $response = $this->graphfl('delete_zona', [
            "ID" => $zona->id
        ], $headers);
        $this->assertEquals(
            $zona->id,
            $response->json("data.DeleteZona.id")
        );
    }
}
