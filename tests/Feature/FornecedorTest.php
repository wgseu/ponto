<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Fornecedor;
use App\Models\Cliente;

class FornecedorTest extends TestCase
{
    use RefreshDatabase;
    
    public function create()
    {
        factory(Cliente::class)->create();
        $fornecedor = factory(Fornecedor::class)->create();
        return $fornecedor;
    }

    public function biuld()
    {
        $cliente = factory(Cliente::class)->create();
        return $cliente;
    }

    public function testCreateFornecedor()
    {
        $headers = PrestadorTest::auth();
        $cliente = self::biuld();
        $fornecedor = $this->graphfl('create_fornecedor', [
            "FornecedorInput" => [
              "empresa_id" => $cliente->id,
            ]
        ], $headers);

        $this->assertEquals(
            1,
            $fornecedor->json("data.CreateFornecedor.id")
        );
    }

    public function testFindFornecedor()
    {
        $headers = PrestadorTest::auth();
        $fornecedor = $this->create();
        $response = $this->graphfl('find_fornecedor_id',[
            "ID" => $fornecedor->id,
        ], $headers);

        $this->assertEquals(
            $fornecedor->id,
            $response->json('data.fornecedores.data.0.id')
        );
    }

    public function testUpdateFornecedor()
    {
        $headers = PrestadorTest::auth();
        $cliente = self::biuld();
        $fornecedor = factory(Fornecedor::class)->create();
        $response = $this->graphfl('update_fornecedor', [
            "ID" => $fornecedor->id,
            "FornecedorUpdateInput" => [
                "empresa_id" => $cliente->id,
                "prazo_pagamento" => 20,
            ]
        ], $headers);
        $fornecedor->refresh();
        $this->assertEquals(
            $fornecedor->prazo_pagamento,
            $response->json('data.UpdateFornecedor.prazo_pagamento')
        );
        $this->assertEquals(
            $fornecedor->empresa_id,
            $response->json('data.UpdateFornecedor.empresa_id')
        );
    }
    
    public function testDeleteFornecedor()
    {
        $headers = PrestadorTest::auth();
        $fornecedor = $this->create();
        $response = $this->graphfl('delete_fornecedor', [
            "ID" => $fornecedor->id
        ], $headers);

        $this->assertEquals(
            $fornecedor->id,
            $response->json("data.DeleteFornecedor.id")
        );
    }
        
}
