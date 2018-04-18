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

class $[Table.norm]Test extends \PHPUnit_Framework_TestCase
{
    public function testFromArray()
    {
        $old_$[table.unix] = new $[Table.norm]([
$[field.each(all)]
            '$[field]' => $[field.if(integer)]123$[field.else.if(float)]12.3$[field.else.if(boolean)]'Y'$[field.else.if(datetime)]'2016-12-05 12:15:00'$[field.else.if(blob)]"\x5\x0\x3"$[field.else.if(image)]'image.png'$[field.else]'$[Table.name]'$[field.end],
$[field.end]
        ]);
        $$[table.unix] = new $[Table.norm]();
        $$[table.unix]->fromArray($old_$[table.unix]);
        $this->assertEquals($$[table.unix], $old_$[table.unix]);
        $$[table.unix]->fromArray(null);
        $this->assertEquals($$[table.unix], new $[Table.norm]());
    }

    public function testPublish()
    {
        $$[table.unix] = new $[Table.norm]();
        $values = $$[table.unix]->publish();
        $allowed = [
$[field.each(all)]
$[field.match(ip|senha|password|secreto|salt|deletado)]
$[field.else]
            '$[field]',
$[field.end]
$[field.end]
        ];
        $this->assertEquals($allowed, array_keys($values));
    }
}
