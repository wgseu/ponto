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


class CarteiraTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid carteira
     * @param string $descricao Carteira descrição
     * @return Carteira
     */
    public static function build($descricao = null)
    {
        $last = Carteira::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $carteira = new Carteira();
        $carteira->setTipo(Carteira::TIPO_LOCAL);
        $carteira->setDescricao($descricao ?: "Carteira {$id}");
        $carteira->setTransacao(10);
        $carteira->setAtiva('Y');
        return $carteira;
    }

    /**
     * Create a carteira on database
     * @param string $descricao Carteira descrição
     * @return Carteira
     */
    public static function create($descricao = null)
    {
        $carteira = self::build($descricao);
        $carteira->insert();
        return $carteira;
    }

    public function testFind()
    {
        $carteira = self::create();
        $condition = ['descricao' => $carteira->getDescricao()];
        $found_carteira = Carteira::find($condition);
        $this->assertEquals($carteira, $found_carteira);
        list($found_carteira) = Carteira::findAll($condition, [], 1);
        $this->assertEquals($carteira, $found_carteira);
        $this->assertEquals(1, Carteira::count($condition));
    }

    public function testAdd()
    {
        $carteira = self::build();
        $carteira->insert();
        $this->assertTrue($carteira->exists());
    }

    public function testUpdate()
    {
        $carteira = self::create();
        $carteira->update();
        $this->assertTrue($carteira->exists());
    }

    public function testDelete()
    {
        $carteira = self::create();
        $carteira->delete();
        $carteira->loadByID();
        $this->assertFalse($carteira->exists());
    }
}
