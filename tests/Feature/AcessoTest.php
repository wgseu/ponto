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
use App\Models\Acesso;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AcessoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateAcesso()
    {
        $headers = PrestadorTest::auth();
        $seed_acesso =  factory(Acesso::class)->create();
        $response = $this->graphfl('create_acesso', [
            'input' => [
                'funcao_id' => $seed_acesso->funcao_id,
                'permissao_id' => $seed_acesso->permissao_id,
            ]
        ], $headers);

        $found_acesso = Acesso::findOrFail($response->json('data.CreateAcesso.id'));
        $this->assertEquals($seed_acesso->funcao_id, $found_acesso->funcao_id);
        $this->assertEquals($seed_acesso->permissao_id, $found_acesso->permissao_id);
    }

    public function testUpdateAcesso()
    {
        $headers = PrestadorTest::auth();
        $acesso = factory(Acesso::class)->create();
        $this->graphfl('update_acesso', [
            'id' => $acesso->id,
            'input' => [
            ]
        ], $headers);
        $acesso->refresh();
    }

    public function testDeleteAcesso()
    {
        $headers = PrestadorTest::auth();
        $acesso_to_delete = factory(Acesso::class)->create();
        $acesso_to_delete = $this->graphfl('delete_acesso', ['id' => $acesso_to_delete->id], $headers);
        $acesso = Acesso::find($acesso_to_delete->id);
        $this->assertNull($acesso);
    }

    public function testFindAcesso()
    {
        $headers = PrestadorTest::auth();
        $acesso = factory(Acesso::class)->create();
        $response = $this->graphfl('query_acesso', [ 'id' => $acesso->id ], $headers);
        $this->assertEquals($acesso->id, $response->json('data.acessos.data.0.id'));
    }
}
