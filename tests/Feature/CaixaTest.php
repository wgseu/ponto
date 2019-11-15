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
use App\Models\Caixa;
use App\Models\Carteira;
use App\Models\Movimentacao;

class CaixaTest extends TestCase
{
    public function testCreateCaixa()
    {
        $headers = PrestadorTest::auth();
        $carteira =  factory(Carteira::class)->create(['tipo' => Carteira::TIPO_LOCAL]);
        $response = $this->graphfl('create_caixa', [
            'input' => [
                'carteira_id' => $carteira->id,
                'descricao' => 'Teste',
            ]
        ], $headers);

        $found_caixa = Caixa::findOrFail($response->json('data.CreateCaixa.id'));
        $this->assertEquals($carteira->id, $found_caixa->carteira_id);
        $this->assertEquals('Teste', $found_caixa->descricao);
    }

    public function testUpdateCaixa()
    {
        $headers = PrestadorTest::auth();
        $caixa = factory(Caixa::class)->create();
        $this->graphfl('update_caixa', [
            'id' => $caixa->id,
            'input' => [
                'descricao' => 'Atualizou',
            ]
        ], $headers);
        $caixa->refresh();
        $this->assertEquals('Atualizou', $caixa->descricao);
    }

    public function testDeleteCaixa()
    {
        $headers = PrestadorTest::auth();
        $caixa_to_delete = factory(Caixa::class)->create();
        $this->graphfl('delete_caixa', ['id' => $caixa_to_delete->id], $headers);
        $caixa = Caixa::find($caixa_to_delete->id);
        $this->assertNull($caixa);
    }

    public function testFindCaixa()
    {
        $headers = PrestadorTest::auth();
        $caixa = factory(Caixa::class)->create();
        $response = $this->graphfl('query_caixa', [ 'id' => $caixa->id ], $headers);
        $this->assertEquals($caixa->id, $response->json('data.caixas.data.0.id'));
    }

    public function testValidadeCaixaCarteiraTipoInvalida()
    {
        $carteira = factory(Carteira::class)->create(['tipo' => Carteira::TIPO_CREDITO]);
        $this->expectException(ValidationException::class);
        factory(Caixa::class)->create(['carteira_id' => $carteira->id]);
    }

    public function testValidadeCaixaDesativarCaixaEmUso()
    {
        $caixa = factory(Caixa::class)->create();
        factory(Movimentacao::class)->create(['caixa_id' => $caixa->id]);
        $caixa->ativa = false;
        $this->expectException(ValidationException::class);
        $caixa->save();
    }
}
