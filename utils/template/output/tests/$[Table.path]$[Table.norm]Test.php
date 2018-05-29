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
use $[Reference.package]\$[Reference.norm];
$[field.end]
$[field.end]
use \MZ\Database\DB;

class $[Table.norm]Test extends \PHPUnit_Framework_TestCase
{
    public function testFromArray()
    {
        $old_$[table.unix] = new $[Table.norm]([
$[field.each(all)]
            '$[field]' => $[field.if(integer|bigint)]123$[field.else.if(float|currency)]12.3$[field.else.if(boolean)]'Y'$[field.else.if(datetime)]'2016-12-25 12:15:00'$[field.else.if(blob)]"\x5\x0\x3"$[field.else.if(image)]'image.png'$[field.else]'$[Table.name]'$[field.end],
$[field.end]
        ]);
        $$[table.unix] = new $[Table.norm]();
        $$[table.unix]->fromArray($old_$[table.unix]);
        $this->assertEquals($$[table.unix], $old_$[table.unix]);
        $$[table.unix]->fromArray(null);
        $this->assertEquals($$[table.unix], new $[Table.norm]());
    }

    public function testFilter()
    {
        $old_$[table.unix] = new $[Table.norm]([
$[field.each(all)]
            '$[field]' => $[field.if(integer|bigint)]1234$[field.else.if(float|currency)]12.3$[field.else.if(boolean)]'Y'$[field.else.if(datetime)]'2016-12-23 12:15:00'$[field.else.if(blob)]"\x5\x0\x3"$[field.else.if(image)]'image.png'$[field.else]'$[Table.name] filter'$[field.end],
$[field.end]
        ]);
        $$[table.unix] = new $[Table.norm]([
$[field.each(all)]
            '$[field]' => $[field.if(primary)]321$[field.else.if(integer|bigint)]'1.234'$[field.else.if(float|currency)]'12,3'$[field.else.if(boolean)]'Y'$[field.else.if(datetime)]'23/12/2016 12:15'$[field.else.if(blob)]"\x5\x0\x3"$[field.else.if(image)]'image.png'$[field.else]' $[Table.name] <script>filter</script> '$[field.end],
$[field.end]
        ]);
        $$[table.unix]->filter($old_$[table.unix]);
        $this->assertEquals($old_$[table.unix], $$[table.unix]);
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

    public function testInsert()
    {
$[field.each(all)]
$[field.if(null|primary)]
$[field.else.if(reference)]
        // TODO: início copiar
        $$[reference.unix] = new $[Reference.norm]();
        $$[reference.unix]->insert();
        // TODO: fim copiar
$[field.end]
$[field.end]
        $$[table.unix] = new $[Table.norm]();
        try {
            $$[table.unix]->insert();
            $this->fail('Não deveria ter cadastrado $[table.gender] $[table.name]');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
$[field.each(all)]
$[field.if(null|primary)]
$[field.else.if(info)]
$[field.else]
                    '$[field]',
$[field.end]
$[field.end]
                ],
                array_keys($e->getErrors())
            );
        }
$[field.each(all)]
$[field.if(null|primary)]
$[field.else.if(reference)]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$$[reference.unix]->get$[Reference.pk.norm]());
$[field.else.if(info)]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[Field.info]);
$[field.else]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[field.if(integer|bigint)]123$[field.else.if(float|currency)]12.3$[field.else.if(boolean)]'Y'$[field.else.if(datetime)]'2016-12-25 12:15:00'$[field.else.if(blob)]"\x5\x0\x3"$[field.else.if(image)]'image.png'$[field.else]'$[Table.name] to insert'$[field.end]);
$[field.end]
$[field.end]
        $$[table.unix]->insert();
    }

    public function testUpdate()
    {
$[field.each(all)]
$[field.if(null|primary)]
$[field.else.if(reference)]
        // TODO: início copiar
        $$[reference.unix] = new $[Reference.norm]();
        $$[reference.unix]->insert();
        // TODO: fim copiar
$[field.end]
$[field.end]
        $$[table.unix] = new $[Table.norm]();
$[field.each(all)]
$[field.if(null|primary)]
$[field.else.if(reference)]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$$[reference.unix]->get$[Reference.pk.norm]());
$[field.else.if(info)]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[Field.info]);
$[field.else]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[field.if(integer|bigint)]123$[field.else.if(float|currency)]12.3$[field.else.if(boolean)]'N'$[field.else.if(datetime)]'2016-12-26 12:15:00'$[field.else.if(blob)]"\x5\x0\x3"$[field.else.if(image)]'image.png'$[field.else]'$[Table.name] to update'$[field.end]);
$[field.end]
$[field.end]
        $$[table.unix]->insert();
