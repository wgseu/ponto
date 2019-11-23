<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Credito;
use App\Models\Cliente;
use App\Exceptions\ValidationException;

class CreditoTest extends TestCase
{
    public function testFindCredito()
    {
        $headers = PrestadorTest::authOwner();
        $credito = factory(Credito::class)->create();
        $response = $this->graphfl('query_credito', [
            'id' => $credito->id,
        ], $headers);

        $this->assertEquals(
            $credito->detalhes,
            $response->json('data.creditos.data.0.detalhes')
        );
    }

    public function testValidateCreditoAbatimentoMaiorSaldo()
    {
        $cliente = factory(Cliente::class)->create();
        $oldCredito = factory(Credito::class)->create(['valor' => 100, 'cliente_id' => $cliente->id]);
        $this->expectException(ValidationException::class);
        $credito = factory(Credito::class)->create(['cliente_id' => $cliente->id, 'valor' => -101]);
    }

    public function testValidateCreditoCreateCancelado()
    {
        $this->expectException(ValidationException::class);
        factory(Credito::class)->create(['cancelado' => true]);
    }

    public function testValidateCreditoCancelamentoMaiorSaldoCredito()
    {
        $cliente = factory(Cliente::class)->create();
        $oldCredito = factory(Credito::class)->create(['valor' => 100, 'cliente_id' => $cliente->id]);
        $credito = factory(Credito::class)->create(['cliente_id' => $cliente->id, 'valor' => -40]);
        $oldCredito->cancelado = true;
        $this->expectException(ValidationException::class);
        $oldCredito->save();
    }

    public function testValidateCreditoTranferirAbatimento()
    {
        $cliente = factory(Cliente::class)->create();
        $oldCredito = factory(Credito::class)->create(['valor' => 100, 'cliente_id' => $cliente->id]);
        $credito = factory(Credito::class)->create(['cliente_id' => $cliente->id, 'valor' => -40]);

        $newCliente = factory(Cliente::class)->create();
        $credito->cliente_id = $newCliente->id;
        $this->expectException(ValidationException::class);
        $credito->save();
    }

    public function testValidateCreditoTranferefirSaldoNegativo()
    {
        $cliente = factory(Cliente::class)->create();
        $oldCredito = factory(Credito::class)->create(['valor' => 100, 'cliente_id' => $cliente->id]);
        $credito = factory(Credito::class)->create(['cliente_id' => $cliente->id, 'valor' => -40]);

        $newCliente = factory(Cliente::class)->create();
        $oldCredito->cliente_id = $newCliente->id;
        $this->expectException(ValidationException::class);
        $oldCredito->save();
    }

    public function testValidateCreditoUpdateCancelado()
    {
        $credito = factory(Credito::class)->create();
        $credito->cancelado = true;
        $credito->save();
        $credito->valor = 15;
        $this->expectException(ValidationException::class);
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
