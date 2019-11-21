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
use App\Models\Emitente;

class EmitenteTest extends TestCase
{
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
        $this->assertEquals('1', $emitente->id);
    }

    public function testFindEmitente()
    {
        $headers = PrestadorTest::auth();
        $emitente = factory(Emitente::class)->create();
        $response = $this->graphfl('query_emitente', [], $headers);
        $this->assertEquals($emitente->id, $response->json('data.emitente.id'));
    }
}
