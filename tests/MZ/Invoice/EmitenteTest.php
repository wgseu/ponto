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

use \MZ\Exception\ValidationException;

class EmitenteTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid emitente
     * @return Emitente
     */
    public static function build()
    {
        $last = Emitente::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $regime = RegimeTest::create();
        $emitente = new Emitente();
        $emitente->setRegimeID($regime->getID());
        $emitente->setAmbiente(Emitente::AMBIENTE_HOMOLOGACAO);
        $emitente->setCSC('CSC do emitente');
        $emitente->setToken('Token do emitente');
        $emitente->setChavePrivada('Chave privada do emitente');
        $emitente->setChavePublica('Chave pública do emitente');
        $emitente->setDataExpiracao('2016-12-25 12:15:00');
        return $emitente;
    }

    /**
     * Create a emitente on database
     * @return Emitente
     */
    public static function create()
    {
        $emitente = self::build();
        $emitente->insert();
        return $emitente;
    }

    public function testFind()
    {
        $emitente = self::create();
        $condition = ['contadorid' => $emitente->getContadorID()];
        $found_emitente = Emitente::find($condition);
        $this->assertEquals($emitente, $found_emitente);
        list($found_emitente) = Emitente::findAll($condition, [], 1);
        $this->assertEquals($emitente, $found_emitente);
        $this->assertEquals(1, Emitente::count($condition));
        $emitente->delete();
    }

    public function testFinds()
    {
        $emitente = self::build();
        $emitente->setContadorID(1);
        $emitente->insert();

        $contador = $emitente->findContadorID();
        $this->assertEquals($emitente->getContadorID(), $contador->getID());

        $regime = $emitente->findRegimeID();
        $this->assertEquals($emitente->getRegimeID(), $regime->getID());
        $emitente->delete();
    }

    public function testAdd()
    {
        $emitente = self::build();
        $emitente->insert();
        $this->assertTrue($emitente->exists());
        $emitente->delete();
    }

    public function testAddInvalid()
    {
        $emitente = self::build();
        $emitente->setRegimeID(null);
        $emitente->setAmbiente('Ambiente teste');
        $emitente->setCSC(null);
        $emitente->setToken(null);
        $emitente->setChavePrivada(null);
        $emitente->setChavePublica(null);
        $emitente->setDataExpiracao(null);
        try {
            $emitente->insert();
            $this->fail('Valores inválidos');
        } catch (ValidationException $e) {
            $this->assertEquals(
                ['regimeid', 'ambiente', 'csc', 'token', 'chaveprivada', 'chavepublica', 'dataexpiracao'],
                array_keys($e->getErrors())
            );
        }
    }

    public function testUpdate()
    {
        $emitente = self::create();
        $emitente->update();
        $this->assertTrue($emitente->exists());
        $emitente->delete();
    }

    public function testDelete()
    {
        $emitente = self::create();
        $emitente->delete();
        $emitente->loadByID();
        $this->assertFalse($emitente->exists());
    }

    public function testGetAmbiente()
    {
        $emitente = new Emitente(['ambiente' => Emitente::AMBIENTE_HOMOLOGACAO]);
        $options = Emitente::getAmbienteOptions();
        $this->assertEquals(Emitente::getAmbienteOptions($emitente->getAmbiente()), $options[$emitente->getAmbiente()]);
    }

    public function testPublish()
    {
        $emitente = new Emitente();
        $values = $emitente->publish(app()->auth->provider);
        $allowed = [
            'id',
            'contadorid',
            'regimeid',
            'ambiente',
            'csc',
            'token',
            'ibpt',
            'chaveprivada',
            'chavepublica',
            'dataexpiracao',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testFromArray()
    {
        $old_emitente = new Emitente([
            'id' => 1,
            'contadorid' => 2,
            'regimeid' => 2,
            'ambiente' => Emitente::AMBIENTE_HOMOLOGACAO,
            'csc' => 'CSC do emitente',
            'token' => 'Token do emitente',
            'chaveprivada' => 'Chave privada do emitente',
            'chavepublica' => 'Chave publica do emitente',
            'dataexpiracao' => '2016-12-25 12:15:00'
        ]);
        $emitente = new Emitente();
        $emitente->fromArray($old_emitente);
        $this->assertEquals($emitente, $old_emitente);

        $emitente->fromArray(null);
        $newEmitente = new Emitente();
        $this->assertEquals($emitente, $newEmitente);
    }
}
