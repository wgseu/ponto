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

use MZ\Exception\ValidationException;

class EventoTest extends \MZ\Framework\TestCase
{

        /**
     * Build a valid evento
     * @return Evento
     */
    public static function build()
    {
        $last = Evento::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $nota = NotaTest::create();
        $evento = new Evento();
        $evento->setNotaID($nota->getID());
        $evento->setEstado(Evento::ESTADO_ABERTO);
        $evento->setMensagem('Mensagem do evento');
        $evento->setCodigo('Código do evento');
        return $evento;
    }

    /**
     * Create a evento on database
     * @return Evento
     */
    public static function create()
    {
        $evento = self::build();
        $evento->insert();
        return $evento;
    }

    public function testFind()
    {
        $evento = self::create();
        $condition = ['notaid' => $evento->getNotaID()];
        $found_evento = Evento::find($condition);
        $this->assertEquals($evento, $found_evento);
        list($found_evento) = Evento::findAll($condition, [], 1);
        $this->assertEquals($evento, $found_evento);
        $this->assertEquals(1, Evento::count($condition));
    }

    public function testFindNota()
    {
        $evento = self::create();
        $nota = $evento->findNotaID();

        $this->assertEquals($evento->getNotaID(), $nota->getID());
    }

    public function testAdd()
    {
        $evento = self::build();
        $evento->insert();
        $this->assertTrue($evento->exists());
    }

    public function testAddInvalid()
    {
        $evento = self::build();
        $evento->setNotaID(null);
        $evento->setEstado('Teste');
        $evento->setMensagem(null);
        $evento->setCodigo(null);
        try {
            $evento->insert();
            $this->fail('Valores invalidos');
        } catch (ValidationException $e) {
            $this->assertEquals(
                ['notaid', 'estado', 'mensagem', 'codigo'],
                array_keys($e->getErrors())
            );
        }
    }

    public function testUpdate()
    {
        $evento = self::create();
        $evento->update();
        $this->assertTrue($evento->exists());
    }

    public function testGetEstado()
    {
        $evento = new Evento(['estado' => Evento::ESTADO_ABERTO]);
        $options = Evento::getEstadoOptions();
        $this->assertEquals(Evento::getEstadoOptions($evento->getEstado()), $options[$evento->getEstado()]);
    }

    public function testDelete()
    {
        $evento = self::create();
        $evento->delete();
        $evento->loadByID();
        $this->assertFalse($evento->exists());
    }

    public function testPublish()
    {
        $evento = new Evento();
        $values = $evento->publish(app()->auth->provider);
        $allowed = [
            'id',
            'notaid',
            'estado',
            'mensagem',
            'codigo',
            'datacriacao',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testFromArray()
    {
        $oldEvento = new Evento([
            'id' => 1,
            'notaid' => 2,
            'estado' => Evento::ESTADO_ABERTO,
            'mensagem' => 'Mensagem de teste',
            'codigo' => 'Cod test'
        ]);
        $evento = new Evento();
        $evento->fromArray($oldEvento);
        $this->assertEquals($evento, $oldEvento);

        $evento->fromArray(null);
        $this->assertEquals($evento, new Evento());
    }
}
