<?php

/**
 * Copyright 2014 da GrandChef - GrandChef Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Restaurantes e Afins.
 * O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
 * DISPOSIÇÕES GERAIS
 * O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
 * ou outros avisos ou restrições de propriedade do GrandChef.
 *
 * O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
 * ou descompilação do GrandChef.
 *
 * PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA
 *
 * GrandChef é a especialidade do desenvolvedor e seus
 * licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
 * de leis de propriedade.
 *
 * O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
 * direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
 *
 * @author Equipe GrandChef <desenvolvimento@grandchef.com.br>
 */

namespace Tests\Feature;

use App\Exceptions\ValidationException;
use Tests\TestCase;
use App\Models\Comanda;
use App\Models\Pedido;

class ComandaTest extends TestCase
{
    public function testCreateComanda()
    {
        $headers = PrestadorTest::authOwner();
        $seed_comanda =  factory(Comanda::class)->create();
        $response = $this->graphfl('create_comanda', [
            'input' => [
                'numero' => 1,
                'nome' => 'Teste',
            ]
        ], $headers);

        $found_comanda = Comanda::findOrFail($response->json('data.CreateComanda.id'));
        $this->assertEquals(1, $found_comanda->numero);
        $this->assertEquals('Teste', $found_comanda->nome);
    }

    public function testFindComanda()
    {
        $headers = PrestadorTest::authOwner();
        $comanda = factory(Comanda::class)->create();
        $response = $this->graphfl('query_comanda', [
            'id' => $comanda->id,
        ], $headers);

        $this->assertEquals(
            $comanda->id,
            $response->json('data.comandas.data.0.id')
        );
        $this->assertEquals(
            $comanda->nome,
            $response->json('data.comandas.data.0.nome')
        );
        $this->assertEquals(
            $comanda->numero,
            $response->json('data.comandas.data.0.numero')
        );
    }

    public function testUpdateComanda()
    {
        $headers = PrestadorTest::authOwner();
        $comanda = factory(Comanda::class)->create();
        $this->graphfl('update_comanda', [
            'id' => $comanda->id,
            'input' => [
                'numero' => 1,
                'nome' => 'Atualizou',
            ]
        ], $headers);
        $comanda->refresh();
        $this->assertEquals(1, $comanda->numero);
        $this->assertEquals('Atualizou', $comanda->nome);
    }

    public function testDeleteComanda()
    {
        $headers = PrestadorTest::authOwner();
        $comanda_to_delete = factory(Comanda::class)->create();
        $this->graphfl('delete_comanda', ['id' => $comanda_to_delete->id], $headers);
        $comanda = Comanda::find($comanda_to_delete->id);
        $this->assertNull($comanda);
    }

    public function testValidateComandaCancelarComandaPedido()
    {
        $comanda = factory(Comanda::class)->create();
        factory(Pedido::class)->create([
            'comanda_id' => $comanda->id,
            'tipo' => Pedido::TIPO_COMANDA
        ]);
        $comanda->ativa = false;
        $this->expectException(ValidationException::class);
        $comanda->save();
    }

    public function testValidateComandaCreateCancelado()
    {
        $this->expectException(ValidationException::class);
        factory(Comanda::class)->create(['ativa' => false]);
    }
}
