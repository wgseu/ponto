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
use App\Models\Pacote;
use App\Models\Produto;
use App\Models\Propriedade;
use App\Exceptions\ValidationException;

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

    public function testValidateGrupoTipoProdutoInvalido()
    {
        $produto = factory(Produto::class)->create(['tipo' => Produto::TIPO_PRODUTO]);
        $this->expectException(ValidationException::class);
        factory(Grupo::class)->create(['produto_id' => $produto->id]);
    }

    public function testValidateGrupoQuantidadeMinimaMaiorMaxima()
    {
        $this->expectException(ValidationException::class);
        factory(Grupo::class)->create(['quantidade_minima' => 10, 'quantidade_maxima' => 1]);
    }

    public function testValidateGrupoQuantidadeMinimaCannotNegativa()
    {
        $this->expectException(ValidationException::class);
        factory(Grupo::class)->create(['quantidade_minima' => -1]);
    }

    public function testValidateGrupoQuantidadeMaximaCannotNegative()
    {
        $this->expectException(ValidationException::class);
        factory(Grupo::class)->create(['quantidade_maxima' => -10]);
    }

    public function testValidateGrupoProdutoCannotUpdate()
    {
        $produto = factory(Produto::class)->create();
        $grupo = factory(Grupo::class)->create();
        $grupo->produto_id = $produto->id;
        $this->expectException(ValidationException::class);
        $grupo->save();
    }

    public function testReduzirQuantidadeMaxima()
    {
        $grupo = factory(Grupo::class)->create();
        factory(Pacote::class)->create([
            'quantidade_maxima' => 10,
            'grupo_id' => $grupo->id,
            'pacote_id' => $grupo->produto_id
        ]);
        $grupo->quantidade_maxima = 6;
        $this->expectException(ValidationException::class);
        $grupo->save();
    }

    public function testAumentarOrdemGrupoAssociado()
    {
        $grupo = factory(Grupo::class)->create();
        $propriedade = factory(Propriedade::class)->create(['grupo_id' => $grupo->id]);
        $associacao = factory(Pacote::class)->create([
            'produto_id' => null,
            'propriedade_id' => $propriedade->id,
            'grupo_id' => $grupo->id,
            'pacote_id' => $grupo->produto_id
        ]);
        $pacote = factory(Pacote::class)->create(['associacao_id' => $associacao->id]);
        $grupo->ordem = 3;
        $this->expectException(ValidationException::class);
        $grupo->save();
    }
}
