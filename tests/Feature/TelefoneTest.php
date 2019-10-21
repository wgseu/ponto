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
use App\Models\Telefone;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TelefoneTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateTelefone()
    {
        $headers = PrestadorTest::auth();
        $seed_telefone =  factory(Telefone::class)->create();
        $response = $this->graphfl('create_telefone', [
            'input' => [
                'cliente_id' => $seed_telefone->cliente_id,
                'pais_id' => $seed_telefone->pais_id,
                'numero' => 'Teste',
            ]
        ], $headers);

        $found_telefone = Telefone::findOrFail($response->json('data.CreateTelefone.id'));
        $this->assertEquals($seed_telefone->cliente_id, $found_telefone->cliente_id);
        $this->assertEquals($seed_telefone->pais_id, $found_telefone->pais_id);
        $this->assertEquals('Teste', $found_telefone->numero);
    }

    public function testUpdateTelefone()
    {
        $headers = PrestadorTest::auth();
        $telefone = factory(Telefone::class)->create();
        $this->graphfl('update_telefone', [
            'id' => $telefone->id,
            'input' => [
                'numero' => 'Atualizou',
            ]
        ], $headers);
        $telefone->refresh();
        $this->assertEquals('Atualizou', $telefone->numero);
    }

    public function testDeleteTelefone()
    {
        $headers = PrestadorTest::auth();
        $telefone_to_delete = factory(Telefone::class)->create();
        $telefone_to_delete = $this->graphfl('delete_telefone', ['id' => $telefone_to_delete->id], $headers);
        $telefone = Telefone::find($telefone_to_delete->id);
        $this->assertNull($telefone);
    }

    public function testQueryTelefone()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Telefone::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_telefone', [], $headers);
        $this->assertEquals(10, $response->json('data.telefones.total'));
    }
}