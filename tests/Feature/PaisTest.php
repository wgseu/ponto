<?php

namespace Tests\Feature;

use App\Models\Pais;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaisTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePais()
    {
        $headers = PrestadorTest::auth();
        $pais_seed = factory(Pais::class)->create();
        $response = $this->graphfl('create_pais', [
            'input' => [
                'nome' => 'Korea',
                'sigla' => 'KOR',
                'codigo' => 'KO',
                'idioma' => 'Koreano',
                'moeda_id' => $pais_seed->moeda_id
            ]
        ], $headers);

        $pais = Pais::findOrFail($response->json('data.CreatePais.id'));
        $this->assertEquals('Korea', $pais->nome);
    }

    public function testUpdatePais()
    {
        $headers = PrestadorTest::auth();
        $old_pais = factory(Pais::class)->create();
        $this->graphfl('update_pais', [
            "input" => [
                "nome" => "Russia",
                "sigla" => "RUS",
                "codigo" => "RS",
                "idioma" => "ru-RU",
            ],
            "id" => $old_pais->id,
        ], $headers);
        $pais = $old_pais->fresh();
        $this->assertEquals('Russia', $pais->nome);
    }

    public function testDeletePais()
    {
        $headers = PrestadorTest::auth();
        $pais_to_delete = factory(Pais::class)->create();
        $this->graphfl('delete_pais', [
            'id' => $pais_to_delete->id,
        ], $headers);
        $not_found_pais = $pais_to_delete->fresh();
        $this->assertNull($not_found_pais);
    }

    public function testQueryPais()
    {
        for ($i = 0; $i < 10; $i++) {
            factory(Pais::class)->create();
        }

        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_pais', [], $headers);
        $this->assertEquals(10, $response->json('data.paises.total'));
        $this->assertIsArray($response->json('data.paises.data'));
        $this->assertCount(10, $response->json('data.paises.data'));
    }

}
