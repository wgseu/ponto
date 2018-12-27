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

use MZ\Util\Date;
use MZ\Provider\FuncaoTest;
use MZ\Provider\PrestadorTest;
use MZ\System\IntegracaoTest;

class HorarioTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid horário
     * @param string $mensagem Horário mensagem
     * @return Horario
     */
    public static function build($mensagem = null)
    {
        $last = Horario::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $horario = new Horario();
        $horario->setMensagem($mensagem);
        $horario->setInicio(Date::MINUTES_PER_DAY + $id * 20);
        $horario->setFim($horario->getInicio() + 10);
        return $horario;
    }

    /**
     * Create a horário on database
     * @param string $mensagem Horário mensagem
     * @return Horario
     */
    public static function create($mensagem = null)
    {
        $horario = self::build($mensagem);
        $horario->insert();
        return $horario;
    }

    public function testFind()
    {
        $mensagem = 'Estamos de férias';
        $horario = self::create($mensagem);
        $condition = ['inicio' => $horario->getInicio(), 'search' => $mensagem];
        $found_horario = Horario::find($condition);
        $this->assertEquals($horario, $found_horario);
        list($found_horario) = Horario::findAll($condition, [], 1);
        $this->assertEquals($horario, $found_horario);
        $this->assertEquals(1, Horario::count($condition));
    }

    public function testAddInvalid()
    {
        $horario = new Horario();
        $horario->setModo('Livre');
        $horario->setFechado('S');
        $this->expectException('\MZ\Exception\ValidationException');
        $horario->insert();
    }

    public function testAdd()
    {
        $horario = self::build();
        $horario->insert();
        $this->assertTrue($horario->exists());
    }

    public function testUpdate()
    {
        $horario = self::create();
        $horario->update();
        $this->assertTrue($horario->exists());
    }

    public function testDelete()
    {
        $horario = self::create();
        $horario->delete();
        $horario->loadByID();
        $this->assertFalse($horario->exists());
    }

    public function testMethods()
    {
        $horario = self::build();
        $horario->setFechado('Y');
        $horario->fromArray($horario);
        $horario->insert();
        $this->assertTrue($horario->isFechado());
        $horario->fromArray(null);
    }

    public function testFindReferenceByID()
    {
        $horario = self::create();
        $this->assertFalse($horario->findFuncaoID()->exists());
        $horario->setFuncaoID(FuncaoTest::create([])->getID());
        $this->assertTrue($horario->findFuncaoID()->exists());
        
        $this->assertFalse($horario->findPrestadorID()->exists());
        $horario->setPrestadorID(PrestadorTest::create()->getID());
        $this->assertTrue($horario->findPrestadorID()->exists());
        
        $this->assertFalse($horario->findIntegracaoID()->exists());
        $horario->setIntegracaoID(IntegracaoTest::create()->getID());
        $this->assertTrue($horario->findIntegracaoID()->exists());
    }

    public function testModoOptions()
    {
        $this->assertInternalType('string', Horario::getModoOptions(Horario::MODO_OPERACAO));
    }

    public function testMultipleSelections()
    {
        $horario = self::build();
        $horario->setFuncaoID(FuncaoTest::create([])->getID());
        $horario->setPrestadorID(PrestadorTest::create()->getID());
        $this->expectException('\MZ\Exception\ValidationException');
        $horario->insert();
    }

    public function testInvalidInterval()
    {
        $horario = self::build();
        $inicio = $horario->getInicio();
        $horario->setInicio($horario->getFim());
        $horario->setFim($inicio);
        $this->expectException('\MZ\Exception\ValidationException');
        $horario->insert();
    }

    public function testInvalidStart()
    {
        $horario = self::build();
        $horario->setInicio(1439);
        $this->expectException('\MZ\Exception\ValidationException');
        $horario->insert();
    }

    public function testInvalidEnd()
    {
        $horario = self::build();
        $horario->setFim(Date::MINUTES_PER_DAY * 8);
        $this->expectException('\MZ\Exception\ValidationException');
        $horario->insert();
    }

    public function testExistingInto()
    {
        $horario = self::create();
        $horario->setInicio($horario->getInicio() - 1);
        $horario->setFim($horario->getFim() + 1);
        $this->expectException('\MZ\Exception\ValidationException');
        $horario->insert();
    }

    public function testExistingBefore()
    {
        $horario = self::create();
        $horario->setInicio($horario->getInicio() - 1);
        $horario->setFim($horario->getFim() - 1);
        $this->expectException('\MZ\Exception\ValidationException');
        $horario->insert();
    }

    public function testExistingAfter()
    {
        $horario = self::create();
        $horario->setInicio($horario->getInicio() + 1);
        $horario->setFim($horario->getFim() + 1);
        $this->expectException('\MZ\Exception\ValidationException');
        $horario->insert();
    }

    public function testExistingExternal()
    {
        $horario = self::create();
        $horario->setInicio($horario->getInicio() + 1);
        $horario->setFim($horario->getFim() - 1);
        $this->expectException('\MZ\Exception\ValidationException');
        $horario->insert();
    }
}
