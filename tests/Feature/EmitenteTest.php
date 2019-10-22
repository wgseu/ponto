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
use App\Models\Emitente;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmitenteTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateEmitente()
    {
        $headers = PrestadorTest::auth();
        $seed_emitente =  factory(Emitente::class)->create();
        $response = $this->graphfl('create_emitente', [
            'input' => [
                'regime_id' => $seed_emitente->regime_id,
            ]
        ], $headers);

        $found_emitente = Emitente::findOrFail($response->json('data.CreateEmitente.id'));
        $this->assertEquals($seed_emitente->regime_id, $found_emitente->regime_id);
    }

    public function testUpdateEmitente()
    {
        $headers = PrestadorTest::auth();
        $emitente = factory(Emitente::class)->create();
        $this->graphfl('update_emitente', [
            'id' => $emitente->id,
            'input' => [
            ]
        ], $headers);
        $emitente->refresh();
    }

    public function testDeleteEmitente()
    {
        $headers = PrestadorTest::auth();
        $emitente_to_delete = factory(Emitente::class)->create();
        $emitente_to_delete = $this->graphfl('delete_emitente', ['id' => $emitente_to_delete->id], $headers);
        $emitente = Emitente::find($emitente_to_delete->id);
        $this->assertNull($emitente);
    }

    public function testFindEmitente()
    {
        $headers = PrestadorTest::auth();
        $emitente = factory(Emitente::class)->create();
        $response = $this->graphfl('query_emitente', [ 'id' => $emitente->id ], $headers);
        $this->assertEquals($emitente->id, $response->json('data.emitentes.data.0.id'));
    }
}
