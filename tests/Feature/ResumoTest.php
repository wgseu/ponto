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

use App\Models\Movimentacao;
use Tests\TestCase;
use App\Models\Resumo;

class ResumoTest extends TestCase
{
    public function testUpdateResumo()
    {
        $headers = PrestadorTest::authOwner();
        $resumo = factory(Resumo::class)->create();
        $this->graphfl('update_resumo', [
            'id' => $resumo->id,
            'input' => [
                'valor' => 1.50,
            ]
        ], $headers);
        $resumo->refresh();
        $this->assertEquals(1.50, $resumo->valor);
    }

    public function testFindResumo()
    {
        $headers = PrestadorTest::authOwner();
        $resumo = factory(Resumo::class)->create();
        $response = $this->graphfl('query_resumo', [ 'id' => $resumo->id ], $headers);
        $this->assertEquals($resumo->id, $response->json('data.resumos.data.0.id'));
    }
}
