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
        $impressora = new Impressora();
        $impressora->setSetorID($setor->getID());
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

    public function testAdd()
    {
        $impressora = self::build();
        $impressora->insert();
        $this->assertTrue($impressora->exists());
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
