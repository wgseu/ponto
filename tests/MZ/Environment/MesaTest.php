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
namespace MZ\Environment;

class MesaTest extends \PHPUnit_Framework_TestCase
{
    public function testFromArray()
    {
        $old_mesa = new Mesa([
            'id' => 123,
            'nome' => 'Mesa',
            'ativa' => 'Y',
        ]);
        $mesa = new Mesa();
        $mesa->fromArray($old_mesa);
        $this->assertEquals($mesa, $old_mesa);
        $mesa->fromArray(null);
        $this->assertEquals($mesa, new Mesa());
    }

    public function testFilter()
    {
        $old_mesa = new Mesa([
            'id' => 1234,
            'nome' => 'Mesa filter',
            'ativa' => 'Y',
        ]);
        $mesa = new Mesa([
            'id' => 1234,
            'nome' => ' Mesa <script>filter</script> ',
            'ativa' => 'Y',
        ]);
        $mesa->filter($old_mesa);
        $this->assertEquals($old_mesa, $mesa);
    }

    public function testPublish()
    {
        $mesa = new Mesa();
        $values = $mesa->publish();
        $allowed = [
            'id',
            'nome',
            'ativa',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $mesa = new Mesa();
        $mesa->setAtiva(null);
        try {
            $mesa->insert();
            $this->fail('Não deveria ter cadastrado a mesa');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'nome',
                    'ativa',
                ],
                array_keys($e->getErrors())
            );
        }
        $mesa->loadNextID();
        $mesa->setNome('Mesa to insert ' . $mesa->getID());
        $mesa->setAtiva('Y');
        $mesa->insert();
    }

    public function testUpdate()
    {
        $mesa = new Mesa();
        $mesa->setNome('Mesa to update');
        $mesa->setAtiva('N');
        $mesa->insert();
        $mesa->setNome('Mesa updated');
        $mesa->setAtiva('N');
        $mesa->update();
        $found_mesa = Mesa::findByID($mesa->getID());
        $this->assertEquals($mesa, $found_mesa);
    }

    public function testDelete()
    {
        $mesa = new Mesa();
        $mesa->setNome('Mesa to delete');
        $mesa->setAtiva('Y');
        $mesa->insert();
        $mesa->delete();
        $found_mesa = Mesa::findByID($mesa->getID());
        $this->assertEquals(new Mesa(), $found_mesa);
        $mesa->setID('');
        $this->setExpectedException('\Exception');
        $mesa->delete();
    }

    public function testFind()
    {
        $mesa = new Mesa();
        $mesa->setNome('Mesa find');
        $mesa->setAtiva('Y');
        $mesa->insert();
        $found_mesa = Mesa::findByID($mesa->getID());
        $this->assertEquals($mesa, $found_mesa);
        $found_mesa->loadByID($mesa->getID());
        $this->assertEquals($mesa, $found_mesa);
        $found_mesa = Mesa::findByNome($mesa->getNome());
        $this->assertEquals($mesa, $found_mesa);
        $found_mesa->loadByNome($mesa->getNome());
        $this->assertEquals($mesa, $found_mesa);

        $mesa_sec = new Mesa();
        $mesa_sec->setNome('Mesa find second');
        $mesa_sec->setAtiva('Y');
        $mesa_sec->insert();

        $mesas = Mesa::findAll(['search' => 'Mesa find'], [], 2, 0);
        $this->assertEquals([$mesa, $mesa_sec], $mesas);

        $count = Mesa::count(['search' => 'Mesa find']);
        $this->assertEquals(2, $count);
    }
}
