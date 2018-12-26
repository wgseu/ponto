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

class GrupoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid grupo
     * @param string $descricao Grupo descrição
     * @return Grupo
     */
    public static function build($descricao = null)
    {
        $last = Grupo::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $produto = ProdutoTest::build();
        $produto->setTipo(Produto::TIPO_PACOTE);
        $produto->insert();
        $grupo = new Grupo();
        $grupo->setProdutoID($produto->getID());
        $grupo->setNome('Nome do grupo');
        $grupo->setDescricao($descricao ?: "Grupo {$id}");
        $grupo->setTipo(Grupo::TIPO_INTEIRO);
        $grupo->setQuantidadeMinima(1);
        $grupo->setQuantidadeMaxima(3);
        $grupo->setFuncao(Grupo::FUNCAO_MINIMO);
        $grupo->setOrdem(123);
        return $grupo;
    }

    /**
     * Create a grupo on database
     * @param string $descricao Grupo descrição
     * @return Grupo
     */
    public static function create($descricao = null)
    {
        $grupo = self::build($descricao);
        $grupo->insert();
        return $grupo;
    }

    public function testFind()
    {
        $grupo = self::create();
        $condition = ['descricao' => $grupo->getDescricao()];
        $found_grupo = Grupo::find($condition);
        $this->assertEquals($grupo, $found_grupo);
        list($found_grupo) = Grupo::findAll($condition, [], 1);
        $this->assertEquals($grupo, $found_grupo);
        $this->assertEquals(1, Grupo::count($condition));
    }

    public function testAdd()
    {
        $grupo = self::build();
        $grupo->insert();
        $this->assertTrue($grupo->exists());
    }

    public function testUpdate()
    {
        $grupo = self::create();
        $grupo->update();
        $this->assertTrue($grupo->exists());
    }

    public function testDelete()
    {
        $grupo = self::create();
        $grupo->delete();
        $grupo->loadByID();
        $this->assertFalse($grupo->exists());
    }
}
