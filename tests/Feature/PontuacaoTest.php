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
use App\Models\Pontuacao;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PontuacaoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePontuacao()
    {
        $headers = PrestadorTest::auth();
        $seed_pontuacao =  factory(Pontuacao::class)->create();
        $response = $this->graphfl('create_pontuacao', [
            'input' => [
                'promocao_id' => $seed_pontuacao->promocao_id,
                'quantidade' => 1,
            ]
        ], $headers);

        $found_pontuacao = Pontuacao::findOrFail($response->json('data.CreatePontuacao.id'));
        $this->assertEquals($seed_pontuacao->promocao_id, $found_pontuacao->promocao_id);
        $this->assertEquals(1, $found_pontuacao->quantidade);
    }

    public function testUpdatePontuacao()
    {
        $headers = PrestadorTest::auth();
        $pontuacao = factory(Pontuacao::class)->create();
        $this->graphfl('update_pontuacao', [
            'id' => $pontuacao->id,
            'input' => [
                'quantidade' => 1,
            ]
        ], $headers);
        $pontuacao->refresh();
        $this->assertEquals(1, $pontuacao->quantidade);
    }

    public function testDeletePontuacao()
    {
        $headers = PrestadorTest::auth();
        $pontuacao_to_delete = factory(Pontuacao::class)->create();
        $pontuacao_to_delete = $this->graphfl('delete_pontuacao', ['id' => $pontuacao_to_delete->id], $headers);
        $pontuacao = Pontuacao::find($pontuacao_to_delete->id);
        $this->assertNull($pontuacao);
    }

    public function testFindPontuacao()
    {
        $headers = PrestadorTest::auth();
        $pontuacao = factory(Pontuacao::class)->create();
        $response = $this->graphfl('query_pontuacao', [ 'id' => $pontuacao->id ], $headers);
        $this->assertEquals($pontuacao->id, $response->json('data.pontuacoes.data.0.id'));
    }
}
