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
use App\Models\Juncao;
use App\Models\Mesa;

class JuncaoTest extends TestCase
{
    public function testCreateJuncao()
    {
        $headers = PrestadorTest::authOwner();
        $seed_juncao =  factory(Juncao::class)->create();
        $response = $this->graphfl('create_juncao', [
            'input' => [
                'mesa_id' => $seed_juncao->mesa_id,
                'pedido_id' => $seed_juncao->pedido_id,
            ]
        ], $headers);

        $found_juncao = Juncao::findOrFail($response->json('data.CreateJuncao.id'));
        $this->assertEquals($seed_juncao->mesa_id, $found_juncao->mesa_id);
        $this->assertEquals($seed_juncao->pedido_id, $found_juncao->pedido_id);
    }

    public function testUpdateJuncao()
    {
        $headers = PrestadorTest::authOwner();
        $juncao = factory(Juncao::class)->create();
        $mesa = factory(Mesa::class)->create();
        $this->graphfl('update_juncao', [
            'id' => $juncao->id,
            'input' => [
                'mesa_id' => $mesa->id,
            ]
        ], $headers);
        $juncao->refresh();
        $this->assertEquals($mesa->id, $juncao->mesa_id);
    }
}