$[field.each(all)]
$[field.if(primary)]
$[field.else.if(reference)]
$[field.else.if(info)]
$[field.else]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[field.if(integer|bigint)]456$[field.else.if(float|currency)]21.4$[field.else.if(boolean)]'N'$[field.else.if(datetime)]'2016-12-25 14:15:00'$[field.else.if(blob)]"\x4\x0\x5"$[field.else.if(image)]'picture.png'$[field.else]'$[Table.name] updated'$[field.end]);
$[field.end]
$[field.end]
        $$[table.unix]->update();
        $found_$[table.unix] = $[Table.norm]::findByID($$[table.unix]->getID());
        $this->assertEquals($$[table.unix], $found_$[table.unix]);
    }

    public function testDelete()
    {
$[field.each(all)]
$[field.if(null|primary)]
$[field.else.if(reference)]
        // TODO: início copiar
        $$[reference.unix] = new $[Reference.norm]();
        $$[reference.unix]->insert();
        // TODO: fim copiar
$[field.end]
$[field.end]
        $$[table.unix] = new $[Table.norm]();
$[field.each(all)]
$[field.if(null|primary)]
$[field.else.if(reference)]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$$[reference.unix]->get$[Reference.pk.norm]());
$[field.else.if(info)]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[Field.info]);
$[field.else]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[field.if(integer|bigint)]123$[field.else.if(float|currency)]12.3$[field.else.if(boolean)]'Y'$[field.else.if(datetime)]'2016-12-20 12:15:00'$[field.else.if(blob)]"\x5\x0\x3"$[field.else.if(image)]'image.png'$[field.else]'$[Table.name] to delete'$[field.end]);
$[field.end]
$[field.end]
        $$[table.unix]->insert();
        $$[table.unix]->delete();
        $found_$[table.unix] = $[Table.norm]::findBy$[Primary.norm]($$[table.unix]->get$[Primary.norm]());
        $this->assertEquals(new $[Table.norm](), $found_$[table.unix]);
        $$[table.unix]->setID('');
        $this->setExpectedException('\Exception');
        $$[table.unix]->delete();
    }

    public function testFind()
    {
$[field.each(all)]
$[field.if(null|primary)]
$[field.else.if(reference)]
        // TODO: início copiar
        $$[reference.unix] = new $[Reference.norm]();
        $$[reference.unix]->insert();
        // TODO: fim copiar
$[field.end]
$[field.end]
        $$[table.unix] = new $[Table.norm]();
$[field.each(all)]
$[field.if(null|primary)]
$[field.else.if(reference)]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$$[reference.unix]->get$[Reference.pk.norm]());
$[field.else.if(info)]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[Field.info]);
$[field.else]
        $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[field.if(integer|bigint)]123$[field.else.if(float|currency)]12.3$[field.else.if(boolean)]'Y'$[field.else.if(datetime)]'2016-12-25 12:15:00'$[field.else.if(blob)]"\x5\x0\x3"$[field.else.if(image)]'image.png'$[field.else]'$[Table.name] find'$[field.end]);
$[field.end]
$[field.end]
        $$[table.unix]->insert();
$[table.each(unique)]
        $found_$[table.unix] = $[Table.norm]::findBy$[unique.each(all)]$[Field.norm]$[unique.end]($[unique.each(all)]$[field.if(first)]$[field.else], $[field.end]$$[table.unix]->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])$[unique.end]);
        $this->assertEquals($$[table.unix], $found_$[table.unix]);
        $found_$[table.unix]->loadBy$[unique.each(all)]$[Field.norm]$[unique.end]($[unique.each(all)]$[field.if(first)]$[field.else], $[field.end]$$[table.unix]->get$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end])$[unique.end]);
        $this->assertEquals($$[table.unix], $found_$[table.unix]);
$[table.end]

        $$[table.unix]_sec = new $[Table.norm]();
$[field.each(all)]
$[field.if(null|primary)]
$[field.else.if(reference)]
        $$[table.unix]_sec->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$$[reference.unix]->get$[Reference.pk.norm]());
$[field.else.if(info)]
        $$[table.unix]_sec->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[Field.info]);
$[field.else]
        $$[table.unix]_sec->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]$[field.if(integer|bigint)]123$[field.else.if(float|currency)]12.3$[field.else.if(boolean)]'Y'$[field.else.if(datetime)]'2016-12-25 12:15:00'$[field.else.if(blob)]"\x5\x0\x3"$[field.else.if(image)]'image.png'$[field.else]'$[Table.name] find second'$[field.end]);
$[field.end]
$[field.end]
        $$[table.unix]_sec->insert();

        $$[table.unix.plural] = $[Table.norm]::findAll(['search' => '$[Table.name] find'], [], 2, 0);
        $this->assertEquals([$$[table.unix], $$[table.unix]_sec], $$[table.unix.plural]);

        $count = $[Table.norm]::count(['search' => '$[Table.name] find']);
        $this->assertEquals(2, $count);
    }
}
