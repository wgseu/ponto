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
namespace MZ\Provider;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class PrestadorPageControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROFUNCIONARIOS]);
        $prestador = PrestadorTest::create();
        $result = $this->get('/gerenciar/funcionario/', ['search' => $prestador->getClienteID()]);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROFUNCIONARIOS]);
        $prestador = PrestadorTest::build();
        $result = $this->post('/gerenciar/funcionario/cadastrar', $prestador->toArray(), true);
        $this->assertEquals(302, $result->getStatusCode());
        $prestador->load(['clienteid' => $prestador->getClienteID()]);
        $this->assertTrue($prestador->exists());
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROFUNCIONARIOS]);
        $prestador = PrestadorTest::create();
        $id = $prestador->getID();
        $result = $this->post('/gerenciar/funcionario/editar?id=' . $id, $prestador->toArray(), true);
        $prestador->loadByID();
        $this->assertEquals(302, $result->getStatusCode());
    }

    public function testDelete()
    {
        AuthenticationTest::authOwner();
        $prestador = PrestadorTest::create();
        $id = $prestador->getID();
        $result = $this->get('/gerenciar/funcionario/excluir?id=' . $id);
        $prestador->loadByID();
        $this->assertEquals(302, $result->getStatusCode());
        $this->assertFalse($prestador->exists());
    }
}
