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
namespace MZ\Device;

use MZ\Environment\SetorTest;
use MZ\Exception\ValidationException;

class DispositivoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid dispositivo
     * @param string $nome Dispositivo nome
     * @return Dispositivo
     */
    public static function build($nome = null)
    {
        $last = Dispositivo::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $setor = SetorTest::create();
        $dispositivo = new Dispositivo();
        $dispositivo->setSetorID($setor->getID());
        $dispositivo->setNome($nome ?: "Tablet {$id}");
        $dispositivo->setDescricao($nome ?: "Tablet {$id}");
        $dispositivo->setTipo(Dispositivo::TIPO_TABLET);
        $dispositivo->setSerial("{$id}8sdd7qw549{$id}");
        $testeQtde = Dispositivo::findAll();
        return $dispositivo;
    }

    /**
     * Create a dispositivo on database
     * @param string $nome Dispositivo nome
     * @return Dispositivo
     */
    public static function create($nome = null)
    {
        $dispositivo = self::build($nome);
        $dispositivo->insert();
        $dispositivo->authorize();
        return $dispositivo;
    }

    public function testInsert()
    {
        $dispositivo = self::create();
        $this->assertTrue($dispositivo->exists());
        $dispositivo->delete();
    }

    public function testAddInvalid()
    {
        $teste = Dispositivo::findAll();
        $dispositivo = self::build();
        $dispositivo->setSetorID(null);
        $dispositivo->setNome(null);
        $dispositivo->setTipo(null);
        $dispositivo->setSerial(null);
        try {
            $qtde = app()->getSystem()->getDispositivos();
            $dispositivo->insert();
            $this->fail('Não cadastrar valores nulos');
            $dispositivo->delete();
        } catch (ValidationException $e) {
            $this->assertEquals(['setorid', 'nome', 'tipo', 'serial'], array_keys($e->getErrors()));
        }
    }

    public function testExcederQtdeDispo()
    {
        $qtdeDisp = Dispositivo::count();
        $maxDispo = app()->getSystem()->getDispositivos();
        $dispositivos = [];
        while ($qtdeDisp < $maxDispo) {
            $dispositivos[] = self::create();
            $qtdeDisp++;
        }
        $dispositivo11 = self::build();
        try {
            $dispositivo11->insert();
            $this->fail('Não cadastrar mais dispositivos');
        } catch (ValidationException $e) {
            $this->assertEquals(['limite'], array_keys($e->getErrors()));
        }
        foreach ($dispositivos as $dispositivo) {
            $dispositivo->delete();
        }
    }

    public function testTranslate()
    {
        $teste = Dispositivo::findAll();
        $dispositivo = self::build();
        $dispositivo->setCaixaID(1);
        $dispositivo->insert();

        try {
            $dispositivo->setSerial("f8svjas9jsd89fkkk");
            $dispositivo->insert();
            $this->fail('Não cadastrar fk duplicada');
            $dispositivo->delete();
        } catch (ValidationException $e) {
            $this->assertEquals(['caixaid'], array_keys($e->getErrors()));
        }
        //-------------------------------
        $dispositivo = self::build();
        $dispositivo->setSerial("f8svjas9jsd89f");
        $dispositivo->insert();
        try {
            $dispositivo->setCaixaID(4);
            $dispositivo->insert();
            $this->fail('Não cadastra com fk duplicada');
            $dispositivo->delete();
        } catch (ValidationException $e) {
            $this->assertEquals(['serial'], array_keys($e->getErrors()));
        }
    }

    public function testFinds()
    {
        $disp = self::create();

        $setorFind = $disp->findSetorID();
        $this->assertEquals($disp->getSetorID(), $setorFind->getID());

        $caixaFind = $disp->findCaixaID();
        $this->assertEquals($disp->getCaixaID(), $caixaFind->getID());

        $dispFind = $disp->findByCaixaID($caixaFind->getID());
        $this->assertInstanceOf(get_class($disp), $dispFind);
        $disp->delete();
    }

    public function testTipoOptions()
    {
        $disp = self::create();
        $options = Dispositivo::getTipoOptions($disp->getTipo());
        $this->assertEquals($disp->getTipo(), $options);
        $disp->delete();
    }


    public function testDelete()
    {
        $dispositivo = self::create();
        $dispositivo->delete();
        $dispositivo->loadByID();
        $this->assertFalse($dispositivo->exists());
    }

}
