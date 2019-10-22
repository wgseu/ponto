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

namespace MZ\Wallet;

class MoedaTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid moeda
     * @param string $nome Moeda nome
     * @return Moeda
     */
    public static function build($nome = null)
    {
        $last = Moeda::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $moeda = new Moeda();
        $moeda->setNome($nome ?: "Moeda {$id}");
        $moeda->setSimbolo('$');
        $moeda->setCodigo("C{$id}");
        $moeda->setDivisao(100);
        $moeda->setFormato('$ %s');
        $moeda->setAtiva('Y');
        return $moeda;
    }

    /**
     * Create a moeda on database
     * @param string $nome Moeda nome
     * @return Moeda
     */
    public static function create($nome = null)
    {
        $moeda = self::build($nome);
        $moeda->insert();
        return $moeda;
    }

    public function testFind()
    {
        $moeda = self::create();
        $condition = ['nome' => $moeda->getNome()];
        $found_moeda = Moeda::find($condition);
        $this->assertEquals($moeda, $found_moeda);
        list($found_moeda) = Moeda::findAll($condition, [], 1);
        $this->assertEquals($moeda, $found_moeda);
        $this->assertEquals(1, Moeda::count($condition));
    }

    public function testAdd()
    {
        $moeda = self::build();
        $moeda->insert();
        $this->assertTrue($moeda->exists());
    }

    public function testUpdate()
    {
        $moeda = self::create();
        $moeda->update();
        $this->assertTrue($moeda->exists());
    }

    public function testDelete()
    {
        $moeda = self::create();
        $moeda->delete();
        $moeda->loadByID();
        $this->assertFalse($moeda->exists());
    }
}
