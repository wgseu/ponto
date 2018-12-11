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

class $[Table.norm]ApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        $$[table.unix] = $[Table.norm]Test::create();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_$[TABLE.style]]);
        $expected = [
            'status' => 'ok',
            'items' => [
                $$[table.unix]->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/$[table.unix.plural]', ['search' => $$[table.unix]->get$[Descriptor.norm]()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        $$[table.unix] = $[Table.norm]Test::build();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_$[TABLE.style]]);
        $expected = [
            'status' => 'ok',
            'item' => [
                $$[table.unix]->publish(app()->auth->provider),
            ]
        ];
        $result = $this->post('/api/$[table.unix.plural]', $$[table.unix]->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        $$[table.unix] = $[Table.norm]Test::create();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_$[TABLE.style]]);
        $id = $$[table.unix]->get$[Primary.norm]();
        $result = $this->patch('/api/$[table.unix.plural]/' . $id, $$[table.unix]->toArray());
        $$[table.unix]->loadBy$[Primary.norm]();
        $expected = [
            'status' => 'ok',
            'item' => [
                $$[table.unix]->publish(app()->auth->provider),
            ]
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        $$[table.unix] = $[Table.norm]Test::create();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_$[TABLE.style]]);
        $id = $$[table.unix]->get$[Primary.norm]();
        $result = $this->delete('/api/$[table.unix.plural]/' . $id);
        $$[table.unix]->loadBy$[Primary.norm]();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($$[table.unix]->exists());
    }
}
