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

use App\Models\Grupo;
use Tests\TestCase;
use App\Models\Pacote;
use App\Models\Produto;
use App\Models\Propriedade;
use App\Exceptions\ValidationException;

class PacoteTest extends TestCase
{
    public function testCreatePacote()
    {
        $headers = PrestadorTest::authOwner();
        $seed_pacote =  factory(Pacote::class)->create(['selecionado' => true]);
        $response = $this->graphfl('create_pacote', [
            'input' => [
                'produto_id' => $seed_pacote->produto_id,
                'pacote_id' => $seed_pacote->pacote_id,
                'grupo_id' => $seed_pacote->grupo_id,
                'acrescimo' => 1.50,
                'selecionado' => true,
            ]
        ], $headers);

        $found_pacote = Pacote::findOrFail($response->json('data.CreatePacote.id'));
        $this->assertEquals($seed_pacote->pacote_id, $found_pacote->pacote_id);
        $this->assertEquals($seed_pacote->grupo_id, $found_pacote->grupo_id);
        $this->assertEquals(1.50, $found_pacote->acrescimo);
    }

    public function testUpdatePacote()
    {
        $headers = PrestadorTest::authOwner();
        $pacote = factory(Pacote::class)->create();
        $this->graphfl('update_pacote', [
            'id' => $pacote->id,
            'input' => [
                'acrescimo' => 1.50,
            ]
        ], $headers);
        $pacote->refresh();
        $this->assertEquals(1.50, $pacote->acrescimo);
    }

    public function testDeletePacote()
    {
        $headers = PrestadorTest::authOwner();
        $pacote_to_delete = factory(Pacote::class)->create();
        $this->graphfl('delete_pacote', ['id' => $pacote_to_delete->id], $headers);
        $pacote_to_delete->refresh();
        $this->assertTrue($pacote_to_delete->trashed());
        $this->assertNotNull($pacote_to_delete->data_arquivado);
    }

    public function testFindPacote()
    {
        $headers = PrestadorTest::authOwner();
        $pacote = factory(Pacote::class)->create();
        $response = $this->graphfl('query_pacote', [ 'id' => $pacote->id ], $headers);
        $this->assertEquals($pacote->id, $response->json('data.pacotes.data.0.id'));
    }

    public function testValidatePacoteTipoInvalidoPacote()
    {
        $produto = factory(Produto::class)->create(['tipo' => Produto::TIPO_PRODUTO]);
        $this->expectException(ValidationException::class);
        factory(Pacote::class)->create(['pacote_id' => $produto->id]);
    }

    public function testValidatePacoteProdutoTipoInvalido()
    {
        $produto = factory(Produto::class)->create(['tipo' => Produto::TIPO_PACOTE]);
        $this->expectException(ValidationException::class);
        factory(Pacote::class)->create(['produto_id' => $produto->id]);
    }

    public function testValidatePacoteDifferentPacoteGrupo()
    {
        $produto = factory(Produto::class)->create(['tipo' => Produto::TIPO_PACOTE]);
        $grupo = factory(Grupo::class)->create();
        $this->expectException(ValidationException::class);
        factory(Pacote::class)->create(['pacote_id' => $produto->id, 'grupo_id' => $grupo->id]);
    }

    public function testValidateGrupoPacoteDifferentGrupoPropriedade()
    {
        $propriedade = factory(Propriedade::class)->create();
        $grupo = factory(Grupo::class)->create();
        $this->expectException(ValidationException::class);
        factory(Pacote::class)->create(['propriedade_id' => $propriedade->id, 'grupo_id' => $grupo->id]);
    }

    public function testValidateGrupoPacoteRerefencesProdutoAndPropriedade()
    {
        $propriedade = factory(Propriedade::class)->create();
        $produto = factory(Produto::class)->create(['tipo' => Produto::TIPO_PACOTE]);
        $this->expectException(ValidationException::class);
        factory(Pacote::class)->create(['propriedade_id' => $propriedade->id, 'produto_id' => $produto->id]);
    }


    public function testValidatePacoteNotAssociado()
    {
        $this->expectException(ValidationException::class);
        factory(Pacote::class)->create(['associacao_id' => null, 'produto_id' => null]);
    }

    public function testValidatePacoteMinimoCannotNegative()
    {
        $this->expectException(ValidationException::class);
        factory(Pacote::class)->create(['quantidade_minima' => -9]);
    }

    public function testValidatePacoteMaxinoCannotNegative()
    {
        $this->expectException(ValidationException::class);
        factory(Pacote::class)->create(['quantidade_maxima' => -9]);
    }

    public function testValidatePacoteMininoCannotGreaterMaximo()
    {
        $this->expectException(ValidationException::class);
        factory(Pacote::class)->create(['quantidade_maxima' => 3, 'quantidade_minima' => 5]);
    }

    public function testValidateQuantidadeMaximaPacoteCannotGreaterGrupo()
    {
        $grupo = factory(Grupo::class)->create(['quantidade_maxima' => 3, 'tipo' => Grupo::TIPO_INTEIRO]);
        $this->expectException(ValidationException::class);
        factory(Pacote::class)->create([
            'quantidade_maxima' => 5,
            'grupo_id' => $grupo->id,
            'pacote_id' => $grupo->produto_id
        ]);
    }

    public function testValidatePacoteCannotUpdatePacoteId()
    {
        $grupo = factory(Grupo::class)->create();
        $pacote = factory(Pacote::class)->create();
        $pacote->grupo_id = $grupo->id;
        $pacote->pacote_id = $grupo->produto_id;
        $this->expectException(ValidationException::class);
        $pacote->save();
    }


    public function testValidatePacoteCannotUpdateGrupoId()
    {
        $pacote = factory(Pacote::class)->create();
        $grupo = factory(Grupo::class)->create();
        $pacote->grupo_id = $grupo->id;
        $this->expectException(ValidationException::class);
        $pacote->save();
    }

    public function testValidatePacoteAssociateIgualsGrup()
    {
        $grupo = factory(Grupo::class)->create();
        $propriedade = factory(Propriedade::class)->create(['grupo_id' => $grupo->id]);
        $associacao = factory(Pacote::class)->create([
            'produto_id' => null,
            'propriedade_id' => $propriedade->id,
            'grupo_id' => $grupo->id,
            'pacote_id' => $grupo->produto_id
        ]);
        $this->expectException(ValidationException::class);
        factory(Pacote::class)->create([
            'produto_id' => null,
            'associacao_id' => $associacao->id,
            'propriedade_id' => $propriedade->id,
            'grupo_id' => $grupo->id,
            'pacote_id' => $grupo->produto_id
        ]);
    }

    public function testValidatePacoteUpdateAssociacaoSome()
    {
        $grupo = factory(Grupo::class)->create();
        $propriedade = factory(Propriedade::class)->create(['grupo_id' => $grupo->id]);
        $pacotePai = factory(Pacote::class)->create([
            'produto_id' => null,
            'propriedade_id' => $propriedade->id,
            'grupo_id' => $grupo->id,
            'pacote_id' => $grupo->produto_id
        ]);
        $pacote = factory(Pacote::class)->create(['associacao_id' => $pacotePai->id]);
        $pacote->associacao_id = $pacote->id;
        $this->expectException(ValidationException::class);
        $pacote->save();
    }
}
