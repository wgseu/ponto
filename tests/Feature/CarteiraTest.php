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

use Tests\TestCase;
use App\Models\Banco;
use App\Models\Carteira;
use App\Exceptions\ValidationException;

class CarteiraTest extends TestCase
{
    public function testCreateCarteira()
    {
        $headers = PrestadorTest::auth();
        $banco = factory(Banco::class)->create();
        $response = $this->graphfl('create_carteira', [
            'input' => [
                'tipo' => Carteira::TIPO_BANCARIA,
                'descricao' => 'Teste',
                'banco_id' => $banco->id,
                'agencia' => '0000-1',
                'conta' => '22333-5',
            ]
        ], $headers);

        $found_carteira = Carteira::findOrFail($response->json('data.CreateCarteira.id'));
        $this->assertEquals(Carteira::TIPO_BANCARIA, $found_carteira->tipo);
        $this->assertEquals('Teste', $found_carteira->descricao);
    }

    public function testUpdateCarteira()
    {
        $headers = PrestadorTest::auth();
        $carteira = factory(Carteira::class)->create();
        $this->graphfl('update_carteira', [
            'id' => $carteira->id,
            'input' => [
                'tipo' => Carteira::TIPO_BANCARIA,
                'descricao' => 'Atualizou',
            ]
        ], $headers);
        $carteira->refresh();
        $this->assertEquals(Carteira::TIPO_BANCARIA, $carteira->tipo);
        $this->assertEquals('Atualizou', $carteira->descricao);
    }

    public function testDeleteCarteira()
    {
        $headers = PrestadorTest::auth();
        $carteira_to_delete = factory(Carteira::class)->create();
        $this->graphfl('delete_carteira', ['id' => $carteira_to_delete->id], $headers);
        $carteira = Carteira::find($carteira_to_delete->id);
        $this->assertNull($carteira);
    }

    public function testFindCarteira()
    {
        $headers = PrestadorTest::auth();
        $carteira = factory(Carteira::class)->create();
        $response = $this->graphfl('query_carteira', [ 'id' => $carteira->id ], $headers);
        $this->assertEquals($carteira->id, $response->json('data.carteiras.data.0.id'));
    }

    public function testCarteiraPaiNaoEncontrada()
    {
        $this->expectException(ValidationException::class);
        factory(Carteira::class)->create([
            'carteira_id' => 99,
        ]);
    }

    public function testCarteiraPaiAgrupada()
    {
        $seed_carteira = factory(Carteira::class)->create();
        $old_carteira = factory(Carteira::class)->create([
            'carteira_id' => $seed_carteira->id,
        ]);
        $this->expectException(ValidationException::class);
        factory(Carteira::class)->create([
            'carteira_id' => $old_carteira->id,
        ]);
    }

    public function testCarteiraIguais()
    {
        $carteira = factory(Carteira::class)->create();
        $carteira->carteira_id = $carteira->id;
        $this->expectException(ValidationException::class);
        $carteira->save();
    }

    public function testTipoBancarioBancoNull()
    {
        $this->expectException(ValidationException::class);
        factory(Carteira::class)->create([
            'banco_id' => null,
        ]);
    }

    public function testFinanceiroComBancoId()
    {
        $this->expectException(ValidationException::class);
        factory(Carteira::class)->create([
            'tipo' => Carteira::TIPO_FINANCEIRA,
        ]);
    }

    public function testTipoBancarioAgenciaNull()
    {
        $this->expectException(ValidationException::class);
        factory(Carteira::class)->create([
            'agencia' => null,
        ]);
    }

    public function testTipoBancarioContaNull()
    {
        $this->expectException(ValidationException::class);
        factory(Carteira::class)->create([
            'conta' => null,
        ]);
    }
}
