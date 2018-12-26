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
namespace MZ\Product;

use MZ\Product\ProdutoTest;
use MZ\Product\GrupoTest;

class PacoteTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid pacote
     * @return Pacote
     */
    public static function build()
    {
        $last = Pacote::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $produto = ProdutoTest::create();
        $grupo = GrupoTest::create();
        $pacote = new Pacote();
        $pacote->setPacoteID($grupo->getProdutoID());
        $pacote->setProdutoID($produto->getID());
        $pacote->setGrupoID($grupo->getID());
        $pacote->setValor(12.3);
        return $pacote;
    }

    /**
     * Create a pacote on database
     * @return Pacote
     */
    public static function create()
    {
        $pacote = self::build();
        $pacote->insert();
        return $pacote;
    }

    public function testFind()
    {
        $pacote = self::create();
        $condition = ['grupoid' => $pacote->getGrupoID(), 'produtoid' => $pacote->getProdutoID()];
        $found_pacote = Pacote::find($condition);
        $this->assertEquals($pacote, $found_pacote);
        list($found_pacote) = Pacote::findAll($condition, [], 1);
        $this->assertEquals($pacote, $found_pacote);
        $this->assertEquals(1, Pacote::count($condition));
    }

    public function testAdd()
    {
        $pacote = self::build();
        $pacote->insert();
        $this->assertTrue($pacote->exists());
    }

    public function testUpdate()
    {
        $pacote = self::create();
        $pacote->update();
        $this->assertTrue($pacote->exists());
    }

    public function testDelete()
    {
        $pacote = self::create();
        $pacote->delete();
        $pacote->loadByID();
        $this->assertFalse($pacote->exists());
    }

    public function testInsertBlankFields()
    {
        $pacote = new Pacote();
        $this->expectException('\Exception');
        $pacote->insert();
    }
}
