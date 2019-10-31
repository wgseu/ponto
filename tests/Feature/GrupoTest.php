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
use App\Models\Grupo;
use App\Models\Produto;
use Illuminate\Validation\ValidationException;

class GrupoTest extends TestCase
{
    public function testCreateGrupo()
    {
        $headers = PrestadorTest::auth();
        $seed_grupo =  factory(Grupo::class)->create();
        $response = $this->graphfl('create_grupo', [
            'input' => [
                'produto_id' => $seed_grupo->produto_id,
                'nome' => 'Teste',
                'descricao' => 'Teste',
            ]
        ], $headers);

        $found_grupo = Grupo::findOrFail($response->json('data.CreateGrupo.id'));
        $this->assertEquals($seed_grupo->produto_id, $found_grupo->produto_id);
        $this->assertEquals('Teste', $found_grupo->nome);
        $this->assertEquals('Teste', $found_grupo->descricao);
    }

    public function testUpdateGrupo()
    {
        $headers = PrestadorTest::auth();
        $grupo = factory(Grupo::class)->create();
        $this->graphfl('update_grupo', [
            'id' => $grupo->id,
            'input' => [
                'nome' => 'Atualizou',
                'descricao' => 'Atualizou',
            ]
        ], $headers);
        $grupo->refresh();
        $this->assertEquals('Atualizou', $grupo->nome);
        $this->assertEquals('Atualizou', $grupo->descricao);
    }

    public function testDeleteGrupo()
    {
        $headers = PrestadorTest::auth();
        $grupo_to_delete = factory(Grupo::class)->create();
        $this->graphfl('delete_grupo', ['id' => $grupo_to_delete->id], $headers);
        $grupo_to_delete->refresh();
        $this->assertTrue($grupo_to_delete->trashed());
        $this->assertNotNull($grupo_to_delete->data_arquivado);
    }

    public function testFindGrupo()
    {
        $headers = PrestadorTest::auth();
        $grupo = factory(Grupo::class)->create();
        $response = $this->graphfl('query_grupo', [ 'id' => $grupo->id ], $headers);
        $this->assertEquals($grupo->id, $response->json('data.grupos.data.0.id'));
    }

    public function testValidateTipoProdutoInvalido()
    {
        $produto = factory(Produto::class)->create();
        $produto->tipo = Produto::TIPO_PRODUTO;
        $produto->save();
        $grupo = new Grupo();
        $grupo->nome = 'Pizza G';
        $grupo->descricao = 'Pizzas com tamanhos G';
        $grupo->produto_id = $produto->id;
        $this->expectException(ValidationException::class);
        $grupo->save();
    }
}
