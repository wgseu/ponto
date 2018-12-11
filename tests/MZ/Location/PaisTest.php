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

use MZ\Wallet\MoedaTest;

class PaisTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid país
     * @param string $nome País nome
     * @return Pais
     */
    public static function build($nome = null)
    {
        $last = Pais::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $moeda = MoedaTest::create();
        $pais = new Pais();
        $pais->setNome($nome ?: "País {$id}");
        $pais->setSigla("P{$id}");
        $pais->setCodigo("C{$id}");
        $pais->setMoedaID($moeda->getID());
        $pais->setIdioma('pt-BR');
        return $pais;
    }

    /**
     * Create a país on database
     * @param string $nome País nome
     * @return Pais
     */
    public static function create($nome = null)
    {
        $pais = self::build($nome);
        $pais->insert();
        return $pais;
    }

    public function testFind()
    {
        $pais = self::create();
        $condition = ['nome' => $pais->getNome()];
        $found_pais = Pais::find($condition);
        $this->assertEquals($pais, $found_pais);
        list($found_pais) = Pais::findAll($condition, [], 1);
        $this->assertEquals($pais, $found_pais);
        $this->assertEquals(1, Pais::count($condition));
    }

    public function testAdd()
    {
        $pais = self::build();
        $pais->insert();
        $this->assertTrue($pais->exists());
    }

    public function testUpdate()
    {
        $pais = self::create();
        $pais->update();
        $this->assertTrue($pais->exists());
    }

    public function testDelete()
    {
        $pais = self::create();
        $pais->delete();
        $pais->loadByID();
        $this->assertFalse($pais->exists());
    }
}
