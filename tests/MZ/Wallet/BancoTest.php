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

class BancoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid banco
     * @param string $razao_social Banco razão social
     * @return Banco
     */
    public static function build($razao_social = null)
    {
        $last = Banco::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $banco = new Banco();
        $banco->setNumero($id + 1000);
        $banco->setRazaoSocial($razao_social ?: "Banco {$id}");
        return $banco;
    }

    /**
     * Create a banco on database
     * @param string $razao_social Banco razão social
     * @return Banco
     */
    public static function create($razao_social = null)
    {
        $banco = self::build($razao_social);
        $banco->insert();
        return $banco;
    }

    public function testFind()
    {
        $banco = self::create();
        $condition = ['razaosocial' => $banco->getRazaoSocial()];
        $found_banco = Banco::find($condition);
        $this->assertEquals($banco, $found_banco);
        list($found_banco) = Banco::findAll($condition, [], 1);
        $this->assertEquals($banco, $found_banco);
        $this->assertEquals(1, Banco::count($condition));
    }

    public function testAdd()
    {
        $banco = self::build();
        $banco->insert();
        $this->assertTrue($banco->exists());
    }

    public function testUpdate()
    {
        $banco = self::create();
        $banco->update();
        $this->assertTrue($banco->exists());
    }

    public function testDelete()
    {
        $banco = self::create();
        $banco->delete();
        $banco->loadByID();
        $this->assertFalse($banco->exists());
    }
}
