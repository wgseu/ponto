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
use App\Models\Permissao;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissaoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePermissao()
    {
        $headers = PrestadorTest::auth();
        $seed_permissao =  factory(Permissao::class)->create();
        $response = $this->graphfl('create_permissao', [
            'input' => [
                'funcionalidade_id' => $seed_permissao->funcionalidade_id,
                'nome' => 'Teste',
                'descricao' => 'Teste',
            ]
        ], $headers);

        $found_permissao = Permissao::findOrFail($response->json('data.CreatePermissao.id'));
        $this->assertEquals($seed_permissao->funcionalidade_id, $found_permissao->funcionalidade_id);
        $this->assertEquals('Teste', $found_permissao->nome);
        $this->assertEquals('Teste', $found_permissao->descricao);
    }

    public function testUpdatePermissao()
    {
        $headers = PrestadorTest::auth();
        $permissao = factory(Permissao::class)->create();
        $this->graphfl('update_permissao', [
            'id' => $permissao->id,
            'input' => [
                'nome' => 'Atualizou',
                'descricao' => 'Atualizou',
            ]
        ], $headers);
        $permissao->refresh();
        $this->assertEquals('Atualizou', $permissao->nome);
        $this->assertEquals('Atualizou', $permissao->descricao);
    }

    public function testDeletePermissao()
    {
        $headers = PrestadorTest::auth();
        $permissao_to_delete = factory(Permissao::class)->create();
        $permissao_to_delete = $this->graphfl('delete_permissao', ['id' => $permissao_to_delete->id], $headers);
        $permissao = Permissao::find($permissao_to_delete->id);
        $this->assertNull($permissao);
    }

    public function testFindPermissao()
    {
        $headers = PrestadorTest::auth();
        $permissao = factory(Permissao::class)->create();
        $response = $this->graphfl('query_permissao', [ 'id' => $permissao->id ], $headers);
        $this->assertEquals($permissao->id, $response->json('data.permissoes.data.0.id'));
    }
}
