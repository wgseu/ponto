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
namespace MZ\Company;

class HorarioTest extends \MZ\Framework\TestCase
{
    public function testFromArray()
    {
        $old_horario = new Horario([
            'id' => 123,
            'inicio' => 123,
            'fim' => 123,
            'tempoentrega' => 123,
        ]);
        $horario = new Horario();
        $horario->fromArray($old_horario);
        $this->assertEquals($horario, $old_horario);
        $horario->fromArray(null);
        $this->assertEquals($horario, new Horario());
    }

    public function testFilter()
    {
        $old_horario = new Horario([
            'id' => 1234,
            'inicio' => 1234,
            'fim' => 1234,
            'tempoentrega' => 1234,
        ]);
        $horario = new Horario([
            'id' => 321,
            'inicio' => '1.234',
            'fim' => '1.234',
            'tempoentrega' => '1.234',
        ]);
        $horario->filter($old_horario);
        $this->assertEquals($old_horario, $horario);
    }

    public function testPublish()
    {
        $horario = new Horario();
        $values = $horario->publish();
        $allowed = [
            'id',
            'inicio',
            'fim',
            'tempoentrega',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $horario = new Horario();
        try {
            $horario->insert();
            $this->fail('Não deveria ter cadastrado o horário');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'inicio',
                    'fim',
                ],
                array_keys($e->getErrors())
            );
        }
        $horario->setInicio(123);
        $horario->setFim(123);
        $horario->insert();
    }

    public function testUpdate()
    {
        $horario = new Horario();
        $horario->setInicio(123);
        $horario->setFim(123);
        $horario->insert();
        $horario->setInicio(456);
        $horario->setFim(456);
        $horario->setTempoEntrega(456);
        $horario->update();
        $found_horario = Horario::findByID($horario->getID());
        $this->assertEquals($horario, $found_horario);
        $horario->setID('');
        $this->expectException('\Exception');
        $horario->update();
    }

    public function testDelete()
    {
        $horario = new Horario();
        $horario->setInicio(123);
        $horario->setFim(123);
        $horario->insert();
        $horario->delete();
        $horario->clean(new Horario());
        $found_horario = Horario::findByID($horario->getID());
        $this->assertEquals(new Horario(), $found_horario);
        $horario->setID('');
        $this->expectException('\Exception');
        $horario->delete();
    }

    public function testFind()
    {
        $horario = new Horario();
        $horario->setInicio(123654);
        $horario->setFim(123656);
        $horario->insert();
        $found_horario = Horario::find(['id' => $horario->getID()]);
        $this->assertEquals($horario, $found_horario);
        $found_horario = Horario::findByID($horario->getID());
        $this->assertEquals($horario, $found_horario);
        $found_horario->loadByID($horario->getID());
        $this->assertEquals($horario, $found_horario);

        $horario_sec = new Horario();
        $horario_sec->setInicio(123654);
        $horario_sec->setFim(123656);
        $horario_sec->insert();

        $horarios = Horario::findAll(['inicio' => 123654], [], 2, 0);
        $this->assertEquals([$horario, $horario_sec], $horarios);

        $count = Horario::count(['inicio' => 123654]);
        $this->assertEquals(2, $count);
    }
}
