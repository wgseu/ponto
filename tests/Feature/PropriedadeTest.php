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
use App\Models\Propriedade;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PropriedadeTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePropriedade()
    {
        $headers = PrestadorTest::auth();
        $seed_propriedade =  factory(Propriedade::class)->create();
        $response = $this->graphfl('create_propriedade', [
            'input' => [
                'grupo_id' => $seed_propriedade->grupo_id,
                'nome' => 'Teste',
            ]
        ], $headers);

        $found_propriedade = Propriedade::findOrFail($response->json('data.CreatePropriedade.id'));
        $this->assertEquals($seed_propriedade->grupo_id, $found_propriedade->grupo_id);
        $this->assertEquals('Teste', $found_propriedade->nome);
    }

    public function testUpdatePropriedade()
    {
        $headers = PrestadorTest::auth();
        $propriedade = factory(Propriedade::class)->create();
        $this->graphfl('update_propriedade', [
            'id' => $propriedade->id,
            'input' => [
                'nome' => 'Atualizou',
            ]
        ], $headers);
        $propriedade->refresh();
        $this->assertEquals('Atualizou', $propriedade->nome);
    }

    public function testDeletePropriedade()
    {
        $headers = PrestadorTest::auth();
        $propriedade_to_delete = factory(Propriedade::class)->create();
        $propriedade_to_delete = $this->graphfl('delete_propriedade', ['id' => $propriedade_to_delete->id], $headers);
        $propriedade = Propriedade::find($propriedade_to_delete->id);
        $this->assertNull($propriedade);
    }

    public function testQueryPropriedade()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Propriedade::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_propriedade', [], $headers);
        $this->assertEquals(10, $response->json('data.propriedades.total'));
    }
}