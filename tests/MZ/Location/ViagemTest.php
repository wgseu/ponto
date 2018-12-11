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
namespace MZ\Location;

use MZ\Provider\PrestadorTest;

class ViagemTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid viagem
     * @return Viagem
     */
    public static function build()
    {
        $last = Viagem::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $prestador = PrestadorTest::create();
        $viagem = new Viagem();
        $viagem->setResponsavelID($prestador->getID());
        $viagem->setDataSaida('2016-12-25 12:15:00');
        return $viagem;
    }

    /**
     * Create a viagem on database
     * @return Viagem
     */
    public static function create()
    {
        $viagem = self::build();
        $viagem->insert();
        return $viagem;
    }

    public function testFind()
    {
        $viagem = self::create();
        $condition = ['responsavelid' => $viagem->getResponsavelID()];
        $found_viagem = Viagem::find($condition);
        $this->assertEquals($viagem, $found_viagem);
        list($found_viagem) = Viagem::findAll($condition, [], 1);
        $this->assertEquals($viagem, $found_viagem);
        $this->assertEquals(1, Viagem::count($condition));
    }

    public function testAdd()
    {
        $viagem = self::build();
        $viagem->insert();
        $this->assertTrue($viagem->exists());
    }

    public function testUpdate()
    {
        $viagem = self::create();
        $viagem->update();
        $this->assertTrue($viagem->exists());
    }

    public function testDelete()
    {
        $viagem = self::create();
        $viagem->delete();
        $viagem->loadByID();
        $this->assertFalse($viagem->exists());
    }
}
