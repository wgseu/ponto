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
namespace MZ\Invoice;

class OrigemTest extends \MZ\Framework\TestCase
{
    public function testFromArray()
    {
        $old_origem = new Origem([
            'id' => 123,
            'codigo' => 123,
            'descricao' => 'Origem',
        ]);
        $origem = new Origem();
        $origem->fromArray($old_origem);
        $this->assertEquals($origem, $old_origem);
        $origem->fromArray(null);
        $this->assertEquals($origem, new Origem());
    }

    public function testFilter()
    {
        $old_origem = new Origem([
            'id' => 1234,
            'codigo' => 1234,
            'descricao' => 'Origem filter',
        ]);
        $origem = new Origem([
            'id' => 321,
            'codigo' => '1.234',
            'descricao' => ' Origem <script>filter</script> ',
        ]);
        $origem->filter($old_origem);
        $this->assertEquals($old_origem, $origem);
    }

    public function testPublish()
    {
        $origem = new Origem();
        $values = $origem->publish();
        $allowed = [
            'id',
            'codigo',
            'descricao',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $origem = new Origem();
        try {
            $origem->insert();
            $this->fail('Não deveria ter cadastrado a origem');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'codigo',
                    'descricao',
                ],
                array_keys($e->getErrors())
            );
        }
        $origem->setCodigo(123);
        $origem->setDescricao('Origem to insert');
        $origem->insert();
    }

    public function testUpdate()
    {
        $origem = new Origem();
        $origem->setCodigo(1234);
        $origem->setDescricao('Origem to update');
        $origem->insert();
        $origem->setCodigo(456);
        $origem->setDescricao('Origem updated');
        $origem->update();
        $found_origem = Origem::findByID($origem->getID());
        $this->assertEquals($origem, $found_origem);
        $origem->setID('');
        $this->expectException('\Exception');
        $origem->update();
    }

    public function testDelete()
    {
        $origem = new Origem();
        $origem->setCodigo(12345);
        $origem->setDescricao('Origem to delete');
        $origem->insert();
        $origem->delete();
        $origem->clean(new Origem());
        $found_origem = Origem::findByID($origem->getID());
        $this->assertEquals(new Origem(), $found_origem);
        $origem->setID('');
        $this->expectException('\Exception');
        $origem->delete();
    }

    public function testFind()
    {
        $origem = new Origem();
        $origem->setCodigo(123456);
        $origem->setDescricao('Origem find');
        $origem->insert();
        $found_origem = Origem::find(['id' => $origem->getID()]);
        $this->assertEquals($origem, $found_origem);
        $found_origem = Origem::findByID($origem->getID());
        $this->assertEquals($origem, $found_origem);
        $found_origem = Origem::findByCodigo($origem->getCodigo());
        $this->assertEquals($origem, $found_origem);

        $origem_sec = new Origem();
        $origem_sec->setCodigo(123654);
        $origem_sec->setDescricao('Origem find second');
        $origem_sec->insert();

        $origens = Origem::findAll(['search' => 'Origem find'], [], 2, 0);
        $this->assertEquals([$origem, $origem_sec], $origens);

        $count = Origem::count(['search' => 'Origem find']);
        $this->assertEquals(2, $count);
    }
}
