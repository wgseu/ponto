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

use MZ\Location\CidadeTest;

class BairroTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid bairro
     * @param string $nome Bairro nome
     * @return Bairro
     */
    public static function build($nome = null)
    {
        $last = Bairro::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $cidade = CidadeTest::create();
        $bairro = new Bairro();
        $bairro->setCidadeID($cidade->getID());
        $bairro->setNome($nome ?: "Bairro {$id}");
        $bairro->setValorEntrega(12.3);
        $bairro->setMapeado('Y');
        return $bairro;
    }

    /**
     * Create a bairro on database
     * @param string $nome Bairro nome
     * @return Bairro
     */
    public static function create($nome = null)
    {
        $bairro = self::build($nome);
        $bairro->insert();
        return $bairro;
    }

    public function testFind()
    {
        $bairro = self::create();
        $condition = ['nome' => $bairro->getNome()];
        $found_bairro = Bairro::find($condition);
        $this->assertEquals($bairro, $found_bairro);
        list($found_bairro) = Bairro::findAll($condition, [], 1);
        $this->assertEquals($bairro, $found_bairro);
        $this->assertEquals(1, Bairro::count($condition));
    }

    public function testAdd()
    {
        $bairro = self::build();
        $bairro->insert();
        $this->assertTrue($bairro->exists());
    }

    public function testUpdate()
    {
        $bairro = self::create();
        $bairro->update();
        $this->assertTrue($bairro->exists());
    }

    public function testDelete()
    {
        $bairro = self::create();
        $bairro->delete();
        $bairro->loadByID();
        $this->assertFalse($bairro->exists());
    }
}
