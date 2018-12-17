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
namespace MZ\Account;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class ClientePageControllerTest extends \MZ\Framework\TestCase
{
    public function testRegister()
    {
        $cliente = ClienteTest::build();
        $data = $cliente->toArray();
        $data['confirmarsenha'] = $cliente->getSenha();
        $data['aceitar'] = 'true';
        $result = $this->post('/conta/cadastrar', $data, true);
        $this->assertEquals(302, $result->getStatusCode());
        $cliente->load(['nome' => $cliente->getNome()]);
        $this->assertTrue($cliente->exists());
    }

    public function testRegisterAuthenticated()
    {
        $cliente = ClienteTest::create();
        app()->auth->login($cliente);
        $result = $this->post('/conta/cadastrar', [], true);
        $this->assertEquals(302, $result->getStatusCode());
    }

    public function testLogout()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCLIENTES]);
        $this->assertTrue(app()->auth->isLogin());
        $result = $this->get('/conta/sair');
        $this->assertEquals(302, $result->getStatusCode());
        $this->assertFalse(app()->auth->isLogin());
    }

    public function testEdit()
    {
        $cliente = ClienteTest::create();
        app()->getAuthentication()->login($cliente);
        $id = $cliente->getID();
        $data = $cliente->toArray();
        unset($data['senha']);
        $result = $this->post('/conta/editar', $data, true);
        $cliente->loadByID();
        $this->assertEquals(302, $result->getStatusCode());
    }

    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCLIENTES]);
        $cliente = ClienteTest::create();
        $result = $this->get('/gerenciar/cliente/', ['search' => $cliente->getNome()]);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCLIENTES]);
        $cliente = ClienteTest::build();
        $data = $cliente->toArray();
        $data['confirmarsenha'] = $cliente->getSenha();
        $result = $this->post('/gerenciar/cliente/cadastrar', $data, true);
        $this->assertEquals(302, $result->getStatusCode());
        $cliente->load(['nome' => $cliente->getNome()]);
        $this->assertTrue($cliente->exists());
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCLIENTES]);
        $cliente = ClienteTest::create();
        $id = $cliente->getID();
        $data = $cliente->toArray();
        unset($data['senha']);
        $result = $this->post('/gerenciar/cliente/editar?id=' . $id, $data, true);
        $cliente->loadByID();
        $this->assertEquals(302, $result->getStatusCode());
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCLIENTES]);
        $cliente = ClienteTest::create();
        $id = $cliente->getID();
        $result = $this->get('/gerenciar/cliente/excluir?id=' . $id);
        $cliente->loadByID();
        $this->assertEquals(302, $result->getStatusCode());
        $this->assertFalse($cliente->exists());
    }
}
