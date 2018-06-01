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
namespace MZ\Session;

class SessaoTest extends \PHPUnit_Framework_TestCase
{
    public function testFromArray()
    {
        $old_sessao = new Sessao([
            'id' => 123,
            'datainicio' => '2016-12-25 12:15:00',
            'datatermino' => '2016-12-25 12:15:00',
            'aberta' => 'Y',
        ]);
        $sessao = new Sessao();
        $sessao->fromArray($old_sessao);
        $this->assertEquals($sessao, $old_sessao);
        $sessao->fromArray(null);
        $this->assertEquals($sessao, new Sessao());
    }

    public function testFilter()
    {
        $old_sessao = new Sessao([
            'id' => 1234,
            'datainicio' => '2016-12-23 12:15:00',
            'datatermino' => '2016-12-23 12:15:00',
            'aberta' => 'Y',
        ]);
        $sessao = new Sessao([
            'id' => 321,
            'datainicio' => '23/12/2016 12:15',
            'datatermino' => '23/12/2016 12:15',
            'aberta' => 'Y',
        ]);
        $sessao->filter($old_sessao);
        $this->assertEquals($old_sessao, $sessao);
    }

    public function testPublish()
    {
        $sessao = new Sessao();
        $values = $sessao->publish();
        $allowed = [
            'id',
            'datainicio',
            'datatermino',
            'aberta',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $sessao = new Sessao();
        $sessao->setDataInicio(null);
        try {
            $sessao->insert();
            $this->fail('Não deveria ter cadastrado a sessão');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'datainicio',
                    'aberta',
                ],
                array_keys($e->getErrors())
            );
        }
        $sessao->setDataInicio('2016-12-25 12:15:00');
        $sessao->setAberta('Y');
        $sessao->insert();
        // tenta abrir duas sessões
        try {
            $outra_sessao = new Sessao();
            $outra_sessao->setDataInicio('2016-12-25 12:15:00');
            $outra_sessao->setAberta('Y');
            $outra_sessao->insert();
            $this->fail('Não deveria ter cadastrado outra sessão');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'aberta',
                ],
                array_keys($e->getErrors())
            );
        }
        // fecha para não interferir nos outros testes
        $sessao->setAberta('N');
        $sessao->update();
    }

    public function testUpdate()
    {
        $sessao = new Sessao();
        $sessao->setDataInicio('2016-12-26 12:15:00');
        $sessao->setAberta('Y');
        $sessao->insert();
        $sessao->setDataInicio('2016-12-25 14:15:00');
        $sessao->setDataTermino('2016-12-25 14:15:00');
        $sessao->setAberta('Y');
        $sessao->update();
        $found_sessao = Sessao::findByID($sessao->getID());
        $this->assertEquals($sessao, $found_sessao);
        // fecha para não interferir nos outros testes
        $sessao->setAberta('N');
        $sessao->update();
    }

    public function testDelete()
    {
        $sessao = new Sessao();
        $sessao->setDataInicio('2016-12-20 12:15:00');
        $sessao->setAberta('Y');
        $sessao->insert();
        $sessao->delete();
        $sessao->clean(new Sessao());
        $found_sessao = Sessao::findByID($sessao->getID());
        $this->assertEquals(new Sessao(), $found_sessao);
        $sessao->setID('');
        $this->setExpectedException('\Exception');
        $sessao->delete();
    }

    public function testFind()
    {
        $sessao = new Sessao();
        $sessao->setDataInicio('2016-11-25 12:15:00');
        $sessao->setAberta('Y');
        $sessao->insert();
        // fecha para não interferir nos outros testes
        $sessao->setAberta('N');
        $sessao->update();
        $found_sessao = Sessao::findByID($sessao->getID());
        $this->assertEquals($sessao, $found_sessao);
        $found_sessao->loadByID($sessao->getID());
        $this->assertEquals($sessao, $found_sessao);

        $sessao_sec = new Sessao();
        $sessao_sec->setDataInicio('2016-11-25 12:15:00');
        $sessao_sec->setAberta('Y');
        $sessao_sec->insert();
        // fecha para não interferir nos outros testes
        $sessao_sec->setAberta('N');
        $sessao_sec->update();

        $sessoes = Sessao::findAll(['datainicio' => '2016-11-25 12:15:00'], [], 2, 0);
        $this->assertEquals([$sessao, $sessao_sec], $sessoes);

        $count = Sessao::count(['datainicio' => '2016-11-25 12:15:00']);
        $this->assertEquals(2, $count);
    }
}
