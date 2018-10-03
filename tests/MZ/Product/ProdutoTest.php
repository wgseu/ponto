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

class ProdutoTest extends \MZ\Framework\TestCase
{
    public static function build($descricao = null)
    {
        $last = Produto::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $categoria = CategoriaTest::create();
        $unidade = UnidadeTest::create();
        $produto = new Produto();
        $produto->setCodigo($id);
        $produto->setCategoriaID($categoria->getID());
        $produto->setUnidadeID($unidade->getID());
        $produto->setDescricao($descricao ?: "Produto #{$id}");
        $produto->setQuantidadeLimite(0);
        $produto->setQuantidadeMaxima(0);
        $produto->setConteudo(1);
        $produto->setPrecoVenda(0);
        $produto->setTipo(Produto::TIPO_PRODUTO);
        $produto->setCobrarServico('Y');
        $produto->setDivisivel('Y');
        $produto->setPesavel('Y');
        $produto->setPerecivel('Y');
        $produto->setTempoPreparo(0);
        $produto->setVisivel('Y');
        $produto->setInterno('N');
        return $produto;
    }

    public static function create($descricao = null)
    {
        $produto = self::build($descricao);
        $produto->insert();
        return $produto;
    }

    public function testPublish()
    {
        $produto = new Produto();
        $values = $produto->publish();
        $allowed = [
            'id',
            'codigo',
            'codigobarras',
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
        $estoque = EstoqueTest::create();
        $produto = $estoque->findProdutoID();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRODUTOS]);
        $campos = [
            'id',
            'categoriaid',
            'descricao',
            'detalhes',
            'precovenda',
            'tipo',
            'conteudo',
            'divisivel',
            'dataatualizacao',
            'imagemurl',
            'avaliacao',
            // extras
            'estoque',
            'categoria',
            'unidade',
        ];
        $item = $produto->publish();
        $item['estoque'] = $estoque->getQuantidade();
        $item['categoria'] = $produto->findCategoriaID()->getDescricao();
        $item['unidade'] = $produto->findUnidadeID()->getSigla();
        $expected = [
            'status' => 'ok',
            'produtos' => [
                array_intersect_key($item, array_flip($campos)),
            ],
        ];
        app()->getAuthentication()->logout();
        $result = $this->get('/app/produto/listar', ['categoria' => $produto->getCategoriaID()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAppFind()
    {
        $estoque = EstoqueTest::create();
        $produto = $estoque->findProdutoID();
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRODUTOS]);
        $campos = [
            'id',
            'categoriaid',
            'descricao',
            'detalhes',
            'precovenda',
            'tipo',
            'conteudo',
            'divisivel',
            'dataatualizacao',
            'imagemurl',
            'avaliacao',
            // extras
            'estoque',
            'categoria',
            'unidade',
        ];
        $item = $produto->publish();
        $item['estoque'] = $estoque->getQuantidade();
        $item['categoria'] = $produto->findCategoriaID()->getDescricao();
        $item['unidade'] = $produto->findUnidadeID()->getSigla();
        $expected = [
            'status' => 'ok',
            'produtos' => [
                array_intersect_key($item, array_flip($campos)),
            ],
        ];
        app()->getAuthentication()->logout();
        $result = $this->get('/app/produto/procurar', ['busca' => $produto->getDescricao()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }
}
