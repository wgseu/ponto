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
use IntegracaoSeeder;
use App\Models\Integracao;

class LoginFacebookTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new IntegracaoSeeder())->run();
        $integration = Integracao::where('codigo', 'facebook')->firstOrFail();
        $integration->update(['login' => 'blabla', 'secret' => 'jaja', 'ativo' => true]);
    }

    public function testLoginSuccessful()
    {
        $token = 'fake_token';
        $response = $this->graphfl('login_facebook', ['token' => $token]);
        $user = $response->json('data.LoginFacebook.user');
        $this->assertEquals('fake@facebook.com', $user['email']);
    }

    public function testLoginInvalidToken()
    {
        $token = 'invalid_token';
        $this->expectException(\Exception::class);
        $response = $this->graphfl('login_facebook', ['token' => $token]);
    }
}
