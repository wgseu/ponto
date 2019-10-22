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
use App\Models\Caixa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaixaTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCaixa()
    {
        $headers = PrestadorTest::auth();
        $seed_caixa =  factory(Caixa::class)->create();
        $response = $this->graphfl('create_caixa', [
            'input' => [
                'carteira_id' => $seed_caixa->carteira_id,
                'descricao' => 'Teste',
            ]
        ], $headers);

        $found_caixa = Caixa::findOrFail($response->json('data.CreateCaixa.id'));
        $this->assertEquals($seed_caixa->carteira_id, $found_caixa->carteira_id);
        $this->assertEquals('Teste', $found_caixa->descricao);
    }

    public function testUpdateCaixa()
    {
        $headers = PrestadorTest::auth();
        $caixa = factory(Caixa::class)->create();
        $this->graphfl('update_caixa', [
            'id' => $caixa->id,
            'input' => [
                'descricao' => 'Atualizou',
            ]
        ], $headers);
        $caixa->refresh();
        $this->assertEquals('Atualizou', $caixa->descricao);
    }

    public function testDeleteCaixa()
    {
        $headers = PrestadorTest::auth();
        $caixa_to_delete = factory(Caixa::class)->create();
        $caixa_to_delete = $this->graphfl('delete_caixa', ['id' => $caixa_to_delete->id], $headers);
        $caixa = Caixa::find($caixa_to_delete->id);
        $this->assertNull($caixa);
    }

    public function testFindCaixa()
    {
        $headers = PrestadorTest::auth();
        $caixa = factory(Caixa::class)->create();
        $response = $this->graphfl('query_caixa', [ 'id' => $caixa->id ], $headers);
        $this->assertEquals($caixa->id, $response->json('data.caixas.data.0.id'));
    }
}
