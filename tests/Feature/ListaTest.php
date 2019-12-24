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

use App\Exceptions\ValidationException;
use Tests\TestCase;
use App\Models\Lista;
use App\Models\Prestador;
use App\Models\Viagem;

class ListaTest extends TestCase
{
    public function testCreateLista()
    {
        $headers = PrestadorTest::authOwner();
        $seed_lista =  factory(Lista::class)->create();
        $response = $this->graphfl('create_lista', [
            'input' => [
                'descricao' => 'Teste',
                'encarregado_id' => $seed_lista->encarregado_id,
                'data_viagem' => '2016-12-25T12:15:00Z',
            ]
        ], $headers);

        $found_lista = Lista::findOrFail($response->json('data.CreateLista.id'));
        $this->assertEquals('Teste', $found_lista->descricao);
        $this->assertEquals($seed_lista->encarregado_id, $found_lista->encarregado_id);
        $this->assertEquals('2016-12-25 12:15:00', $found_lista->data_viagem);
    }

    public function testUpdateLista()
    {
        $headers = PrestadorTest::authOwner();
        $lista = factory(Lista::class)->create([
            'estado' => Lista::ESTADO_ANALISE,
        ]);
        $this->graphfl('update_lista', [
            'id' => $lista->id,
            'input' => [
                'descricao' => 'Atualizou',
            ]
        ], $headers);
        $lista->refresh();
        $this->assertEquals('Atualizou', $lista->descricao);
    }

    public function testDeleteLista()
    {
        $headers = PrestadorTest::authOwner();
        $lista_to_delete = factory(Lista::class)->create();
        $this->graphfl('delete_lista', ['id' => $lista_to_delete->id], $headers);
        $lista = Lista::find($lista_to_delete->id);
        $this->assertNull($lista);
    }

    public function testFindLista()
    {
        $headers = PrestadorTest::authOwner();
        $viagem = factory(Viagem::class)->create();
        $lista = factory(Lista::class)->create(['viagem_id' => $viagem->id]);
        $response = $this->graphfl('query_lista', [ 'id' => $lista->id ], $headers);

        $encarregadoExpect = Prestador::find($response->json('data.listas.data.0.encarregado_id'));
        $encarregadoResult = $lista->encarregado;
        $this->assertEquals($encarregadoExpect, $encarregadoResult);

        $viagemExpect = Viagem::find($response->json('data.listas.data.0.viagem_id'));
        $viagemResult = $lista->viagem;
        $this->assertEquals($viagemExpect, $viagemResult);

        $this->assertEquals($lista->id, $response->json('data.listas.data.0.id'));
        $this->assertEquals($lista->descricao, $response->json('data.listas.data.0.descricao'));
    }

    public function testValidateListaCompraFinalizada()
    {
        $lista = factory(Lista::class)->create(['estado' => Lista::ESTADO_COMPRADA]);
        $lista->descricao = 'Mercado';
        $this->expectException(ValidationException::class);
        $lista->save();
    }

    public function testValidateListaDataViagemInvalida()
    {
        $this->expectException(ValidationException::class);
        factory(Lista::class)->create([
            'data_viagem' => '2016-12-25 12:15:00',
            'data_cadastro' => '2019-12-25 12:15:00'
        ]);
    }
}
