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
namespace MZ\System;

use MZ\System\FuncionalidadeTest;
use MZ\Exception\ValidationException;

class PermissaoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid permissão
     * @param string $descricao Permissão descrição
     * @return Permissao
     */
    public static function build($descricao = null)
    {
        $last = Permissao::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $funcionalidade = FuncionalidadeTest::create();
        $permissao = new Permissao();
        $permissao->setFuncionalidadeID($funcionalidade->getID());
        $permissao->setNome("Permissão {$id}");
        $permissao->setDescricao($descricao ?: "Permissão {$id}");
        return $permissao;
    }

    /**
     * Create a permissão on database
     * @param string $descricao Permissão descrição
     * @return Permissao
     */
    public static function create($descricao = null)
    {
        $permissao = self::build($descricao);
        $permissao->insert();
        return $permissao;
    }

    public function testFind()
    {
        $permissao = self::create();
        $condition = ['descricao' => $permissao->getDescricao()];
        $found_permissao = Permissao::find($condition);
        $this->assertEquals($permissao, $found_permissao);
        list($found_permissao) = Permissao::findAll($condition, [], 1);
        $this->assertEquals($permissao, $found_permissao);
        $this->assertEquals(1, Permissao::count($condition));
    }

    public function testFinds()
    {
        $permissao = self::create();

        $funcionalidade = $permissao->findFuncionalidadeID();
        $this->assertEquals($permissao->getFuncionalidadeID(), $funcionalidade->getID());

        $modulo = $permissao->findModuloID();
        $this->assertEquals($permissao->getModuloID(), $modulo->getID());
    }

    public function testAdd()
    {
        $permissao = self::build();
        $permissao->insert();
        $this->assertTrue($permissao->exists());
    }

    public function testAddInvalid()
    {
        $permissao = self::build();
        $permissao->setFuncionalidadeID(null);
        $permissao->setNome(null);
        $permissao->setDescricao(null);
        try {
            $permissao->insert();
            $this->fail('Não cadastrar valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['funcionalidadeid', 'nome', 'descricao'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $permissao = self::create();
        try {
            $permissao->insert();
            $this->fail('fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['nome'], array_keys($e->getErrors()));
        }
    }

    public function testUpdate()
    {
        $permissao = self::create();
        $permissao->update();
        $this->assertTrue($permissao->exists());
    }

    public function testDelete()
    {
        $permissao = self::create();
        $permissao->delete();
        $permissao->loadByID();
        $this->assertFalse($permissao->exists());
    }
}
