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
namespace MZ\Sale;

class ComandaTest extends \PHPUnit_Framework_TestCase
{
    public function testFromArray()
    {
        $old_comanda = new Comanda([
            'id' => 123,
            'nome' => 'Comanda',
            'ativa' => 'Y',
        ]);
        $comanda = new Comanda();
        $comanda->fromArray($old_comanda);
        $this->assertEquals($comanda, $old_comanda);
        $comanda->fromArray(null);
        $this->assertEquals($comanda, new Comanda());
    }

    public function testFilter()
    {
        $old_comanda = new Comanda([
            'id' => 1234,
            'nome' => 'Comanda filter',
            'ativa' => 'Y',
        ]);
        $comanda = new Comanda([
            'id' => 1234,
            'nome' => ' Comanda <script>filter</script> ',
            'ativa' => 'Y',
        ]);
        $comanda->filter($old_comanda);
        $this->assertEquals($old_comanda, $comanda);
    }

    public function testPublish()
    {
        $comanda = new Comanda();
        $values = $comanda->publish();
        $allowed = [
            'id',
            'nome',
            'ativa',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $comanda = new Comanda();
        $comanda->setAtiva(null);
        try {
            $comanda->insert();
            $this->fail('Não deveria ter cadastrado a comanda');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'nome',
                    'ativa',
                ],
                array_keys($e->getErrors())
            );
        }
        $comanda->setID(Comanda::getNextID());
        $comanda->setNome('Comanda to insert ' . $comanda->getID());
        $comanda->setAtiva('Y');
        $comanda->insert();
    }

    public function testUpdate()
    {
        $comanda = new Comanda();
        $comanda->setNome('Comanda to update');
        $comanda->setAtiva('N');
        $comanda->insert();
        $comanda->setNome('Comanda updated');
        $comanda->setAtiva('N');
        $comanda->update();
        $found_comanda = Comanda::findByID($comanda->getID());
        $this->assertEquals($comanda, $found_comanda);
    }

    public function testDelete()
    {
        $comanda = new Comanda();
        $comanda->setNome('Comanda to delete');
        $comanda->setAtiva('Y');
        $comanda->insert();
        $comanda->delete();
        $found_comanda = Comanda::findByID($comanda->getID());
        $this->assertEquals(new Comanda(), $found_comanda);
        $comanda->setID('');
        $this->setExpectedException('\Exception');
        $comanda->delete();
    }

    public function testFind()
    {
        $comanda = new Comanda();
        $comanda->setNome('Comanda find');
        $comanda->setAtiva('Y');
        $comanda->insert();
        $found_comanda = Comanda::findByID($comanda->getID());
        $this->assertEquals($comanda, $found_comanda);
        $found_comanda->loadByID($comanda->getID());
        $this->assertEquals($comanda, $found_comanda);
        $found_comanda = Comanda::findByNome($comanda->getNome());
        $this->assertEquals($comanda, $found_comanda);
        $found_comanda->loadByNome($comanda->getNome());
        $this->assertEquals($comanda, $found_comanda);

        $comanda_sec = new Comanda();
        $comanda_sec->setNome('Comanda find second');
        $comanda_sec->setAtiva('Y');
        $comanda_sec->insert();

        $comandas = Comanda::findAll(['search' => 'Comanda find'], [], 2, 0);
        $this->assertEquals([$comanda, $comanda_sec], $comandas);

        $count = Comanda::count(['search' => 'Comanda find']);
        $this->assertEquals(2, $count);
    }
}
