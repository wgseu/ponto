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
use App\Models\Cartao;
use App\Models\Carteira;
use App\Models\Forma;

class CartaoTest extends TestCase
{
    public function testCreateCartao()
    {
        $headers = PrestadorTest::authOwner();
        $seed_cartao =  factory(Cartao::class)->create();
        $response = $this->graphfl('create_cartao', [
            'input' => [
                'forma_id' => $seed_cartao->forma_id,
                'bandeira' => 'Teste',
            ]
        ], $headers);

        $found_cartao = Cartao::findOrFail($response->json('data.CreateCartao.id'));
        $this->assertEquals($seed_cartao->forma_id, $found_cartao->forma_id);
        $this->assertEquals('Teste', $found_cartao->bandeira);
    }

    public function testFindCartao()
    {
        $headers = PrestadorTest::authOwner();
        $carteira = factory(Carteira::class)->create();
        $cartao = factory(Cartao::class)->create(['carteira_id' => $carteira->id]);
        $response = $this->graphfl('query_cartao', [
            'id' => $cartao->id,
        ], $headers);

        $this->assertEquals($cartao->id, $response->json('data.cartoes.data.0.id'));
        $this->assertEquals($cartao->bandeira, $response->json('data.cartoes.data.0.bandeira'));

        $forma = Forma::find($response->json('data.cartoes.data.0.forma_id'));
        $this->assertEquals($cartao->forma, $forma);
        $carteiras = Carteira::find($response->json('data.cartoes.data.0.carteira_id'));
        $this->assertEquals($cartao->carteira, $carteiras);
    }

    public function testUpdateCartao()
    {
        $headers = PrestadorTest::authOwner();
        $cartao = factory(Cartao::class)->create();
        $this->graphfl('update_cartao', [
            'id' => $cartao->id,
            'input' => [
                'bandeira' => 'Visa',
            ]
        ], $headers);
        $cartao->refresh();
        $this->assertEquals('Visa', $cartao->bandeira);
    }

    public function testDeleteCartao()
    {
        $headers = PrestadorTest::authOwner();
        $cartao_to_delete = factory(Cartao::class)->create();
        $this->graphfl('delete_cartao', ['id' => $cartao_to_delete->id], $headers);
        $cartao = Cartao::find($cartao_to_delete->id);
        $this->assertNull($cartao);
    }

    public function testValidateCartaoTaxaNegativa()
    {
        $this->expectException(ValidationException::class);
        factory(Cartao::class)->create(['taxa' => -4]);
    }

    public function testValidateCartaoDiasRepasseNegativo()
    {
        $this->expectException(ValidationException::class);
        factory(Cartao::class)->create(['dias_repasse' => -30]);
    }

    public function testValidateCartaoTaxaAntecipacaoNegativa()
    {
        $this->expectException(ValidationException::class);
        factory(Cartao::class)->create(['taxa_antecipacao' => -4]);
    }

    public function testValidateCartaoCreateDesativado()
    {
        $this->expectException(ValidationException::class);
        factory(Cartao::class)->create(['ativo' => false]);
    }
}
