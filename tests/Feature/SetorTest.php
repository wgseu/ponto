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
use App\Models\Setor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SetorTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateSetor()
    {
        $headers = PrestadorTest::auth();
        $seed_setor =  factory(Setor::class)->create();
        $response = $this->graphfl('create_setor', [
            'input' => [
                'nome' => 'Cozinha',
            ]
        ], $headers);

        $found_setor = Setor::findOrFail($response->json('data.CreateSetor.id'));
        $this->assertEquals('Cozinha', $found_setor->nome);
    }

    public function testFindSetor()
    {
        $headers = PrestadorTest::auth();
        $setor = factory(Setor::class)->create();
        $response = $this->graphfl('find_setor_id', [
            'id' => $setor->id,
        ], $headers);

        $this->assertEquals(
            $setor->id,
            $response->json('data.setores.data.0.id')
        );
        $this->assertEquals(
            $setor->nome,
            $response->json('data.setores.data.0.nome')
        );
        $this->assertEquals(
            $setor->nome,
            $response->json('data.setores.data.0.nome')
        );
    }

    public function testUpdateSetor()
    {
        $headers = PrestadorTest::auth();
        $setor = factory(Setor::class)->create();
        $this->graphfl('update_setor', [
            'id' => $setor->id,
            'input' => [
                'nome' => 'Atualizou',
            ]
        ], $headers);
        $setor->refresh();
        $this->assertEquals('Atualizou', $setor->nome);
    }

    public function testDeleteSetor()
    {
        $headers = PrestadorTest::auth();
        $setor_to_delete = factory(Setor::class)->create();
        $this->graphfl('delete_setor', ['id' => $setor_to_delete->id], $headers);
        $setor = Setor::find($setor_to_delete->id);
        $this->assertNull($setor);
    }


    public function testValidateSetorInvalidSubsetor()
    {
        $setor = factory(Setor::class)->create();
        $setor->delete();
        $setor->setor_id = 100;
        $this->expectException('\Exception');
        $setor->save();
    }

    public function testValidateSetorCreateSubsetor()
    {
        $setorPai = factory(Setor::class)->create();
        $subsetor = factory(Setor::class)->create();
        $subsetor->setor_id = $setorPai->id;
        $subsetor->save();
        $setor = factory(Setor::class)->create();
        $setor->delete();
        $setor->setor_id = $subsetor->id;
        $this->expectException('\Exception');
        $setor->save();
    }

    public function testValidateSetorUpdateSubsetorEleMesmo()
    {
        $setor = factory(Setor::class)->create();
        $setor->setor_id = $setor->id;
        $this->expectException('\Exception');
        $setor->save();
    }

    public function testValidateSetorUpdateSubsetorDoSetorpai()
    {
        $setorPai = factory(Setor::class)->create();
        $subsetor = factory(Setor::class)->create();
        $subsetor->setor_id = $setorPai->id;
        $subsetor->save();
        $setor = factory(Setor::class)->create();
        $setorPai->setor_id = $setor->id;
        $this->expectException('\Exception');
        $setorPai->save();
    }
}
