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
use App\Models\Classificacao;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClassificacaoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateClassificacao()
    {
        $headers = PrestadorTest::auth();
        $seed_classificacao =  factory(Classificacao::class)->create();
        $response = $this->graphfl('create_classificacao', [
            'input' => [
                'descricao' => 'Teste',
            ]
        ], $headers);

        $found_classificacao = Classificacao::findOrFail($response->json('data.CreateClassificacao.id'));
        $this->assertEquals('Teste', $found_classificacao->descricao);
    }

    public function testUpdateClassificacao()
    {
        $headers = PrestadorTest::auth();
        $classificacao = factory(Classificacao::class)->create();
        $this->graphfl('update_classificacao', [
            'id' => $classificacao->id,
            'input' => [
                'descricao' => 'Atualizou',
            ]
        ], $headers);
        $classificacao->refresh();
        $this->assertEquals('Atualizou', $classificacao->descricao);
    }

    public function testDeleteClassificacao()
    {
        $headers = PrestadorTest::auth();
        $classificacao_to_delete = factory(Classificacao::class)->create();
        $classificacao_to_delete = $this->graphfl('delete_classificacao', ['id' => $classificacao_to_delete->id], $headers);
        $classificacao = Classificacao::find($classificacao_to_delete->id);
        $this->assertNull($classificacao);
    }

    public function testFindClassificacao()
    {
        $headers = PrestadorTest::auth();
        $classificacao = factory(Classificacao::class)->create();
        $response = $this->graphfl('query_classificacao', [ 'id' => $classificacao->id ], $headers);
        $this->assertEquals($classificacao->id, $response->json('data.classificacoes.data.0.id'));
    }
}
