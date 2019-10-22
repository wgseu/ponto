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
use App\Models\Composicao;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComposicaoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateComposicao()
    {
        $headers = PrestadorTest::auth();
        $seed_composicao =  factory(Composicao::class)->create();
        $response = $this->graphfl('create_composicao', [
            'input' => [
                'composicao_id' => $seed_composicao->composicao_id,
                'produto_id' => $seed_composicao->produto_id,
                'quantidade' => 1.0,
            ]
        ], $headers);

        $found_composicao = Composicao::findOrFail($response->json('data.CreateComposicao.id'));
        $this->assertEquals($seed_composicao->composicao_id, $found_composicao->composicao_id);
        $this->assertEquals($seed_composicao->produto_id, $found_composicao->produto_id);
        $this->assertEquals(1.0, $found_composicao->quantidade);
    }

    public function testUpdateComposicao()
    {
        $headers = PrestadorTest::auth();
        $composicao = factory(Composicao::class)->create();
        $this->graphfl('update_composicao', [
            'id' => $composicao->id,
            'input' => [
                'quantidade' => 1.0,
            ]
        ], $headers);
        $composicao->refresh();
        $this->assertEquals(1.0, $composicao->quantidade);
    }

    public function testDeleteComposicao()
    {
        $headers = PrestadorTest::auth();
        $composicao_to_delete = factory(Composicao::class)->create();
        $composicao_to_delete = $this->graphfl('delete_composicao', ['id' => $composicao_to_delete->id], $headers);
        $composicao = Composicao::find($composicao_to_delete->id);
        $this->assertNull($composicao);
    }

    public function testFindComposicao()
    {
        $headers = PrestadorTest::auth();
        $composicao = factory(Composicao::class)->create();
        $response = $this->graphfl('query_composicao', [ 'id' => $composicao->id ], $headers);
        $this->assertEquals($composicao->id, $response->json('data.composicoes.data.0.id'));
    }
}
