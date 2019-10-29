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

use App\Models\Modulo;
use Tests\TestCase;

class ModuloTest extends TestCase
{
    public function testUpdateModulo()
    {
        $headers = PrestadorTest::auth();
        $modulo = Modulo::find(1);
        $this->graphfl('update_modulo', [
            'id' => $modulo->id,
            'input' => [
                'habilitado' => false,
            ]
        ], $headers);
        $modulo->refresh();
        $this->assertEquals(0, $modulo->habilitado);
    }

    public function testFindModulo()
    {
        $headers = PrestadorTest::auth();
        $response = $this->graphfl('query_modulo', [ 'id' => 8 ], $headers);
        $this->assertEquals(8, $response->json('data.modulos.data.0.id'));
    }
}
