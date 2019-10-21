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
use App\Models\Funcionalidade;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FuncionalidadeTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateFuncionalidade()
    {
        $headers = PrestadorTest::auth();
        $seed_funcionalidade =  factory(Funcionalidade::class)->create();
        $response = $this->graphfl('create_funcionalidade', [
            'input' => [
                'nome' => 'Teste',
                'descricao' => 'Teste',
            ]
        ], $headers);

        $found_funcionalidade = Funcionalidade::findOrFail($response->json('data.CreateFuncionalidade.id'));
        $this->assertEquals('Teste', $found_funcionalidade->nome);
        $this->assertEquals('Teste', $found_funcionalidade->descricao);
    }

    public function testUpdateFuncionalidade()
    {
        $headers = PrestadorTest::auth();
        $funcionalidade = factory(Funcionalidade::class)->create();
        $this->graphfl('update_funcionalidade', [
            'id' => $funcionalidade->id,
            'input' => [
                'nome' => 'Atualizou',
                'descricao' => 'Atualizou',
            ]
        ], $headers);
        $funcionalidade->refresh();
        $this->assertEquals('Atualizou', $funcionalidade->nome);
        $this->assertEquals('Atualizou', $funcionalidade->descricao);
    }

    public function testDeleteFuncionalidade()
    {
        $headers = PrestadorTest::auth();
        $funcionalidade_to_delete = factory(Funcionalidade::class)->create();
        $funcionalidade_to_delete = $this->graphfl('delete_funcionalidade', ['id' => $funcionalidade_to_delete->id], $headers);
        $funcionalidade = Funcionalidade::find($funcionalidade_to_delete->id);
        $this->assertNull($funcionalidade);
    }

    public function testQueryFuncionalidade()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Funcionalidade::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_funcionalidade', [], $headers);
        $this->assertEquals(10, $response->json('data.funcionalidades.total'));
    }
}