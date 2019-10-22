<?php

/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
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
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
 */

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tributacao;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TributacaoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateTributacao()
    {
        $headers = PrestadorTest::auth();
        $seed_tributacao =  factory(Tributacao::class)->create();
        $response = $this->graphfl('create_tributacao', [
            'input' => [
                'ncm' => 'Teste',
                'origem_id' => $seed_tributacao->origem_id,
                'operacao_id' => $seed_tributacao->operacao_id,
                'imposto_id' => $seed_tributacao->imposto_id,
            ]
        ], $headers);

        $found_tributacao = Tributacao::findOrFail($response->json('data.CreateTributacao.id'));
        $this->assertEquals('Teste', $found_tributacao->ncm);
        $this->assertEquals($seed_tributacao->origem_id, $found_tributacao->origem_id);
        $this->assertEquals($seed_tributacao->operacao_id, $found_tributacao->operacao_id);
        $this->assertEquals($seed_tributacao->imposto_id, $found_tributacao->imposto_id);
    }

    public function testUpdateTributacao()
    {
        $headers = PrestadorTest::auth();
        $tributacao = factory(Tributacao::class)->create();
        $this->graphfl('update_tributacao', [
            'id' => $tributacao->id,
            'input' => [
                'ncm' => 'Atualizou',
            ]
        ], $headers);
        $tributacao->refresh();
        $this->assertEquals('Atualizou', $tributacao->ncm);
    }

    public function testDeleteTributacao()
    {
        $headers = PrestadorTest::auth();
        $tributacao_to_delete = factory(Tributacao::class)->create();
        $tributacao_to_delete = $this->graphfl('delete_tributacao', ['id' => $tributacao_to_delete->id], $headers);
        $tributacao = Tributacao::find($tributacao_to_delete->id);
        $this->assertNull($tributacao);
    }

    public function testFindTributacao()
    {
        $headers = PrestadorTest::auth();
        $tributacao = factory(Tributacao::class)->create();
        $response = $this->graphfl('query_tributacao', [ 'id' => $tributacao->id ], $headers);
        $this->assertEquals($tributacao->id, $response->json('data.tributacoes.data.0.id'));
    }
}
