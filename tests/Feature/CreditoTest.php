<?php

namespace Tests\Feature;

use App\Exceptions\SafeValidationException;
use App\Models\Credito;
use App\Models\Cliente;
use Tests\TestCase;

class CreditoTest extends TestCase
{
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
        $response = $this->graphfl('query_credito', [
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
        $oldCredito = factory(Credito::class)->create(['valor' => 100, 'cliente_id' => $cliente->id]);
        $this->expectException(SafeValidationException::class);
        $credito = factory(Credito::class)->create(['cliente_id' => $cliente->id, 'valor' => -101]);
    }

    public function testValidateCreditoCreateCancelado()
    {
        $this->expectException(SafeValidationException::class);
        factory(Credito::class)->create(['cancelado' => true]);
    }

    public function testValidateCreditoCancelamentoMaiorSaldoCredito()
    {
        $cliente = factory(Cliente::class)->create();
        $oldCredito = factory(Credito::class)->create(['valor' => 100, 'cliente_id' => $cliente->id]);
        $credito = factory(Credito::class)->create(['cliente_id' => $cliente->id, 'valor' => -40]);
        $oldCredito->cancelado = true;
        $this->expectException(SafeValidationException::class);
        $oldCredito->save();
    }

    public function testValidateCreditoTranferirAbatimento()
    {
        $cliente = factory(Cliente::class)->create();
        $oldCredito = factory(Credito::class)->create(['valor' => 100, 'cliente_id' => $cliente->id]);
        $credito = factory(Credito::class)->create(['cliente_id' => $cliente->id, 'valor' => -40]);

        $newCliente = factory(Cliente::class)->create();
        $credito->cliente_id = $newCliente->id;
        $this->expectException(SafeValidationException::class);
        $credito->save();
    }

    public function testValidateCreditoTranferefirSaldoNegativo()
    {
        $cliente = factory(Cliente::class)->create();
        $oldCredito = factory(Credito::class)->create(['valor' => 100, 'cliente_id' => $cliente->id]);
        $credito = factory(Credito::class)->create(['cliente_id' => $cliente->id, 'valor' => -40]);

        $newCliente = factory(Cliente::class)->create();
        $oldCredito->cliente_id = $newCliente->id;
        $this->expectException(SafeValidationException::class);
        $oldCredito->save();
    }

    public function testValidateCreditoUpdateCancelado()
    {
        $credito = factory(Credito::class)->create();
        $credito->cancelado = true;
        $credito->save();
        $credito->valor = 15;
        $this->expectException(SafeValidationException::class);
        $credito->save();
    }

    public function testCreditoBelongToCliente()
    {
        $cliente = factory(Cliente::class)->create();
        $credito = factory(Credito::class)->create(['cliente_id' => $cliente->id]);
        $expected = Cliente::find($cliente->id);
        $result = $credito->cliente;
        $this->assertEquals($expected, $result);
    }
}
