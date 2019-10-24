<?php

namespace Tests\Feature;

use App\Models\Bairro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class BairroTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateBairro()
    {
        $headers = PrestadorTest::auth();
        $seed_bairro =  factory(Bairro::class)->create();
        $response = $this->graphfl('create_bairro', [
            'input' => [
                'cidade_id' => $seed_bairro->cidade_id,
                'nome' => 'Teste',
                'valor_entrega' => 1.50,
            ]
        ], $headers);

        $found_bairro = Bairro::findOrFail($response->json('data.CreateBairro.id'));
        $this->assertEquals($seed_bairro->cidade_id, $found_bairro->cidade_id);
        $this->assertEquals('Teste', $found_bairro->nome);
        $this->assertEquals(1.50, $found_bairro->valor_entrega);
    }


    public function testFindBairro()
    {
        $headers = PrestadorTest::auth();
        $bairro = factory(Bairro::class)->create();
        $response = $this->graphfl('find_bairro_id', [
            'id' => $bairro->id,
        ], $headers);

        $this->assertEquals(
            $bairro->id,
            $response->json('data.bairros.data.0.id')
        );
        $this->assertEquals(
            $bairro->nome,
            $response->json('data.bairros.data.0.nome')
        );
    }

    public function testUpdateBairro()
    {
        $headers = PrestadorTest::auth();
        $bairro = factory(Bairro::class)->create();
        $this->graphfl('update_bairro', [
            'id' => $bairro->id,
            'input' => [
                'nome' => 'Jardim 51 Mundial das Palmeiras',
                'valor_entrega' => 10.2,
            ]
        ], $headers);
        $bairro->refresh();
        $this->assertEquals(
            'Jardim 51 Mundial das Palmeiras',
            $bairro->nome
        );
        $this->assertEquals(
            10.2,
            $bairro->valor_entrega
        );
    }
    
    public function testDeleteBairro()
    {
        $headers = PrestadorTest::auth();
        $bairro_to_delete = factory(Bairro::class)->create();
        $this->graphfl('delete_bairro', ['id' => $bairro_to_delete->id], $headers);
        $bairro = Bairro::find($bairro_to_delete->id);
        $this->assertNull($bairro);
    }

    public function testValidateBairroPrazoEntregaMaximoMaiorMinimo()
    {
        $bairro = factory(Bairro::class)->create();
        $bairro->entrega_minima = 4;
        $bairro->entrega_maxima = 2;
        $this->expectException(ValidationException::class);
        $bairro->save();
    }

    public function testValidateBairroValorNegativo()
    {
        $bairro = factory(Bairro::class)->create();
        $bairro->valor_entrega = -5;
        $this->expectException(ValidationException::class);
        $bairro->save();
    }
}
