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
namespace MZ\Wallet;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class BancoPageControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        $banco = BancoTest::create();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROBANCOS]);
        $result = $this->get('/gerenciar/banco/', ['search' => $banco->getRazaoSocial()]);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testAdd()
    {
        $banco = BancoTest::build();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROBANCOS]);
        $result = $this->post('/gerenciar/banco/cadastrar', $banco->toArray(), true);
        $this->assertEquals(302, $result->getStatusCode());
        $banco->load(['razaosocial' => $banco->getRazaoSocial()]);
        $this->assertTrue($banco->exists());
    }

    public function testUpdate()
    {
        $banco = BancoTest::create();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROBANCOS]);
        $id = $banco->getID();
        $result = $this->post('/gerenciar/banco/editar?id=' . $id, $banco->toArray(), true);
        $banco->loadByID();
        $this->assertEquals(302, $result->getStatusCode());
    }

    public function testDelete()
    {
        $banco = BancoTest::create();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROBANCOS]);
        $id = $banco->getID();
        $result = $this->get('/gerenciar/banco/excluir?id=' . $id);
        $banco->loadByID();
        $this->assertEquals(302, $result->getStatusCode());
        $this->assertFalse($banco->exists());
    }
}