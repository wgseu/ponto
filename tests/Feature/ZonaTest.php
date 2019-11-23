<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Zona;
use App\Models\Bairro;
use App\Exceptions\ValidationException;

class ZonaTest extends TestCase
{
    public function testCreateZona()
    {
        $headers = PrestadorTest::authOwner();
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
        $headers = PrestadorTest::authOwner();
        $zona = factory(Zona::class)->create();
        $response = $this->graphfl('query_zona', ['id' => $zona->id], $headers);

        $bairroExpect = Bairro::find($response->json('data.zonas.data.0.bairro_id'));
        $bairroResult = $zona->bairro;
        $this->assertEquals($bairroExpect, $bairroResult);

        $this->assertEquals($zona->nome, $response->json('data.zonas.data.0.nome'));
    }

    public function testUpdateZona()
    {
        $headers = PrestadorTest::authOwner();
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
        $headers = PrestadorTest::authOwner();
        $zona_to_delete = factory(Zona::class)->create();
        $this->graphfl('delete_zona', ['id' => $zona_to_delete->id], $headers);
        $zona = Zona::find($zona_to_delete->id);
        $this->assertNull($zona);
    }

    public function testValidateZonaPrazoEntregaInvalido()
    {
        $this->expectException(ValidationException::class);
        factory(Zona::class)->create(['entrega_minima' => 4, 'entrega_maxima' => 2]);
    }

    public function testValidateZonaAdicionalNegativo()
    {
        $this->expectException(ValidationException::class);
        factory(Zona::class)->create(['adicional_entrega' => -5]);
    }
}
