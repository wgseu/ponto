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
use App\Models\Telefone;

class TelefoneTest extends TestCase
{
    public function testCreateTelefone()
    {
        $headers = PrestadorTest::authOwner();
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
        $headers = PrestadorTest::authOwner();
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
        $headers = PrestadorTest::authOwner();
        $telefone_to_delete = factory(Telefone::class)->create();
        $this->graphfl('delete_telefone', ['id' => $telefone_to_delete->id], $headers);
        $telefone = Telefone::find($telefone_to_delete->id);
        $this->assertNull($telefone);
    }

    public function testFindTelefone()
    {
        $headers = PrestadorTest::authOwner();
        $telefone = factory(Telefone::class)->create();
        $response = $this->graphfl('query_telefone', [ 'id' => $telefone->id ], $headers);
        $this->assertEquals($telefone->id, $response->json('data.telefones.data.0.id'));
    }
}
