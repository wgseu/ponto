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
use App\Models\Comanda;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComandaTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateComanda()
    {
        $headers = PrestadorTest::auth();
        $seed_comanda =  factory(Comanda::class)->create();
        $response = $this->graphfl('create_comanda', [
            'input' => [
                'numero' => 1,
                'nome' => 'Teste',
            ]
        ], $headers);

        $found_comanda = Comanda::findOrFail($response->json('data.CreateComanda.id'));
        $this->assertEquals(1, $found_comanda->numero);
        $this->assertEquals('Teste', $found_comanda->nome);
    }

    public function testUpdateComanda()
    {
        $headers = PrestadorTest::auth();
        $comanda = factory(Comanda::class)->create();
        $this->graphfl('update_comanda', [
            'id' => $comanda->id,
            'input' => [
                'numero' => 1,
                'nome' => 'Atualizou',
            ]
        ], $headers);
        $comanda->refresh();
        $this->assertEquals(1, $comanda->numero);
        $this->assertEquals('Atualizou', $comanda->nome);
    }

    public function testDeleteComanda()
    {
        $headers = PrestadorTest::auth();
        $comanda_to_delete = factory(Comanda::class)->create();
        $comanda_to_delete = $this->graphfl('delete_comanda', ['id' => $comanda_to_delete->id], $headers);
        $comanda = Comanda::find($comanda_to_delete->id);
        $this->assertNull($comanda);
    }

    public function testQueryComanda()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Comanda::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_comanda', [], $headers);
        $this->assertEquals(10, $response->json('data.comandas.total'));
    }
}
