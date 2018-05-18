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
 * @author  Francimar Alves <mazinsw@gmail.com>
 */
namespace MZ\Product;

class CategoriaTest extends \PHPUnit_Framework_TestCase
{
    public function testFromArray()
    {
        $old_categoria = new Categoria([
            'id' => 123,
            'categoriaid' => 123,
            'descricao' => 'Categoria',
            'servico' => 'Y',
            'imagem' => "\x5\x0\x3",
            'dataatualizacao' => '2016-12-05 12:15:00',
        ]);
        $categoria = new Categoria();
        $categoria->fromArray($old_categoria);
        $this->assertEquals($categoria, $old_categoria);
        $categoria->fromArray(null);
        $new_categoria = new Categoria();
        // evita falha temporal pois a data de atualização sempre pega a data e hora atual
        $new_categoria->setDataAtualizacao($categoria->getDataAtualizacao());
        $this->assertEquals($categoria, $new_categoria);
    }

    public function testPublish()
    {
        $categoria = new Categoria();
        $values = $categoria->publish();
        $allowed = [
            'id',
            'categoriaid',
            'descricao',
            'servico',
            'imagem',
            'dataatualizacao',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $categoria = new Categoria();
        $categoria->setDescricao('Variedades');
        $categoria->insert();
        $found_categoria = Categoria::findByID($categoria->getID());
        $this->assertEquals($categoria, $found_categoria);
        $subcategoria = new Categoria();
        $subcategoria->setCategoriaID($categoria->getID());
        $subcategoria->setDescricao('Variedade específica');
        $subcategoria->insert();
        $found_subcategoria = Categoria::findByID($subcategoria->getID());
        $this->assertEquals($subcategoria, $found_subcategoria);
        $subsubcategoria = new Categoria();
        $subsubcategoria->setCategoriaID($subcategoria->getID());
        $subsubcategoria->setDescricao('Variedade mais específica');
        $this->setExpectedException('\MZ\Exception\ValidationException');
        try {
            $subsubcategoria->insert();
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(['categoriaid'], array_keys($e->getErrors()));
            throw $e;
        }
    }

    public function testUpdate()
    {
        $categoria = new Categoria();
        $categoria->setDescricao('Nova caetgoria');
        $categoria->insert();
        $found_categoria = Categoria::findByID($categoria->getID());
        $found_categoria->setDescricao('Nova categoria');
        $found_categoria->update();
        $categoria->loadByID($categoria->getID());
        $this->assertEquals($categoria, $found_categoria);
    }

    public function testFind()
    {
        $categoria = new Categoria();
        $categoria->setDescricao('Outras categorias');
        $categoria->insert();
        $found_categoria = Categoria::find(['search' => 'outras']);
        $this->assertEquals($categoria, $found_categoria);
        $unidade = new Unidade();
        $unidade->setNome('Nome da unidade');
        $unidade->setSigla('UND');
        $unidade->insert();
        $produto = new Produto();
        $produto->setDescricao('Produto da categoria');
        $produto->setCategoriaID($categoria->getID());
        $produto->setUnidadeID($unidade->getID());
        $produto->setTipo(Produto::TIPO_PRODUTO);
        $produto->setVisivel('Y');
        $produto->insert();
        $found_categoria = Categoria::find(['id' => $categoria->getID(), 'disponivel' => 'Y']);
        $this->assertEquals($categoria, $found_categoria);
        $found_categoria = Categoria::find(['id' => $categoria->getID(), 'disponivel' => 'N']);
        $this->assertEquals(new Categoria(), $found_categoria);
    }
}
