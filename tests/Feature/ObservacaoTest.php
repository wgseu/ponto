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
use App\Models\Observacao;

class ObservacaoTest extends TestCase
{
    public function testCreateObservacao()
    {
        $headers = PrestadorTest::auth();
        $seed_observacao =  factory(Observacao::class)->create();
        $response = $this->graphfl('create_observacao', [
            'input' => [
                'produto_id' => $seed_observacao->produto_id,
                'descricao' => 'Teste',
            ]
        ], $headers);

        $found_observacao = Observacao::findOrFail($response->json('data.CreateObservacao.id'));
        $this->assertEquals($seed_observacao->produto_id, $found_observacao->produto_id);
        $this->assertEquals('Teste', $found_observacao->descricao);
    }

    public function testUpdateObservacao()
    {
        $headers = PrestadorTest::auth();
        $observacao = factory(Observacao::class)->create();
        $this->graphfl('update_observacao', [
            'id' => $observacao->id,
            'input' => [
                'descricao' => 'Atualizou',
            ]
        ], $headers);
        $observacao->refresh();
        $this->assertEquals('Atualizou', $observacao->descricao);
    }

    public function testDeleteObservacao()
    {
        $headers = PrestadorTest::auth();
        $observacao_to_delete = factory(Observacao::class)->create();
        $this->graphfl('delete_observacao', ['id' => $observacao_to_delete->id], $headers);
        $observacao = Observacao::find($observacao_to_delete->id);
        $this->assertNull($observacao);
    }

    public function testFindObservacao()
    {
        $headers = PrestadorTest::auth();
        $observacao = factory(Observacao::class)->create();
        $response = $this->graphfl('query_observacao', [ 'id' => $observacao->id ], $headers);
        $this->assertEquals($observacao->id, $response->json('data.observacoes.data.0.id'));
    }
}
