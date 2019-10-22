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
use App\Models\Funcao;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FuncaoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateFuncao()
    {
        $headers = PrestadorTest::auth();
        $seed_funcao =  factory(Funcao::class)->create();
        $response = $this->graphfl('create_funcao', [
            'input' => [
                'descricao' => 'Teste',
                'remuneracao' => 1.50,
            ]
        ], $headers);

        $found_funcao = Funcao::findOrFail($response->json('data.CreateFuncao.id'));
        $this->assertEquals('Teste', $found_funcao->descricao);
        $this->assertEquals(1.50, $found_funcao->remuneracao);
    }

    public function testUpdateFuncao()
    {
        $headers = PrestadorTest::auth();
        $funcao = factory(Funcao::class)->create();
        $this->graphfl('update_funcao', [
            'id' => $funcao->id,
            'input' => [
                'descricao' => 'Atualizou',
                'remuneracao' => 1.50,
            ]
        ], $headers);
        $funcao->refresh();
        $this->assertEquals('Atualizou', $funcao->descricao);
        $this->assertEquals(1.50, $funcao->remuneracao);
    }

    public function testDeleteFuncao()
    {
        $headers = PrestadorTest::auth();
        $funcao_to_delete = factory(Funcao::class)->create();
        $funcao_to_delete = $this->graphfl('delete_funcao', ['id' => $funcao_to_delete->id], $headers);
        $funcao = Funcao::find($funcao_to_delete->id);
        $this->assertNull($funcao);
    }

    public function testFindFuncao()
    {
        $headers = PrestadorTest::auth();
        $funcao = factory(Funcao::class)->create();
        $response = $this->graphfl('query_funcao', [ 'id' => $funcao->id ], $headers);
        $this->assertEquals($funcao->id, $response->json('data.funcoes.data.0.id'));
    }
}
