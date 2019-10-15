<?php

namespace Tests\Feature;

use App\Models\Bairro;
use App\Models\Zona;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ZonaTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateZona()
    {
        $headers = PrestadorTest::auth();
        $bairro = factory(Bairro::class)->create();
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

    public function testFindZona()
    {
        $headers = PrestadorTest::auth();
        $zona = factory(Zona::class)->create();
        $response = $this->graphfl('find_zona_id',[
            "ID" => $zona->id,
        ], $headers);

        $this->assertEquals(
            $zona->nome,
            $response->json('data.zonas.data.0.nome')
        );
    }

    public function testUpdateZona()
    {
        $headers = PrestadorTest::auth();
        $zona = factory(Zona::class)->create();
        $response = $this->graphfl('update_zona', [
            "ID" => $zona->id,
            "ZonaUpdateInput" => [
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
            $zona->adicional_entrega,
            $response->json('data.UpdateZona.adicional_entrega')
        );
        $this->assertEquals(
            $zona->disponivel,
            $response->json('data.UpdateZona.disponivel')
        );
    }
    
    public function testDeleteZona()
    {
        $headers = PrestadorTest::auth();
        $zona = factory(Zona::class)->create();
        $response = $this->graphfl('delete_zona', [
            "ID" => $zona->id
        ], $headers);
        $this->assertEquals(
            $zona->id,
            $response->json("data.DeleteZona.id")
        );
    }
}
