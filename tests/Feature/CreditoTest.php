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
        $response = $this->graphfl('create_credito', [
            'input' => [
                'cliente_id' => $cliente_id->id,
                'valor' => 10,
                'detalhes' => 'Devolução de mercadorias',
            ]
        ], $headers);
        $found_credito = Credito::findOrFail($response->json('data.CreateCredito.id'));
        $this->assertEquals($cliente_id->id, $found_credito->cliente_id);
        $this->assertEquals(10, $found_credito->valor);
        $this->assertEquals('Devolução de mercadorias', $found_credito->detalhes);
    }

    public function testFindCredito()
    {
        $headers = PrestadorTest::auth();
        $credito = factory(Credito::class)->create();
        $response = $this->graphfl('find_credito_id', [
            'id' => $credito->id,
        ], $headers);

        $this->assertEquals(
            $credito->detalhes,
            $response->json('data.creditos.data.0.detalhes')
        );
    }

    public function testUpdateCredito()
    {
        $headers = PrestadorTest::auth();
        $credito = factory(Credito::class)->create();
        $this->graphfl('update_credito', [
            'id' => $credito->id,
            'input' => [
                'cancelado' => true,
              ]
        ], $headers);
        $credito->refresh();
        $this->assertEquals(
            true,
            $credito->cancelado
        );
    }
    
    public function testDeleteCredito()
    {
        $headers = PrestadorTest::auth();
        $credito_to_delete = factory(Credito::class)->create();
        $this->graphfl('delete_credito', ['id' => $credito_to_delete->id], $headers);
        $credito = Credito::find($credito_to_delete->id);
        $this->assertNull($credito);
    }

    public function testValidateCreditoAbatimentoMaiorSaldo()
    {
        $cliente = factory(Cliente::class)->create();
        $oldCredito = factory(Credito::class)->create();
        $oldCredito->valor = 100;
        $oldCredito->cliente_id = $cliente->id;
        $oldCredito->save();

        $credito = factory(Credito::class)->create();
        $credito->cliente_id = $cliente->id;
        $credito->save();

        $credito->cliente_id = $cliente->id;
        $credito->valor = -101;
        $this->expectException('\Exception');
        $credito->save();
    }

    public function testValidateCreditoCreateCancelado()
    {
        $credito = factory(Credito::class)->create();
        $credito->delete();
        $credito->cancelado = true;
        $this->expectException('\Exception');
        $credito->save();
    }

    public function testValidateCreditoCancelamentoMaiorSaldoCredito()
    {
        $cliente = factory(Cliente::class)->create();
        $oldCredito = factory(Credito::class)->create();
        $oldCredito->valor = 100;
        $oldCredito->cliente_id = $cliente->id;
        $oldCredito->save();

        $credito = factory(Credito::class)->create();
        $credito->cliente_id = $cliente->id;
        $credito->save();

        $credito->cliente_id = $cliente->id;
        $credito->valor = -40;
        $credito->save();

        $oldCredito->cancelado = true;
        $this->expectException('\Exception');
        $oldCredito->save();
    }

    public function testValidateCreditoTranferirAbatimento()
    {
        $cliente = factory(Cliente::class)->create();
        $oldCredito = factory(Credito::class)->create();
        $oldCredito->valor = 100;
        $oldCredito->cliente_id = $cliente->id;
        $oldCredito->save();

        $credito = factory(Credito::class)->create();
        $credito->cliente_id = $cliente->id;
        $credito->save();

        $credito->cliente_id = $cliente->id;
        $credito->valor = -40;
        $credito->save();

        $newCliente = factory(Cliente::class)->create();
        $credito->cliente_id = $newCliente->id;
        $this->expectException('\Exception');
        $credito->save();
    }

    public function testValidateCreditoTranferefirSaldoNegativo()
    {
        $cliente = factory(Cliente::class)->create();
        $oldCredito = factory(Credito::class)->create();
        $oldCredito->valor = 100;
        $oldCredito->cliente_id = $cliente->id;
        $oldCredito->save();

        $credito = factory(Credito::class)->create();
        $credito->cliente_id = $cliente->id;
        $credito->save();

        $credito->cliente_id = $cliente->id;
        $credito->valor = -40;
        $credito->save();

        $newCliente = factory(Cliente::class)->create();
        $oldCredito->cliente_id = $newCliente->id;
        $this->expectException('\Exception');
        $oldCredito->save();
    }

    public function testValidateCreditoUpdateCancelado()
    {
        $credito = factory(Credito::class)->create();
        $credito->cancelado = true;
        $credito->save();
        $credito->valor = 15;
        $this->expectException('\Exception');
        $credito->save();
    }
}
