<?php

namespace Tests\Feature;

use App\Exceptions\ValidationException;
use App\Models\Empresa;
use App\Models\Moeda;
use App\Models\Pais;
use Tests\TestCase;

class MoedaTest extends TestCase
{
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
                'conversao' => 1,
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
        $response = $this->graphfl('query_moeda', [
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
        $this->expectException(ValidationException::class);
        factory(Moeda::class)->create(['conversao' => null, 'ativa' => true]);
    }

    public function testValidateMoedaFormatoInvalido()
    {
        $this->expectException(ValidationException::class);
        factory(Moeda::class)->create(['formato' => 'valor']);
    }

    public function testValidateMoedaFormatoSimboloInvalido()
    {
        $this->expectException(ValidationException::class);
        factory(Moeda::class)->create(['formato' => ':value']);
    }

    public function testValidateMoedaDivisaoInvalida()
    {
        $this->expectException(ValidationException::class);
        factory(Moeda::class)->create(['divisao' => 5]);
    }

    public function testValidateMoedaPaisAtivo()
    {
        $moeda = factory(Moeda::class)->create();
        $pais = factory(Pais::class)->create(['moeda_id' => $moeda->id]);
        Empresa::find('1')->update(['pais_id' => $pais->id]);

        $moeda->conversao = 8;
        $this->expectException(ValidationException::class);
        $moeda->save();
    }

    public function testValidateMoedaConversaoNegativa()
    {
        $this->expectException(ValidationException::class);
        factory(Moeda::class)->create(['conversao' => -5]);
    }
}
