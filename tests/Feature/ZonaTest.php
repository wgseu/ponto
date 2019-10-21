<?php

namespace Tests\Feature;

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
        $seed_zona =  factory(Zona::class)->create();
        $response = $this->graphfl('create_zona', [
            'input' => [
                'bairro_id' => $seed_zona->bairro_id,
                'nome' => 'Zona 3',
                'adicional_entrega' => 1,
            ]
        ], $headers);

        $found_zona = Zona::findOrFail($response->json('data.CreateZona.id'));
        $this->assertEquals($seed_zona->bairro_id, $found_zona->bairro_id);
        $this->assertEquals('Zona 3', $found_zona->nome);
        $this->assertEquals(1, $found_zona->adicional_entrega);
    }

    public function testFindZona()
    {
        $headers = PrestadorTest::auth();
        $zona = factory(Zona::class)->create();
        $response = $this->graphfl('find_zona_id', [
            'id' => $zona->id,
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
        $this->graphfl('update_zona', [
            'id' => $zona->id,
            'input' => [
                'nome' => 'Area 51',
                'adicional_entrega' => 20,
                'disponivel' => true,
              ]
        ], $headers);
        $zona->refresh();
        $this->assertEquals('Area 51', $zona->nome);
        $this->assertEquals(20, $zona->adicional_entrega);
        $this->assertEquals(true, $zona->disponivel);
    }
    
    public function testDeleteZona()
    {
        $headers = PrestadorTest::auth();
        $zona_to_delete = factory(Zona::class)->create();
        $this->graphfl('delete_zona', ['id' => $zona_to_delete->id], $headers);
        $zona = Zona::find($zona_to_delete->id);
        $this->assertNull($zona);
    }

    public function testValidateZonaPrazoEntregaMaximoMaiorMinimo()
    {
        $zona = factory(Zona::class)->create();
        $zona->entrega_minima = 4;
        $zona->entrega_maxima = 2;
        $this->expectException('\Exception');
        $zona->save();
    }

    public function testValidateZonaAdicionalNegativo()
    {
        $zona = factory(Zona::class)->create();
        $zona->adicional_entrega = -5;
        $this->expectException('\Exception');
        $zona->save();
    }
}
