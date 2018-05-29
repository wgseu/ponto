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
namespace MZ\Wallet;

class MoedaTest extends \PHPUnit_Framework_TestCase
{
    public function testFromArray()
    {
        $old_moeda = new Moeda([
            'id' => 123,
            'nome' => 'Moeda',
            'simbolo' => 'Moeda',
            'codigo' => 'Moeda',
            'divisao' => 123,
            'fracao' => 'Moeda',
            'formato' => 'Moeda',
        ]);
        $moeda = new Moeda();
        $moeda->fromArray($old_moeda);
        $this->assertEquals($moeda, $old_moeda);
        $moeda->fromArray(null);
        $this->assertEquals($moeda, new Moeda());
    }

    public function testFilter()
    {
        $old_moeda = new Moeda([
            'id' => 1234,
            'nome' => 'Moeda filter',
            'simbolo' => 'Moeda filter',
            'codigo' => 'Moeda filter',
            'divisao' => 1234,
            'fracao' => 'Moeda filter',
            'formato' => 'Moeda filter',
        ]);
        $moeda = new Moeda([
            'id' => 321,
            'nome' => ' Moeda <script>filter</script> ',
            'simbolo' => ' Moeda <script>filter</script> ',
            'codigo' => ' Moeda <script>filter</script> ',
            'divisao' => '1.234',
            'fracao' => ' Moeda <script>filter</script> ',
            'formato' => ' Moeda <script>filter</script> ',
        ]);
        $moeda->filter($old_moeda);
        $this->assertEquals($old_moeda, $moeda);
    }

    public function testPublish()
    {
        $moeda = new Moeda();
        $values = $moeda->publish();
        $allowed = [
            'id',
            'nome',
            'simbolo',
            'codigo',
            'divisao',
            'fracao',
            'formato',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $moeda = new Moeda();
        try {
            $moeda->insert();
            $this->fail('Não deveria ter cadastrado a moeda');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'nome',
                    'simbolo',
                    'divisao',
                    'formato',
                ],
                array_keys($e->getErrors())
            );
        }
        $moeda->setNome('Moeda to insert');
        $moeda->setSimbolo('Moeda to insert');
        $moeda->setDivisao(123);
        $moeda->setFormato('Moeda to insert');
        $moeda->insert();
    }

    public function testUpdate()
    {
        $moeda = new Moeda();
        $moeda->setNome('Moeda to update');
        $moeda->setSimbolo('Moeda to update');
        $moeda->setDivisao(123);
        $moeda->setFormato('Moeda to update');
        $moeda->insert();
        $moeda->setNome('Moeda updated');
        $moeda->setSimbolo('Moeda updated');
        $moeda->setCodigo('Moeda updated');
        $moeda->setDivisao(456);
        $moeda->setFracao('Moeda updated');
        $moeda->setFormato('Moeda updated');
        $moeda->update();
        $found_moeda = Moeda::findByID($moeda->getID());
        $this->assertEquals($moeda, $found_moeda);
    }

    public function testDelete()
    {
        $moeda = new Moeda();
        $moeda->setNome('Moeda to delete');
        $moeda->setSimbolo('Moeda to delete');
        $moeda->setDivisao(123);
        $moeda->setFormato('Moeda to delete');
        $moeda->insert();
        $moeda->delete();
        $found_moeda = Moeda::findByID($moeda->getID());
        $this->assertEquals(new Moeda(), $found_moeda);
        $moeda->setID('');
        $this->setExpectedException('\Exception');
        $moeda->delete();
    }

    public function testFind()
    {
        $moeda = new Moeda();
        $moeda->setNome('Moeda find');
        $moeda->setSimbolo('Moeda find');
        $moeda->setDivisao(123);
        $moeda->setFormato('Moeda find');
        $moeda->insert();
        $found_moeda = Moeda::findByID($moeda->getID());
        $this->assertEquals($moeda, $found_moeda);
        $found_moeda->loadByID($moeda->getID());
        $this->assertEquals($moeda, $found_moeda);

        $moeda_sec = new Moeda();
        $moeda_sec->setNome('Moeda find second');
        $moeda_sec->setSimbolo('Moeda find second');
        $moeda_sec->setDivisao(123);
        $moeda_sec->setFormato('Moeda find second');
        $moeda_sec->insert();

        $moedas = Moeda::findAll(['search' => 'Moeda find'], [], 2, 0);
        $this->assertEquals([$moeda, $moeda_sec], $moedas);

        $count = Moeda::count(['search' => 'Moeda find']);
        $this->assertEquals(2, $count);
    }
}
