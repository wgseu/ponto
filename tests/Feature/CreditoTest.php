<?php

namespace Tests\Feature;

use App\Models\Credito;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreditoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCredito()
    {
        $headers = PrestadorTest::auth();
        $cliente_id = factory(Cliente::class)->create();
        $credito = $this->graphfl('create_credito', [
            "CreditoInput" => [
                'cliente_id' => $cliente_id->id,
                'valor' => 10,
                'detalhes' => "Devolução de mercadorias",
            ]
        ], $headers);
        $this->assertEquals($cliente_id->id, $credito->json("data.CreateCredito.cliente_id"));
        $this->assertEquals(10, $credito->json("data.CreateCredito.valor"));
        $this->assertEquals("Devolução de mercadorias", $credito->json("data.CreateCredito.detalhes"));
    }

    public function testFindCredito()
    {
        $headers = PrestadorTest::auth();
        $credito = factory(Credito::class)->create();
        $response = $this->graphfl('find_credito_id',[
            "ID" => $credito->id,
        ], $headers);

        $this->assertEquals(
            1,
            $response->json('data.creditos.data.0.id')
        );
    }

    public function testUpdateCredito()
    {
        $headers = PrestadorTest::auth();
        $credito = factory(Credito::class)->create();
        $response = $this->graphfl('update_credito', [
            "ID" => $credito->id,
            "CreditoUpdateInput" => [
                "cancelado" => true,
              ]
        ], $headers);
        $credito->refresh();
        $this->assertEquals(
            $credito->cancelado,
            $response->json('data.UpdateCredito.cancelado')
        );
    }
    
    public function testDeleteCredito()
    {
        $headers = PrestadorTest::auth();
        $credito = factory(Credito::class)->create();
        $response = $this->graphfl('delete_credito', [
            "ID" => $credito->id
        ], $headers);
        $this->assertEquals(
            $credito->id,
            $response->json("data.DeleteCredito.id")
        );
    }
}
