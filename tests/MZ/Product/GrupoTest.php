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
use MZ\Exception\ValidationException;

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
        $grupo->delete();
    }

    public function testFinds()
    {
        $grupo = self::create();

        $produtoIDDescricao = $grupo->findByProdutoIDDescricao($grupo->getProdutoID(), $grupo->getDescricao());
        $this->assertInstanceOf(get_class($grupo), $produtoIDDescricao);
        //----------------------------
        $produtoIDNome = $grupo->findByProdutoIDNome($grupo->getProdutoID(), $grupo->getNome());
        $this->assertInstanceOf(get_class($grupo), $produtoIDNome);
        $grupo->delete();
    }

    public function testAdd()
    {
        $grupo = self::build();
        $grupo->insert();
        $this->assertTrue($grupo->exists());
        $grupo->delete();
    }

    public function testAddInvalid()
    {
        $grupo = self::build();
        $grupo->setProdutoID(null);
        $grupo->setNome(null);
        $grupo->setDescricao(null);
        $grupo->setTipo(null);
        $grupo->setQuantidadeMinima(null);
        $grupo->setQuantidadeMaxima(null);
        $grupo->setFuncao(null);
        $grupo->setOrdem(null);
        try {
            $grupo->insert();
            $this->fail('Não cadastrar com valores nulos');
            $grupo->delete();
        } catch (ValidationException $e) {
            $this->assertEquals(['produtoid', 'nome', 'descricao', 'tipo', 'quantidademinima', 'quantidademaxima', 'funcao', 'ordem'], array_keys($e->getErrors()));
        }

        $grupo = self::build();
        $produto = ProdutoTest::build();
        $produto->setTipo(Produto::TIPO_COMPOSICAO);
        $produto->insert();
        $grupo->setProdutoID($produto->getID());
        try {
            $grupo->insert();
            $this->fail('Produto não é um pacote');
            $grupo->delete();
        } catch (ValidationException $e) {
            $this->assertEquals(['produtoid'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $grupo = self::build();
        $grupo->setNome('Teste');
        $grupo->insert();

        try {
            $grupo->insert();
            $this->fail('Não duplicar produto id e o nome do grupo');
        } catch (ValidationException $e) {
            $this->assertEquals(['produtoid', 'nome'], array_keys($e->getErrors()));
        }

        $grupo = self::build();
        $grupo->setDescricao('Teste');
        $grupo->insert();

        try {
            $grupo->setNome('Outra coisa');
            $grupo->insert();
            $this->fail('Não duplicar produto id e descricao do grupo');
        } catch (ValidationException $e) {
            $this->assertEquals(['produtoid', 'descricao'], array_keys($e->getErrors()));
        }
    }

    public function testGetFuncaoOptions()
    {
        $grupo = self::create();
        $options = Grupo::getFuncaoOptions($grupo->getFuncao());
        // $this->assertEquals($grupo->getFuncao(), $options);
        $this->assertEquals('Mínimo', $options);
        $grupo->delete();
    }

    public function testGetTipoOptions()
    {
        $grupo = self::create();
        $options = Grupo::getTipoOptions($grupo->getTipo());
        $this->assertEquals($grupo->getTipo(), $options);
        $grupo->delete();
    }

    public function testUpdate()
    {
        $grupo = self::create();
        $grupo->update();
        $this->assertTrue($grupo->exists());
        $grupo->delete();
    }

    public function testDelete()
    {
        $grupo = self::create();
        $grupo->delete();
        $grupo->loadByID();
        $this->assertFalse($grupo->exists());
    }

}
