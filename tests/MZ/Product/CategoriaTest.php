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

use MZ\Stock\EstoqueTest;
use MZ\Product\ProdutoTest;
use MZ\Exception\ValidationException;

class CategoriaTest extends \MZ\Framework\TestCase
{
    public static function build($descricao = null)
    {
        $last = Categoria::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $categoria = new Categoria();
        $categoria->setDescricao($descricao ?: "Categoria #{$id}");
        $categoria->setServico('Y');
        $categoria->setOrdem(0);
        return $categoria;
    }

    public static function create($descricao = null)
    {
        $categoria = self::build($descricao);
        $categoria->insert();
        return $categoria;
    }

    public function testAppListar()
    {
        app()->getAuthentication()->logout();
        $result = $this->get('/app/categoria/listar');
        $expected = [
            'status' => 'ok',
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAddInvalid()
    {
        $categoria = self::build();
        $categoria->setDescricao(null);
        $categoria->setServico(null);
        $categoria->setOrdem(null);
        try {
            $categoria->insert();
            $this->fail('Não cadastrar com valores nulo');
        } catch (ValidationException $e) {
            $this->assertEquals(['descricao', 'servico', 'ordem'], array_keys($e->getErrors()));
        }
        //-----------------------------------
        //Tenta cadastra uma subcategoria com uma categoria que não existe
        $categoria = self::build();
        $categoria->setDescricao('Pizza');
        $categoria->insert();

        //criar a subCategoria Açai P
        $subCategoria = self::build();
        $subCategoria->setDescricao('Acai P');
        //a subC estara "associada" com a Categoria 54
        $subCategoria->setCategoriaID(54);
        try {
            $subCategoria->insert();
            $this->fail('A categoria pai não existe');
        } catch (ValidationException $e) {
            $this->assertEquals(['categoriaid'], array_keys($e->getErrors()));
        }
        //-----------------------------------
        $categoria = self::build();
        $categoria->setDescricao('Pizza vários níveis');
        $categoria->insert();

        //criar a subCategoria pizza P
        $subCategoria = self::build();
        $subCategoria->setDescricao('Pizza P');
        $subCategoria->setCategoriaID($categoria->getID());
        $subCategoria->insert();

        //criar uma subCategoria da subCategoria Pizza P
        $subSubCategoria = self::build();
        $subSubCategoria->setDescricao('Pizza P teste');
        $subSubCategoria->setCategoriaID($subCategoria->getID());
        try {
            $subSubCategoria->insert();
            $this->fail('Não cadastrar mais de 1 nível');
        } catch (ValidationException $e) {
            $this->assertEquals(['categoriaid'], array_keys($e->getErrors()));
        }
    }

    public function testUpdateInvalid()
    {
        $categoria_pai = self::build();
        $categoria_pai->setDescricao('Pizza Teste');
        $categoria_pai->insert();

        //criando outra categoria 
        $categoria = self::build();
        $categoria->setID($categoria_pai->getID());
        $categoria->setCategoriaID($categoria_pai->getID());
        $categoria->setDescricao('Pizza G');

        try {
            $categoria->update();
            $this->fail('Erro');
        } catch (ValidationException $e) {
            $this->assertEquals(['categoriaid'], array_keys($e->getErrors()));
        }
    }

    public function testIsAvailable()
    {
        $produto = ProdutoTest::build();
        $categoria = self::create();
        $produto->setCategoriaID($categoria->getID());
        $produto->insert();
        $this->assertEquals($categoria->getID(), $produto->getCategoriaID());
        $categoria_disponivel = $categoria->isAvailable();
        $this->assertTrue($categoria_disponivel);
    }

    public function testTranslate()
    {
        $categoria = self::create();

        try {
            $categoria->insert();
            $this->fail('Não cadastrar descricao repetida');
        } catch (ValidationException $e) {
            $this->assertEquals(['descricao'], array_keys($e->getErrors()));
        }
    }

    public function testFind()
    {
        $categoria = self::create();

        $categoriaFind = $categoria->findCategoriaID();
        $this->assertEquals($categoria->getCategoriaID(), $categoriaFind->getID());

        $descricao = $categoria->findByDescricao($categoria->getDescricao());
        $this->assertInstanceOf(get_class($categoria), $descricao);
    }

    public function testCount()
    {
        $categoria = self::create();

        $count = $categoria->count(['id' => $categoria->getID()]);
        $this->assertEquals(1, $count);
    }

    public function testMakeImg()
    {
        $categoria = new Categoria();
        $this->assertEquals('/static/img/categoria.png', $categoria->makeImagemURL(true));
        $categoria->setImagemURL('imagem.png');
        $this->assertEquals('/static/img/categoria/imagem.png', $categoria->makeImagemURL());
    }

    public function testClean()
    {
        $old_categoria = new Categoria();
        $old_categoria->setImagemURL('categoriafake.png');
        $categoria = new Categoria();
        $categoria->setImagemURL('categoriainexistente.png');
        $categoria->clean($old_categoria);
        $this->assertEquals($old_categoria, $categoria);
    }
}
