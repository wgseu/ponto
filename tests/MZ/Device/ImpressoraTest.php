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
use MZ\Device\DispositivoTest;
use MZ\Exception\ValidationException;

class ImpressoraTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid impressora
     * @param string $descricao Impressora descrição
     * @return Impressora
     */
    public static function build($descricao = null)
    {
        $last = Impressora::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $setor = SetorTest::create();
        $dispositivo = DispositivoTest::create();
        $impressora = new Impressora();
        $impressora->setSetorID($setor->getID());
        $impressora->setDispositivoID($dispositivo->getID());
        $impressora->setNome('Nome da impressora');
        $impressora->setDescricao($descricao ?: "Impressora {$id}");
        $impressora->setModo(Impressora::MODO_TERMINAL);
        $impressora->setOpcoes(123);
        $impressora->setColunas(48);
        $impressora->setAvanco(3);
        return $impressora;
    }

    /**
     * Create a impressora on database
     * @param string $descricao Impressora descrição
     * @return Impressora
     */
    public static function create($descricao = null)
    {
        $impressora = self::build($descricao);
        $impressora->insert();
        return $impressora;
    }

    public function testFind()
    {
        $impressora = self::create();
        $condition = ['descricao' => $impressora->getDescricao()];
        $found_impressora = Impressora::find($condition);
        $this->assertEquals($impressora, $found_impressora);
        list($found_impressora) = Impressora::findAll($condition, [], 1);
        $this->assertEquals($impressora, $found_impressora);
        $this->assertEquals(1, Impressora::count($condition));
    }

    public function testFinds()
    {
        $impressora = self::create();

        $setor = $impressora->findSetorID();
        $this->assertEquals($impressora->getSetorID(), $setor->getID());

        $dispositivo = $impressora->findDispositivoID();
        $this->assertEquals($impressora->getDispositivoID(), $dispositivo->getID());

        $impreBySetor = $impressora->findBySetorIDDispositivoIDModo($setor->getID(), $dispositivo->getID(), $impressora->getModo());
        $this->assertInstanceOf(get_class($impressora), $impreBySetor);

        $impreByDispo = $impressora->findByDispositivoIDDescricao($dispositivo->getID(), $impressora->getDescricao());
        $this->assertInstanceOf(get_class($impressora), $impreByDispo);
    }

    public function testAdd()
    {
        $impressora = self::build();
        $impressora->insert();
        $this->assertTrue($impressora->exists());
    }

    public function testAddInvalid()
    {
        $impressora = self::build();
        $impressora->setSetorID(null);
        $impressora->setNome(null);
        $impressora->setDescricao(null);
        $impressora->setModo(null);
        $impressora->setOpcoes(null);
        $impressora->setColunas(null);
        $impressora->setAvanco(null);
        try {
            $impressora->insert();
            $this->fail('Não cadastrar valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['setorid', 'nome', 'descricao', 'modo', 'opcoes', 'colunas', 'avanco'], 
            array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $impressora = self::build();
        $impressora->setDescricao('Teste');
        $impressora->setSetorID(1);
        $impressora->setDispositivoID(1);
        $impressora->setModo(Impressora::MODO_TERMINAL);
        $this->expectException('\Exception');
        $impressora->insert();

    }

    public function testGetModoOptions()
    {
        $impressora = self::create();
        $options = Impressora::getModoOptions($impressora->getModo());
        $this->assertEquals($impressora->getModo(), $options);
    }

    public function testGetModelo()
    {
        $impressora = self::build();
        $impressora->setDriver('Thermal');
        $impressora->insert();

        $modelo = $impressora->getModelo();
        $this->assertEquals('CMP-20', $modelo);
        //--------
        $impressora = self::build();
        $impressora->setDriver('Dataregis');
        $impressora->insert();

        $modelo = $impressora->getModelo();
        $this->assertEquals('VOX', $modelo);
        //------------
        $impressora = self::build();
        $impressora->setDriver('Bematech');
        $impressora->insert();

        $modelo = $impressora->getModelo();
        $this->assertEquals('MP-4200 TH', $modelo);
        //---------
        $impressora = self::build();
        $impressora->setDriver('Daruma');
        $impressora->insert();

        $modelo = $impressora->getModelo();
        $this->assertEquals('DR700', $modelo);
        //---------
        $impressora = self::build();
        $impressora->setDriver('Diebold');
        $impressora->insert();

        $modelo = $impressora->getModelo();
        $this->assertEquals('IM453', $modelo);
        //---------
        $impressora = self::build();
        $impressora->setDriver('PertoPrinter');
        $impressora->insert();

        $modelo = $impressora->getModelo();
        $this->assertEquals('PertoPrinter', $modelo);
    }

    public function testUpdate()
    {
        $impressora = self::create();
        $impressora->update();
        $this->assertTrue($impressora->exists());
    }

    public function testDelete()
    {
        $impressora = self::create();
        $impressora->delete();
        $impressora->loadByID();
        $this->assertFalse($impressora->exists());
    }
}
