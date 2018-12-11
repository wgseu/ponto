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
namespace MZ\Location;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class EstadoPageControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        $estado = EstadoTest::create();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROESTADOS]);
        $result = $this->get('/gerenciar/estado/', ['search' => $estado->getNome()]);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testAdd()
    {
        $estado = EstadoTest::build();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROESTADOS]);
        $result = $this->post('/gerenciar/estado/cadastrar', $estado->toArray(), true);
        $this->assertEquals(302, $result->getStatusCode());
        $estado->load(['nome' => $estado->getNome()]);
        $this->assertTrue($estado->exists());
    }

    public function testUpdate()
    {
        $estado = EstadoTest::create();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROESTADOS]);
        $id = $estado->getID();
        $result = $this->post('/gerenciar/estado/editar?id=' . $id, $estado->toArray(), true);
        $estado->loadByID();
        $this->assertEquals(302, $result->getStatusCode());
    }

    public function testDelete()
    {
        $estado = EstadoTest::create();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROESTADOS]);
        $id = $estado->getID();
        $result = $this->get('/gerenciar/estado/excluir?id=' . $id);
        $estado->loadByID();
        $this->assertEquals(302, $result->getStatusCode());
        $this->assertFalse($estado->exists());
    }
}
