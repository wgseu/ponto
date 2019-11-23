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
use App\Models\Unidade;

class UnidadeTest extends TestCase
{
    public function testCreateUnidade()
    {
        $headers = PrestadorTest::authOwner();
        $seed_unidade =  factory(Unidade::class)->create();
        $response = $this->graphfl('create_unidade', [
            'input' => [
                'nome' => 'Teste',
                'sigla' => 'Teste',
            ]
        ], $headers);

        $found_unidade = Unidade::findOrFail($response->json('data.CreateUnidade.id'));
        $this->assertEquals('Teste', $found_unidade->nome);
        $this->assertEquals('Teste', $found_unidade->sigla);
    }

    public function testUpdateUnidade()
    {
        $headers = PrestadorTest::authOwner();
        $unidade = factory(Unidade::class)->create();
        $this->graphfl('update_unidade', [
            'id' => $unidade->id,
            'input' => [
                'nome' => 'Atualizou',
                'sigla' => 'Atualizou',
            ]
        ], $headers);
        $unidade->refresh();
        $this->assertEquals('Atualizou', $unidade->nome);
        $this->assertEquals('Atualizou', $unidade->sigla);
    }

    public function testDeleteUnidade()
    {
        $headers = PrestadorTest::authOwner();
        $unidade_to_delete = factory(Unidade::class)->create();
        $this->graphfl('delete_unidade', ['id' => $unidade_to_delete->id], $headers);
        $unidade = Unidade::find($unidade_to_delete->id);
        $this->assertNull($unidade);
    }

    public function testFindUnidade()
    {
        $headers = PrestadorTest::authOwner();
        $unidade = factory(Unidade::class)->create();
        $response = $this->graphfl('query_unidade', [ 'id' => $unidade->id ], $headers);
        $this->assertEquals($unidade->id, $response->json('data.unidades.data.0.id'));
    }
}
