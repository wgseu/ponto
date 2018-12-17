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
namespace MZ\Stock;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class FornecedorPageControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROFORNECEDORES]);
        $fornecedor = FornecedorTest::create();
        $result = $this->get('/gerenciar/fornecedor/', ['search' => $fornecedor->getEmpresaID()]);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROFORNECEDORES]);
        $fornecedor = FornecedorTest::build();
        $result = $this->post('/gerenciar/fornecedor/cadastrar', $fornecedor->toArray(), true);
        $this->assertEquals(302, $result->getStatusCode());
        $fornecedor->load(['empresaid' => $fornecedor->getEmpresaID()]);
        $this->assertTrue($fornecedor->exists());
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROFORNECEDORES]);
        $fornecedor = FornecedorTest::create();
        $id = $fornecedor->getID();
        $result = $this->post('/gerenciar/fornecedor/editar?id=' . $id, $fornecedor->toArray(), true);
        $fornecedor->loadByID();
        $this->assertEquals(302, $result->getStatusCode());
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROFORNECEDORES]);
        $fornecedor = FornecedorTest::create();
        $id = $fornecedor->getID();
        $result = $this->get('/gerenciar/fornecedor/excluir?id=' . $id);
        $fornecedor->loadByID();
        $this->assertEquals(302, $result->getStatusCode());
        $this->assertFalse($fornecedor->exists());
    }
}
