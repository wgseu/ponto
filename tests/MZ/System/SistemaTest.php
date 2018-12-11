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

class SistemaTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        $condition = ['id' => '1'];
        $sistema = Sistema::find($condition);
        $this->assertTrue($sistema->exists());
        list($found_sistema) = Sistema::findAll($condition, [], 1);
        $this->assertEquals($sistema, $found_sistema);
        $this->assertEquals(1, Sistema::count($condition));
    }

    public function testAdd()
    {
        $sistema = Sistema::find(['id' => '1']);
        $this->expectException('\Exception');
        $sistema->insert();
    }

    public function testUpdate()
    {
        $sistema = Sistema::find(['id' => '1']);
        $sistema->update();
        $this->assertTrue($sistema->exists());
    }

    public function testDelete()
    {
        $sistema = Sistema::find(['id' => '1']);
        $this->expectException('\Exception');
        $sistema->delete();
    }
}
