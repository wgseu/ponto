<?php

namespace Tests\Feature;

use App\Models\Bairro;
use App\Models\Cidade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Feature\CidadeTest;

class BairroTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateBairro()
    {
        $headers = PrestadorTest::auth();
        $cidade = factory(Cidade::class)->create();
        $bairro = $this->graphfl('create_bairro', [
            "BairroInput" => [
                "cidade_id" => $cidade->id,
                "nome"=> "Jardim Imperial",
                "valor_entrega" => 14.9,
                "disponivel" => true,
                "mapeado" => false,
                "entrega_minima" => 0,
                "entrega_maxima" => 2,  
            ]
        ], $headers);
        $this->assertEquals(
            1,
            $bairro->json("data.CreateBairro.id")
        );
    }

    public function testFindBairro()
    {
        $headers = PrestadorTest::auth();
        $bairro = factory(Bairro::class)->create();
        $response = $this->graphfl('find_bairro_id',[
            "ID" => $bairro->id,
        ], $headers);

        $this->assertEquals(
            1,
            $response->json('data.bairros.data.0.id')
        );
    }

    public function testUpdateBairro()
    {
        $headers = PrestadorTest::auth();
        $bairro = factory(Bairro::class)->create();
        $response = $this->graphfl('update_bairro', [
            "ID" => $bairro->id,
            "BairroUpdateInput" => [
                "nome"=> "Jardim 51 Mundial das Palmeiras",
                "valor_entrega" => 10.2,
              ]
        ], $headers);
        $bairro->refresh();
        $this->assertEquals(
            $bairro->nome,
            $response->json('data.UpdateBairro.nome')
        );
        $this->assertEquals(
            $bairro->valor_entrega,
            $response->json('data.UpdateBairro.valor_entrega')
        );
    }
    
    public function testDeleteBairro()
    {
        $headers = PrestadorTest::auth();
        $bairro = factory(Bairro::class)->create();
        $response = $this->graphfl('delete_bairro', [
            "ID" => $bairro->id
        ], $headers);
        $this->assertEquals(
            $bairro->id,
            $response->json("data.DeleteBairro.id")
        );
    }
    
}
