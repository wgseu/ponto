<?php

namespace Tests\Feature;

use App\Models\Pais;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaisTest extends TestCase
{
    use RefreshDatabase;

    public function build()
    {
        $pais = factory(Pais::class)->create();
        return $pais;
    }

    public function testCreatePais()
    {
        $moeda = $this->graphfl('create_moeda', [
            "input" => [
                "nome" => "moedateste",
                "simbolo" => "M$",
                "codigo" => "MOT",
                "divisao" => 100,
                "formato" => "m$",
                "ativa" => false
            ]
        ]);
        $pais = $this->graphfl('create_pais', [
            "input" => [
                "nome" => "Korea",
                "sigla" => "KOR",
                "codigo" => "KO",
                "idioma" => "Koreano",
                "moeda_id" => $moeda->json('data.CreateMoeda.id')
            ]
        ]);

        $response = $this->graphfl('query_last_pais_code');

        $this->assertEquals(
            $pais->json('data.CreatePais.id'),
            $response->json("data.paises.data.0.id")
        );
    }

    public function testUpdatePais()
    {
        $this->build();
        $pais = $this->graphfl('update_pais', [
            "input" => [
                "nome" => "Russa",
                "sigla" => "RUS",
                "codigo" => "RS",
                "idioma" => "russa",
                "moeda_id" => 1
            ]
        ]);

        $response = $this->graphfl('query_last_pais_code');

        $this->assertEquals(
            $pais->json('data.UpdatePais.nome'),
            $response->json("data.paises.data.0.nome")
        );
    }

    public function testDeletePais()
    {
        $this->build();
        $this->graphfl('delete_pais');
        $response = $this->graphfl('query_last_pais_code');

        $this->assertNull($response->json('data.paises.data.0'));
    }

    public function testQueryPais()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->build();
        }

        $response = $this->graphfl('query_pais');
        $this->assertIsArray($response->json('data.paises.data'));
        $this->assertCount(10, $response->json('data.paises.data'));
    }

}
