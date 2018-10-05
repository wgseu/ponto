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
        $estoque = EstoqueTest::create();
        $produto = $estoque->findProdutoID();
        $categoria = $produto->findCategoriaID();
        $result = $this->get('/app/categoria/listar');
        // undo product available
        $estoque->delete();
        // end undo product available
        $keys = \array_flip([
            'id',
            'descricao',
            'imagemurl',
            'dataatualizacao',
        ]);
        $item = \array_intersect_key($categoria->publish(), $keys);
        if (isset($result['categorias'])) {
            $result['categorias'] = \array_map(function ($item) use ($keys) {
                return \array_intersect_key($item, $keys);
            }, $result['categorias']);
        }
        $expected = [
            'status' => 'ok',
            'categorias' => [ $item, ]
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }
}
