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
namespace MZ\System;

use MZ\System\Permissao;
use MZ\Provider\FuncaoTest;
use MZ\Account\AuthenticationTest;

class AcessoPageControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authOwner();
        list($acesso) = AcessoTest::create(FuncaoTest::create([]), [Permissao::NOME_ALTERARCONFIGURACOES]);
        $result = $this->get('/gerenciar/acesso/', [
            'funcao' => $acesso->getFuncaoID(),
            'search' => $acesso->findPermissaoID()->getNome(),
        ]);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testAdd()
    {
        AuthenticationTest::authOwner();
        $funcao = FuncaoTest::create([]);
        $permissao = Permissao::findByNome(Permissao::NOME_ALTERARCONFIGURACOES);
        $result = $this->post('/gerenciar/acesso/', [
            'funcao' => $funcao->getID(),
            'permissao' => $permissao->getID(),
            'marcado' => 'Y',
        ], true);
        $this->assertEquals(302, $result->getStatusCode());
        $acesso =  Acesso::find(['permissaoid' => $permissao->getID(), 'funcaoid' => $funcao->getID()]);
        $this->assertTrue($acesso->exists());
    }

    public function testDelete()
    {
        AuthenticationTest::authOwner();
        list($acesso) = AcessoTest::create(FuncaoTest::create([]), [Permissao::NOME_ALTERARCONFIGURACOES]);
        $id = $acesso->getID();
        $result = $this->post('/gerenciar/acesso/', [
            'funcao' => $acesso->getFuncaoID(),
            'permissao' => $acesso->getPermissaoID(),
            'marcado' => 'N',
        ], true);
        $acesso->loadByID();
        $this->assertEquals(302, $result->getStatusCode());
        $this->assertFalse($acesso->exists());
    }
}
