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
use App\Models\Contagem;
use App\Models\Produto;
use App\Models\Setor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContagemTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateContagem()
    {
        $headers = PrestadorTest::auth();
        $produto_id = factory(Produto::class)->create();
        $setor_id = factory(Setor::class)->create();
        $response = $this->graphfl('create_contagem', [
            'input' => [
                'produto_id' => $produto_id->id,
                'setor_id' => $setor_id->id,
                'quantidade' => 1.0,
            ]
        ], $headers);

        $found_contagem = Contagem::findOrFail($response->json('data.CreateContagem.id'));
        $this->assertEquals($produto_id->id, $found_contagem->produto_id);
        $this->assertEquals($setor_id->id, $found_contagem->setor_id);
        $this->assertEquals(1.0, $found_contagem->quantidade);
    }

    public function testUpdateContagem()
    {
        $headers = PrestadorTest::auth();
        $contagem = factory(Contagem::class)->create();
        $this->graphfl('update_contagem', [
            'id' => $contagem->id,
            'input' => [
                'quantidade' => 1.0,
            ]
        ], $headers);
        $contagem->refresh();
        $this->assertEquals(1.0, $contagem->quantidade);
    }

    public function testDeleteContagem()
    {
        $headers = PrestadorTest::auth();
        $contagem_to_delete = factory(Contagem::class)->create();
        $this->graphfl('delete_contagem', ['id' => $contagem_to_delete->id], $headers);
        $contagem = Contagem::find($contagem_to_delete->id);
        $this->assertNull($contagem);
    }

    public function testFindContagem()
    {
        $headers = PrestadorTest::auth();
        $contagem = factory(Contagem::class)->create();
        $response = $this->graphfl('query_contagem', [ 'id' => $contagem->id ], $headers);
        $this->assertEquals($contagem->id, $response->json('data.contagens.data.0.id'));
    }
}
