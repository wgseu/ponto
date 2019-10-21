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
use App\Models\Impressora;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImpressoraTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateImpressora()
    {
        $headers = PrestadorTest::auth();
        $seed_impressora =  factory(Impressora::class)->create();
        $response = $this->graphfl('create_impressora', [
            'input' => [
                'dispositivo_id' => $seed_impressora->dispositivo_id,
                'setor_id' => $seed_impressora->setor_id,
                'nome' => 'Teste',
                'modelo' => 'Teste',
            ]
        ], $headers);

        $found_impressora = Impressora::findOrFail($response->json('data.CreateImpressora.id'));
        $this->assertEquals($seed_impressora->dispositivo_id, $found_impressora->dispositivo_id);
        $this->assertEquals($seed_impressora->setor_id, $found_impressora->setor_id);
        $this->assertEquals('Teste', $found_impressora->nome);
        $this->assertEquals('Teste', $found_impressora->modelo);
    }

    public function testUpdateImpressora()
    {
        $headers = PrestadorTest::auth();
        $impressora = factory(Impressora::class)->create();
        $this->graphfl('update_impressora', [
            'id' => $impressora->id,
            'input' => [
                'nome' => 'Atualizou',
                'modelo' => 'Atualizou',
            ]
        ], $headers);
        $impressora->refresh();
        $this->assertEquals('Atualizou', $impressora->nome);
        $this->assertEquals('Atualizou', $impressora->modelo);
    }

    public function testDeleteImpressora()
    {
        $headers = PrestadorTest::auth();
        $impressora_to_delete = factory(Impressora::class)->create();
        $impressora_to_delete = $this->graphfl('delete_impressora', ['id' => $impressora_to_delete->id], $headers);
        $impressora = Impressora::find($impressora_to_delete->id);
        $this->assertNull($impressora);
    }

    public function testQueryImpressora()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Impressora::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_impressora', [], $headers);
        $this->assertEquals(10, $response->json('data.impressoras.total'));
    }
}
