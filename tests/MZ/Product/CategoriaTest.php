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

    public function testFilter()
    {
        $old_categoria = new Categoria([
            'id' => 1234,
            'categoriaid' => 1234,
            'descricao' => 'Categoria filter',
            'servico' => 'Y',
            'dataatualizacao' => '2016-12-23 12:15:00',
        ]);
        $categoria = new Categoria([
            'id' => 321,
            'categoriaid' => '1.234',
            'descricao' => ' Categoria <script>filter</script> ',
            'servico' => 'Y',
            'dataatualizacao' => '2016-12-23 12:15:00',
        ]);
        $categoria->filter($old_categoria, true);
        $this->assertEquals($old_categoria, $categoria);
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
            'imagemurl',
            'ordem',
            'dataatualizacao',
            'dataarquivado',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $categoria = new Categoria();
        $categoria->setServico(null);
        try {
            $categoria->insert();
            $this->fail('Não deveria ter cadastrado a categoria');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'descricao',
                    'servico',
                ],
                array_keys($e->getErrors())
            );
        }
        $categoria->setServico('Y');
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
        $this->expectException('\MZ\Exception\ValidationException');
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
        $categoria->loadByID();
        $this->assertEquals($categoria, $found_categoria);
    }

    public function testDelete()
    {
        $categoria = new Categoria();
        $categoria->setDescricao('Categoria to delete');
        $categoria->setServico('Y');
        $categoria->setDataAtualizacao('2016-12-20 12:15:00');
        $categoria->insert();
        $categoria->delete();
        $categoria->clean(new Categoria());
        $found_categoria = Categoria::findByID($categoria->getID());
        $this->assertEquals(new Categoria(), $found_categoria);
        $categoria->setID('');
        $this->expectException('\Exception');
        $categoria->delete();
    }

    public function testFind()
    {
        $categoria = new Categoria();
        $categoria->setDescricao('Categoria find');
        $categoria->insert();
        $found_categoria = Categoria::findByID($categoria->getID());
        $this->assertEquals($categoria, $found_categoria);
        $found_categoria = Categoria::findByDescricao($categoria->getDescricao());
        $this->assertEquals($categoria, $found_categoria);
        $found_categoria = Categoria::find(['search' => 'find']);
        $this->assertEquals($categoria, $found_categoria);
        $produto = ProdutoTest::create();
        $produto->setCategoriaID($categoria->getID());
        $produto->update();
        $found_categoria = Categoria::find(['id' => $categoria->getID(), 'disponivel' => 'Y']);
        $this->assertEquals($categoria, $found_categoria);
        $found_categoria = Categoria::find(['id' => $categoria->getID(), 'disponivel' => 'N']);
        $this->assertEquals(new Categoria(), $found_categoria);

        $categoria_sec = new Categoria();
        $categoria_sec->setDescricao('Categoria find second');
        $categoria_sec->setServico('Y');
        $categoria_sec->setDataAtualizacao('2016-12-25 12:15:00');
        $categoria_sec->insert();

        $categorias = Categoria::findAll(['search' => 'Categoria find'], [], 2, 0);
        $this->assertEquals([$categoria, $categoria_sec], $categorias);

        $count = Categoria::count(['search' => 'Categoria find']);
        $this->assertEquals(2, $count);
    }
}
