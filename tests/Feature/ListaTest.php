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
use App\Models\Lista;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListaTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateLista()
    {
        $headers = PrestadorTest::auth();
        $seed_lista =  factory(Lista::class)->create();
        $response = $this->graphfl('create_lista', [
            'input' => [
                'descricao' => 'Teste',
                'encarregado_id' => $seed_lista->encarregado_id,
                'data_viagem' => '2016-12-25 12:15:00',
            ]
        ], $headers);

        $found_lista = Lista::findOrFail($response->json('data.CreateLista.id'));
        $this->assertEquals('Teste', $found_lista->descricao);
        $this->assertEquals($seed_lista->encarregado_id, $found_lista->encarregado_id);
        $this->assertEquals('2016-12-25 12:15:00', $found_lista->data_viagem);
    }

    public function testUpdateLista()
    {
        $headers = PrestadorTest::auth();
        $lista = factory(Lista::class)->create();
        $this->graphfl('update_lista', [
            'id' => $lista->id,
            'input' => [
                'descricao' => 'Atualizou',
                'data_viagem' => '2016-12-28 12:30:00',
            ]
        ], $headers);
        $lista->refresh();
        $this->assertEquals('Atualizou', $lista->descricao);
        $this->assertEquals('2016-12-28 12:30:00', $lista->data_viagem);
    }

    public function testDeleteLista()
    {
        $headers = PrestadorTest::auth();
        $lista_to_delete = factory(Lista::class)->create();
        $lista_to_delete = $this->graphfl('delete_lista', ['id' => $lista_to_delete->id], $headers);
        $lista = Lista::find($lista_to_delete->id);
        $this->assertNull($lista);
    }

    public function testFindLista()
    {
        $headers = PrestadorTest::auth();
        $lista = factory(Lista::class)->create();
        $response = $this->graphfl('query_lista', [ 'id' => $lista->id ], $headers);
        $this->assertEquals($lista->id, $response->json('data.listas_de_compras.data.0.id'));
    }
}
