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
use App\Models\Zona;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ZonaTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateZona()
    {
        $headers = PrestadorTest::auth();
        $seed_zona =  factory(Zona::class)->create();
        $response = $this->graphfl('create_zona', [
            'input' => [
                'bairro_id' => $seed_zona->bairro_id,
                'nome' => 'Teste',
                'adicional_entrega' => 1.50,
            ]
        ], $headers);

        $found_zona = Zona::findOrFail($response->json('data.CreateZona.id'));
        $this->assertEquals($seed_zona->bairro_id, $found_zona->bairro_id);
        $this->assertEquals('Teste', $found_zona->nome);
        $this->assertEquals(1.50, $found_zona->adicional_entrega);
    }

    public function testUpdateZona()
    {
        $headers = PrestadorTest::auth();
        $zona = factory(Zona::class)->create();
        $this->graphfl('update_zona', [
            'id' => $zona->id,
            'input' => [
                'nome' => 'Atualizou',
                'adicional_entrega' => 1.50,
            ]
        ], $headers);
        $zona->refresh();
        $this->assertEquals('Atualizou', $zona->nome);
        $this->assertEquals(1.50, $zona->adicional_entrega);
    }

    public function testDeleteZona()
    {
        $headers = PrestadorTest::auth();
        $zona_to_delete = factory(Zona::class)->create();
        $zona_to_delete = $this->graphfl('delete_zona', ['id' => $zona_to_delete->id], $headers);
        $zona = Zona::find($zona_to_delete->id);
        $this->assertNull($zona);
    }

    public function testQueryZona()
    {
        for ($i=0; $i < 10; $i++) {
            factory(Zona::class)->create();
        }
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_zona', [], $headers);
        $this->assertEquals(10, $response->json('data.zonas.total'));
    }
}
