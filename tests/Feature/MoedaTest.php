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
        $response = $this->graphfl('create_moeda', [
            'input' => [
                'nome' => 'Cruzado',
                'simbolo' => 'X',
                'codigo' => '1',
                'divisao' => 100,
                'formato' => 'X :value',
            ]
        ], $headers);

        $found_moeda = Moeda::findOrFail($response->json('data.CreateMoeda.id'));
        $this->assertEquals('Cruzado', $found_moeda->nome);
        $this->assertEquals('X', $found_moeda->simbolo);
        $this->assertEquals('1', $found_moeda->codigo);
        $this->assertEquals(100, $found_moeda->divisao);
        $this->assertEquals('X :value', $found_moeda->formato);
    }

    public function testFindMoeda()
    {
        $headers = PrestadorTest::auth();
        $moeda = factory(Moeda::class)->create();
        $response = $this->graphfl('find_moeda_id', [
            'id' => $moeda->id,
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
        $this->graphfl('update_moeda', [
            'id' => $moeda->id,
            'input' => [
                'nome' => 'Chefinha',
                'simbolo' => 'X',
                'conversao' => 1.0,
            ]
        ], $headers);
        $moeda->refresh();
        $this->assertEquals('Chefinha', $moeda->nome);
        $this->assertEquals('X', $moeda->simbolo);
        $this->assertEquals('1.0', $moeda->conversao);
    }
    
    public function testDeleteMoeda()
    {
        $headers = PrestadorTest::auth();
        $moeda_to_delete = factory(Moeda::class)->create();
        $this->graphfl('delete_moeda', ['id' => $moeda_to_delete->id], $headers);
        $moeda = Moeda::find($moeda_to_delete->id);
        $this->assertNull($moeda);
    }

    public function testValidateMoedaAtivaConversaoNula()
    {
        $moeda = factory(Moeda::class)->create();
        $moeda->ativa = true;
        $this->expectException('\Exception');
        $moeda->save();
    }

    public function testValidateMoedaFormatoInvalido()
    {
        $moeda = factory(Moeda::class)->create();
        $moeda->formato = 'valor';
        $this->expectException('\Exception');
        $moeda->save();
    }

    public function testValidateMoedaDivisaoInvalida()
    {
        $moeda = factory(Moeda::class)->create();
        $moeda->divisao = 5;
        $this->expectException('\Exception');
        $moeda->save();
    }
}
