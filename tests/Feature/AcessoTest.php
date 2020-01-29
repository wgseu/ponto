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
use App\Models\Acesso;
use App\Models\Funcao;
use App\Models\Permissao;

class AcessoTest extends TestCase
{
    public function testCreateAcesso()
    {
        $headers = PrestadorTest::authOwner();
        $funcao =  factory(Funcao::class)->create();
        $permissao =  Permissao::first();
        $response = $this->graphfl('create_acesso', [
            'input' => [
                'funcao_id' => $funcao->id,
                'permissao_id' => $permissao->id,
            ]
        ], $headers);

        $found_acesso = Acesso::findOrFail($response->json('data.CreateAcesso.id'));
        $this->assertEquals($funcao->id, $found_acesso->funcao_id);
        $this->assertEquals($permissao->id, $found_acesso->permissao_id);
    }

    public function testDeleteAcesso()
    {
        $headers = PrestadorTest::authOwner();
        $acesso_to_delete = factory(Acesso::class)->create();
        $this->graphfl('delete_acesso', ['id' => $acesso_to_delete->id], $headers);
        $acesso = Acesso::find($acesso_to_delete->id);
        $this->assertNull($acesso);
    }

    public function testFindAcesso()
    {
        $headers = PrestadorTest::authOwner();
        $acesso = factory(Acesso::class)->create();
        $response = $this->graphfl('query_acesso', [ 'id' => $acesso->id ], $headers);
        $this->assertEquals($acesso->id, $response->json('data.acessos.data.0.id'));

        $funcaoExpected = Funcao::find($response->json('data.acessos.data.0.funcao_id'));
        $funcaoResult = $acesso->funcao;
        $this->assertEquals($funcaoExpected, $funcaoResult);

        $permissaoExpected = Permissao::find($response->json('data.acessos.data.0.permissao_id'));
        $permissaoResult = $acesso->permissao;
        $this->assertEquals($permissaoExpected, $permissaoResult);
    }
}
