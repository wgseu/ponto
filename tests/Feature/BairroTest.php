<?php

namespace Tests\Feature;

use App\Models\Bairro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Feature\CidadeTest;

class BairroTest extends TestCase
{
    use RefreshDatabase;

    public function create()
    {
        $cidade = new CidadeTest();
        $cid = $cidade->create();
        $bairro = factory(Bairro::class)->create();
        return $bairro;
    }

    public function biuld()
    {
        $cidade = new CidadeTest();
        $cid = $cidade->create();
        return $cid;
    }

    public function testCreateBairro()
    {
        $headers = PrestadorTest::auth();
        $cidade = self::biuld();
        $bairro = $this->graphfl('create_bairro', [
            "BairroInput" => [
                "cidade_id" => 1,
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
        $bairro = $this->create();
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
        $cidade = self::biuld();
        $bairro = factory(Bairro::class)->create();
        $response = $this->graphfl('update_bairro', [
            "ID" => $cidade->id,
            "BairroUpdateInput" => [
                "cidade_id" => 1,
                "nome"=> "Jardim 51 Mundial das Palmeiras",
                "valor_entrega" => 10,
                "disponivel" => true,
                "mapeado" => false, 
              ]
        ], $headers);
        $bairro->refresh();
        $this->assertEquals(
            $bairro->nome,
            $response->json('data.UpdateBairro.nome')
        );
        $this->assertEquals(
            $bairro->cidade_id,
            $response->json('data.UpdateBairro.cidade_id')
        );
        $this->assertEquals(
            $bairro->valor_entrega,
            $response->json('data.UpdateBairro.valor_entrega')
        );
        $this->assertEquals(
            $bairro->disponivel,
            $response->json('data.UpdateBairro.disponivel')
        );
        $this->assertEquals(
            0,
            $response->json('data.UpdateBairro.mapeado')
        );
    }
    
    public function testDeleteBairro()
    {
        $headers = PrestadorTest::auth();
        $bairro = $this->create();
        $response = $this->graphfl('delete_bairro', [
            "ID" => $bairro->id
        ], $headers);
        $this->assertEquals(
            $bairro->id,
            $response->json("data.DeleteBairro.id")
        );
    }

    
}
