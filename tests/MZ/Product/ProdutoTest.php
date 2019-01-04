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

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;
use MZ\Stock\EstoqueTest;
use MZ\Stock\Estoque;
use MZ\Environment\SetorTest;

class ProdutoTest extends \MZ\Framework\TestCase
{
    /**
     * @param string $descricao descricao do produto
     * @return Produto
     */
    public static function build($descricao = null)
    {
        $categoria = CategoriaTest::create();
        $unidade = UnidadeTest::create();
        $setor = SetorTest::create();
        $produto = new Produto();
        $produto->loadNextCodigo();
        $id = $produto->getCodigo();
        $produto->setCategoriaID($categoria->getID());
        $produto->setUnidadeID($unidade->getID());
        $produto->setSetorPreparoID($setor->getID());
        $produto->setDescricao($descricao ?: "Produto #{$id}");
        $produto->setQuantidadeLimite(10);
        $produto->setQuantidadeMaxima(100);
        $produto->setConteudo(1);
        $produto->setPrecoVenda(3.50);
        $produto->setTipo(Produto::TIPO_PRODUTO);
        $produto->setCobrarServico('Y');
        $produto->setDivisivel('Y');
        $produto->setPesavel('Y');
        $produto->setPerecivel('Y');
        $produto->setTempoPreparo(10);
        $produto->setVisivel('Y');
        $produto->setInterno('N');
        return $produto;
    }

    /**
     * @param string $descricao descricao do produto
     * @return Produto
     */
    public static function create($descricao = null)
    {
        $produto = self::build($descricao);
        $produto->insert();
        return $produto;
    }

    public function testPublish()
    {
        $produto = new Produto();
        $values = $produto->publish(app()->auth->provider);
        $allowed = [
            'id',
            'codigo',
            'categoriaid',
            'unidadeid',
            'setorestoqueid',
            'setorpreparoid',
            'tributacaoid',
            'descricao',
            'abreviacao',
            'detalhes',
            'quantidadelimite',
            'quantidademaxima',
            'conteudo',
            'precovenda',
            'custoproducao',
            'tipo',
            'cobrarservico',
            'divisivel',
            'pesavel',
            'perecivel',
            'tempopreparo',
            'visivel',
            'interno',
            'avaliacao',
            'imagemurl',
            'dataatualizacao',
            'dataarquivado',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testAppList()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRODUTOS]);
        $estoque = EstoqueTest::create();
        $produto = $estoque->findProdutoID();
        $campos = [
            'id',
            'codigo',
            'categoriaid',
            'supercategoriaid',
            'descricao',
            'abreviacao',
            'detalhes',
            'precovenda',
            'tipo',
            'conteudo',
            'divisivel',
            'dataatualizacao',
            'imagemurl',
            'avaliacao',
            'pesavel',
            'visivel',
            // extras
            'estoque',
            'categoria',
            'unidade',
        ];
        $categoria = $produto->findCategoriaID();
        $item = $produto->publish(app()->auth->provider);
        $item['estoque'] = $estoque->getQuantidade();
        $item['categoria'] = $categoria->getDescricao();
        $item['supercategoriaid'] = $categoria->getCategoriaID();
        $item['unidade'] = $produto->findUnidadeID()->getSigla();
        $expected = [
            'status' => 'ok',
            'produtos' => [
                array_intersect_key($item, array_flip($campos)),
            ],
        ];
        app()->getAuthentication()->logout();
        $result = $this->get('/app/produto/listar', ['categoria' => $produto->getCategoriaID()]);
        // undo product available
        $estoque->delete();
        // end undo product available
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAppFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRODUTOS]);
        $estoque = EstoqueTest::create();
        $produto = $estoque->findProdutoID();
        $campos = [
            'id',
            'codigo',
            'categoriaid',
            'supercategoriaid',
            'descricao',
            'abreviacao',
            'detalhes',
            'precovenda',
            'tipo',
            'conteudo',
            'divisivel',
            'dataatualizacao',
            'imagemurl',
            'avaliacao',
            'pesavel',
            'visivel',
            // extras
            'estoque',
            'categoria',
            'unidade',
        ];
        $categoria = $produto->findCategoriaID();
        $item = $produto->publish(app()->auth->provider);
        $item['estoque'] = $estoque->getQuantidade();
        $item['categoria'] = $categoria->getDescricao();
        $item['supercategoriaid'] = $categoria->getCategoriaID();
        $item['unidade'] = $produto->findUnidadeID()->getSigla();
        $expected = [
            'status' => 'ok',
            'produtos' => [
                array_intersect_key($item, array_flip($campos)),
            ],
        ];
        app()->getAuthentication()->logout();
        $result = $this->get('/app/produto/procurar', ['busca' => $produto->getDescricao()]);
        // undo product available
        $estoque->delete();
        // end undo product available
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testLoadNextCodigo()
    {
        $produto = self::build();
        $produto->setCodigo($produto->getCodigo() + 1);
        $produto->loadNextCodigo();
        $produto->setDescricao("Produto #{$produto->getCodigo()}");
        $produto->insert();
        $old_codigo = $produto->getCodigo();
        $produto = self::build();
        $produto->setCodigo($old_codigo + 10);
        $produto->setDescricao("Produto #{$produto->getCodigo()}");
        $produto->insert();
        $produto->loadNextCodigo();
        $this->assertEquals($old_codigo + 11, $produto->getCodigo());
        $produto = self::build();
        $produto->setCodigo($old_codigo);
        $produto->loadNextCodigo();
        $this->assertEquals($old_codigo + 1, $produto->getCodigo());
        $produto->setDescricao("Produto #{$produto->getCodigo()}");
        $produto->insert();
        $produto = self::build();
        $produto->setCodigo($old_codigo);
        $produto->loadNextCodigo();
        $this->assertEquals($old_codigo + 2, $produto->getCodigo());
    }
}
