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

class FuncaoPageControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authOwner();
        $funcao = FuncaoTest::create();
        $result = $this->get('/gerenciar/funcao/', ['search' => $funcao->getDescricao()]);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testAdd()
    {
        AuthenticationTest::authOwner();
        $funcao = FuncaoTest::build();
        $result = $this->post('/gerenciar/funcao/cadastrar', $funcao->toArray(), true);
        $this->assertEquals(302, $result->getStatusCode());
        $funcao->load(['descricao' => $funcao->getDescricao()]);
        $this->assertTrue($funcao->exists());
    }

    public function testUpdate()
    {
        AuthenticationTest::authOwner();
        $funcao = FuncaoTest::create();
        $id = $funcao->getID();
        $result = $this->post('/gerenciar/funcao/editar?id=' . $id, $funcao->toArray(), true);
        $funcao->loadByID();
        $this->assertEquals(302, $result->getStatusCode());
    }

    public function testDelete()
    {
        AuthenticationTest::authOwner();
        $funcao = FuncaoTest::create();
        $id = $funcao->getID();
        $result = $this->get('/gerenciar/funcao/excluir?id=' . $id);
        $funcao->loadByID();
        $this->assertEquals(302, $result->getStatusCode());
        $this->assertFalse($funcao->exists());
    }
}
