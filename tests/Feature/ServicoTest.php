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
use App\Models\Servico;

class ServicoTest extends TestCase
{
    public function testCreateServico()
    {
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('create_servico', [
            'input' => [
                'nome' => 'Teste',
                'descricao' => 'Teste',
                'tipo' => Servico::TIPO_TAXA,
                'valor' => 100.00
            ]
        ], $headers);

        $found_servico = Servico::findOrFail($response->json('data.CreateServico.id'));
        $this->assertEquals('Teste', $found_servico->nome);
        $this->assertEquals('Teste', $found_servico->descricao);
        $this->assertEquals(Servico::TIPO_TAXA, $found_servico->tipo);
    }

    public function testUpdateServico()
    {
        $headers = PrestadorTest::auth();
        $servico = factory(Servico::class)->create();
        $this->graphfl('update_servico', [
            'id' => $servico->id,
            'input' => [
                'nome' => 'Atualizou',
                'descricao' => 'Atualizou',
                'tipo' => Servico::TIPO_TAXA,
            ]
        ], $headers);
        $servico->refresh();
        $this->assertEquals('Atualizou', $servico->nome);
        $this->assertEquals('Atualizou', $servico->descricao);
        $this->assertEquals(Servico::TIPO_TAXA, $servico->tipo);
    }

    public function testDeleteServico()
    {
        $headers = PrestadorTest::auth();
        $servico_to_delete = factory(Servico::class)->create();
        $this->graphfl('delete_servico', ['id' => $servico_to_delete->id], $headers);
        $servico = Servico::find($servico_to_delete->id);
        $this->assertNull($servico);
    }

    public function testFindServico()
    {
        $headers = PrestadorTest::auth();
        $servico = factory(Servico::class)->create();
        $response = $this->graphfl('query_servico', [ 'id' => $servico->id ], $headers);
        $this->assertEquals($servico->id, $response->json('data.servicos.data.0.id'));
    }
}
