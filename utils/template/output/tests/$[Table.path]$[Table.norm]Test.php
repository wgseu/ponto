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

$[field.each(all)]
$[field.if(null|primary)]
$[field.else.if(reference)]
use $[Reference.package]\$[Reference.norm]Test;
$[field.end]
$[field.end]

class $[Table.norm]Test extends \MZ\Framework\TestCase
{
    /**
     * Build a valid $[table.name]
$[descriptor.if(string)]
     * @param string $$[descriptor.unix] $[Table.name] $[descriptor.name]
$[descriptor.end]
     * @return $[Table.norm]
     */
    public static function build($[descriptor.if(string)]$$[descriptor.unix] = null$[descriptor.end])
    {
        $last = $[Table.norm]::find([], ['id' => -1]);
        $id = $last->getID() + 1;
$[field.each(all)]
$[field.if(null|primary)]
$[field.else.if(reference)]
        $$[reference.unix] = $[Reference.norm]Test::create();
$[field.end]
$[field.end]
        $$[table.unix] = new $[Table.norm]();
$[field.each(all)]
$[field.if(null|primary|info)]
$[field.else.if(reference)]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$$[reference.unix]->get$[Reference.pk.norm]());
$[field.else.if(info)]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[Field.info]);
$[field.else.if(enum)]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[Table.norm]::$[field.each(option)]$[field.if(first)]$[FIELD.unix]_$[FIELD.option.norm]$[field.end]$[field.end]);
$[field.else.if(descriptor)]
$[descriptor.if(string)]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$$[field.unix] ?: "$[Table.name] {$id}");
$[descriptor.else]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$id);
$[descriptor.end]
$[field.else.if(unique)]
$[field.if(string)]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$$[field.unix] ?: "$[Table.name] {$id}");
$[field.else]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$id);
$[field.end]
$[field.else.match(.*atualizacao|.*cadastro|.*criacao|.*lancamento|.*envio)]
$[field.else]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[field.if(integer|bigint)]123$[field.else.if(float|currency|double)]12.3$[field.else.if(boolean)]'Y'$[field.else.if(datetime)]'2016-12-25 12:15:00'$[field.else.if(blob)]"\x5\x0\x3"$[field.else.if(image)]'image.png'$[field.else]'$[Field.name] d$[table.gender] $[table.name]'$[field.end]);
$[field.end]
$[field.end]
        return $$[table.unix];
    }

    /**
     * Create a $[table.name] on database
$[descriptor.if(string)]
     * @param string $$[descriptor.unix] $[Table.name] $[descriptor.name]
$[descriptor.end]
     * @return $[Table.norm]
     */
    public static function create($[descriptor.if(string)]$$[descriptor.unix] = null$[descriptor.end])
    {
        $$[table.unix] = self::build($[descriptor.if(string)]$$[descriptor.unix]$[descriptor.end]);
        $$[table.unix]->insert();
        return $$[table.unix];
    }

    public function testFind()
    {
        $$[table.unix] = self::create();
        $condition = ['$[descriptor]' => $$[table.unix]->get$[Descriptor.norm]()];
        $found_$[table.unix] = $[Table.norm]::find($condition);
        $this->assertEquals($$[table.unix], $found_$[table.unix]);
        list($found_$[table.unix]) = $[Table.norm]::findAll($condition, [], 1);
        $this->assertEquals($$[table.unix], $found_$[table.unix]);
        $this->assertEquals(1, $[Table.norm]::count($condition));
    }

    public function testAdd()
    {
        $$[table.unix] = self::build();
        $$[table.unix]->insert();
        $this->assertTrue($$[table.unix]->exists());
    }

    public function testUpdate()
    {
        $$[table.unix] = self::create();
        $$[table.unix]->update();
        $this->assertTrue($$[table.unix]->exists());
    }

    public function testDelete()
    {
        $$[table.unix] = self::create();
        $$[table.unix]->delete();
        $$[table.unix]->loadBy$[Primary.norm]();
        $this->assertFalse($$[table.unix]->exists());
    }
}
