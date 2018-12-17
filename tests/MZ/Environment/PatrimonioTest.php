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
namespace MZ\Environment;

use MZ\Account\ClienteTest;

class PatrimonioTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid patrimônio
     * @param string $descricao Patrimônio descrição
     * @return Patrimonio
     */
    public static function build($descricao = null)
    {
        $last = Patrimonio::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $cliente = ClienteTest::create();
        $patrimonio = new Patrimonio();
        $patrimonio->setEmpresaID($cliente->getID());
        $patrimonio->setNumero($id + 1000);
        $patrimonio->setDescricao($descricao ?: "Patrimônio {$id}");
        $patrimonio->setQuantidade(2);
        $patrimonio->setAltura(3);
        $patrimonio->setLargura(4);
        $patrimonio->setComprimento(5.6);
        $patrimonio->setEstado(Patrimonio::ESTADO_NOVO);
        $patrimonio->setCusto(12.3);
        $patrimonio->setValor(12.3);
        $patrimonio->setAtivo('Y');
        return $patrimonio;
    }

    /**
     * Create a patrimônio on database
     * @param string $descricao Patrimônio descrição
     * @return Patrimonio
     */
    public static function create($descricao = null)
    {
        $patrimonio = self::build($descricao);
        $patrimonio->insert();
        return $patrimonio;
    }

    public function testFind()
    {
        $patrimonio = self::create();
        $condition = ['descricao' => $patrimonio->getDescricao()];
        $found_patrimonio = Patrimonio::find($condition);
        $this->assertEquals($patrimonio, $found_patrimonio);
        list($found_patrimonio) = Patrimonio::findAll($condition, [], 1);
        $this->assertEquals($patrimonio, $found_patrimonio);
        $this->assertEquals(1, Patrimonio::count($condition));
    }

    public function testAdd()
    {
        $patrimonio = self::build();
        $patrimonio->insert();
        $this->assertTrue($patrimonio->exists());
    }

    public function testUpdate()
    {
        $patrimonio = self::create();
        $patrimonio->update();
        $this->assertTrue($patrimonio->exists());
    }

    public function testDelete()
    {
        $patrimonio = self::create();
        $patrimonio->delete();
        $patrimonio->loadByID();
        $this->assertFalse($patrimonio->exists());
    }
}
