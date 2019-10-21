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
use App\Models\Banco;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BancoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateBanco()
    {
        $headers = PrestadorTest::auth();
        $seed_banco =  factory(Banco::class)->create();
        $response = $this->graphfl('create_banco', [
            'input' => [
                'numero' => 'Teste',
                'fantasia' => 'Teste',
                'razao_social' => 'Teste',
            ]
        ], $headers);

        $found_banco = Banco::findOrFail($response->json('data.CreateBanco.id'));
        $this->assertEquals('Teste', $found_banco->numero);
        $this->assertEquals('Teste', $found_banco->fantasia);
        $this->assertEquals('Teste', $found_banco->razao_social);
    }

    public function testUpdateBanco()
    {
        $headers = PrestadorTest::auth();
        $banco = factory(Banco::class)->create();
        $this->graphfl('update_banco', [
            'id' => $banco->id,
            'input' => [
                'numero' => 'Atualizou',
                'fantasia' => 'Atualizou',
                'razao_social' => 'Atualizou',
            ]
        ], $headers);
        $banco->refresh();
        $this->assertEquals('Atualizou', $banco->numero);
        $this->assertEquals('Atualizou', $banco->fantasia);
        $this->assertEquals('Atualizou', $banco->razao_social);
    }

    public function testDeleteBanco()
    {
        $headers = PrestadorTest::auth();
        $banco_to_delete = factory(Banco::class)->create();
        $banco_to_delete = $this->graphfl('delete_banco', ['id' => $banco_to_delete->id], $headers);
        $banco = Banco::find($banco_to_delete->id);
        $this->assertNull($banco);
    }

    public function testQueryBanco()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Banco::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_banco', [], $headers);
        $this->assertEquals(10, $response->json('data.bancos.total'));
    }
}