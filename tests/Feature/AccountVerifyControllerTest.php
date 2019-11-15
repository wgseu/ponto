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
use App\Models\Cliente;

class AccountVerifyControllerTest extends TestCase
{
    public function testActivateByToken()
    {
        /** @var Cliente $cliente */
        $cliente = factory(Cliente::class)->create();
        $this->assertEquals(Cliente::STATUS_INATIVO, $cliente->status);
        $token = $cliente->createValidateToken();
        $response = $this->get("/account/verify/$token");
        $response->assertStatus(302);
        $cliente->refresh();
        $this->assertEquals(Cliente::STATUS_ATIVO, $cliente->status);
    }

    public function testInvalidActivationToken()
    {
        /** @var Cliente $cliente */
        $cliente = factory(Cliente::class)->create();
        $this->assertEquals(Cliente::STATUS_INATIVO, $cliente->status);
        // with access token
        $token = auth()->fromUser($cliente);
        $response = $this->get("/account/verify/$token");
        $response->assertStatus(401);

        // with refresh token
        $token = $cliente->createRefreshToken();
        $response = $this->get("/account/verify/$token");
        $response->assertStatus(401);
    }
}
