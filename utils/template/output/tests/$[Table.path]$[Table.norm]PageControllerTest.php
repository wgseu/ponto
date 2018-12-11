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
$[table.if(package)]
namespace $[Table.package];
$[table.end]

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class $[Table.norm]PageControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        $$[table.unix] = $[Table.norm]Test::create();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_$[TABLE.style]]);
        $result = $this->get('/gerenciar/$[table.unix]/', ['search' => $$[table.unix]->get$[Descriptor.norm]()]);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testAdd()
    {
        $$[table.unix] = $[Table.norm]Test::build();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_$[TABLE.style]]);
        $result = $this->post('/gerenciar/$[table.unix]/cadastrar', $$[table.unix]->toArray(), true);
        $this->assertEquals(302, $result->getStatusCode());
        $$[table.unix]->load(['$[descriptor]' => $$[table.unix]->get$[Descriptor.norm]()]);
        $this->assertTrue($$[table.unix]->exists());
    }

    public function testUpdate()
    {
        $$[table.unix] = $[Table.norm]Test::create();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_$[TABLE.style]]);
        $id = $$[table.unix]->get$[Primary.norm]();
        $result = $this->post('/gerenciar/$[table.unix]/editar?id=' . $id, $$[table.unix]->toArray(), true);
        $$[table.unix]->loadBy$[Primary.norm]();
        $this->assertEquals(302, $result->getStatusCode());
    }

    public function testDelete()
    {
        $$[table.unix] = $[Table.norm]Test::create();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_$[TABLE.style]]);
        $id = $$[table.unix]->get$[Primary.norm]();
        $result = $this->get('/gerenciar/$[table.unix]/excluir?id=' . $id);
        $$[table.unix]->loadBy$[Primary.norm]();
        $this->assertEquals(302, $result->getStatusCode());
        $this->assertFalse($$[table.unix]->exists());
    }
}
