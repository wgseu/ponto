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
use App\Models\Operacao;

class OperacaoTest extends TestCase
{
    public function testCreateOperacao()
    {
        $headers = PrestadorTest::authOwner();
        $seed_operacao =  factory(Operacao::class)->create();
        $response = $this->graphfl('create_operacao', [
            'input' => [
                'codigo' => 1,
                'descricao' => 'Teste',
            ]
        ], $headers);

        $found_operacao = Operacao::findOrFail($response->json('data.CreateOperacao.id'));
        $this->assertEquals(1, $found_operacao->codigo);
        $this->assertEquals('Teste', $found_operacao->descricao);
    }

    public function testUpdateOperacao()
    {
        $headers = PrestadorTest::authOwner();
        $operacao = factory(Operacao::class)->create();
        $this->graphfl('update_operacao', [
            'id' => $operacao->id,
            'input' => [
                'codigo' => 1,
                'descricao' => 'Atualizou',
            ]
        ], $headers);
        $operacao->refresh();
        $this->assertEquals(1, $operacao->codigo);
        $this->assertEquals('Atualizou', $operacao->descricao);
    }

    public function testDeleteOperacao()
    {
        $headers = PrestadorTest::authOwner();
        $operacao_to_delete = factory(Operacao::class)->create();
        $this->graphfl('delete_operacao', ['id' => $operacao_to_delete->id], $headers);
        $operacao = Operacao::find($operacao_to_delete->id);
        $this->assertNull($operacao);
    }

    public function testFindOperacao()
    {
        $headers = PrestadorTest::authOwner();
        $operacao = factory(Operacao::class)->create();
        $response = $this->graphfl('query_operacao', [ 'id' => $operacao->id ], $headers);
        $this->assertEquals($operacao->id, $response->json('data.operacoes.data.0.id'));
    }
}
