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
use MZ\Product\PacoteTest;
use MZ\Exception\ValidationException;

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

    public function testAddInvalid()
    {
        $produto = self::build();
        $produto->setCodigo(null);
        $produto->setCategoriaID(null);
        $produto->setUnidadeID(null);
        $produto->setDescricao(null);
        $produto->setQuantidadeLimite(null);
        $produto->setQuantidadeMaxima(null);
        $produto->setConteudo(null);
        $produto->setPrecoVenda(null);
        $produto->setTempoPreparo(null);
        try {
            $produto->insert();
            $this->fail('Não cadastrar com valores nulos');
            $produto->delete();
        } catch (ValidationException $e) {
            $this->assertEquals(['codigo', 'categoriaid', 'unidadeid', 'descricao', 'quantidadelimite',
            'quantidademaxima', 'conteudo', 'precovenda', 'tempopreparo'], array_keys($e->getErrors()));
        }
        //--------------------
        $produto = self::build();
        $produto->setQuantidadeLimite(-1);
        $produto->setQuantidadeMaxima(-1);
        $produto->setCustoProducao(-1);
        $produto->setPrecoVenda(-1);
        $produto->setTempoPreparo(-1);
        try {
            $produto->insert();
            $this->fail('Valores inválidos');
            $produto->delete();
        } catch (ValidationException $e) {
            $this->assertEquals(['quantidadelimite', 'quantidademaxima', 'precovenda', 'custoproducao', 'tempopreparo'], array_keys($e->getErrors()));
        }
        //----------------------------
        $produto = self::build();
        $produto->setConteudo(0);
        try {
            $produto->insert();
            $this->fail('Conteudo do produto não pode ser ZERO');
            $produto->delete();
        } catch (ValidationException $e) {
            $this->assertEquals(['conteudo'], array_keys($e->getErrors()));
        }
        //----------------------------
        $unidade = UnidadeTest::build();
        $unidade->setSigla("un");
        // $unidade->setSigla(Unidade::SIGLA_UNITARIA);
        $unidade->insert();
        $produto = self::build();
        $produto->setConteudo(5);
        $produto->setUnidadeID($unidade->getID());
        try {
            $produto->insert();
            $this->fail('');
        } catch (ValidationException $e) {
            $this->assertEquals(['conteudo'], array_keys($e->getErrors()));
        }
        //----------------------------
        $produto = self::build();
        $produto->setTipo('Tipo inválido');
        $produto->setCobrarServico('E');
        $produto->setDivisivel('E');
        $produto->setPesavel('E');
        $produto->setPerecivel('E');
        $produto->setVisivel('E');
        $produto->setInterno('E');
        try {
            $produto->insert();
            $this->fail('');
        } catch (ValidationException $e) {
            $this->assertEquals(['tipo', 'cobrarservico', 'divisivel', 'pesavel', 'perecivel', 'visivel', 'interno' ], array_keys($e->getErrors()));
        }
    }

    public function testFinds()
    {
        $produto = self::create();

        $estoque = $produto->findSetorEstoqueID();
        $this->assertEquals($produto->getSetorEstoqueID(), $estoque->getID());

        $preparo = $produto->findSetorPreparoID();
        $this->assertEquals($produto->getSetorPreparoID(), $preparo->getID());

        $tributacao = $produto->findTributacaoID();
        $this->assertEquals($produto->getTributacaoID(), $tributacao->getID());

        $prodByDesc = $produto->findByDescricao($produto->getDescricao());
        $this->assertInstanceOf(get_class($produto), $prodByDesc);

        $prodByCod = $produto->findByCodigo($produto->getCodigo());
        $this->assertInstanceOf(get_class($produto), $prodByCod);
        $produto->delete();
    }

    public function testGetAbreviado()
    {
        $produto = self::build();
        $produto->setAbreviacao("");
        $produto->insert();
        $desc = $produto->getAbreviado();
        $this->assertEquals($produto->getDescricao(), $desc);
        //----
        $produto1 = self::build();
        $produto1->setAbreviacao('P');
        $produto1->insert();
        $desc1 = $produto1->getAbreviado();
        $this->assertEquals($produto1->getAbreviacao(), $desc1);
    }

    public function testGetEstoque()
    {
        //quando informa primeiro parametro busca todo estoque daquele produto
        //quando informado o segundo parametro ele busca o estoque por setor
        $produto = self::build();
        $produto->setSetorPreparoID(2);
        $produto->insert();
        $estoque = EstoqueTest::build();
        $estoque->setProdutoID($produto->getID());
        $estoque->setQuantidade(6.);
        $estoque->insert();
        $qtdeestoq = $produto->getEstoque();
        $this->assertEquals($estoque->getQuantidade(), $qtdeestoq);
        $qtdeSetorPreparo = $produto->getEstoque($produto->getSetorPreparoID());
        $this->assertEquals(0, $qtdeSetorPreparo);
    }

    public function testGetTipoOptions()
    {
        $produto = self::create();
        $options = Produto::getTipoOptions($produto->getTipo());
        $this->assertEquals($produto->getTipo(), $options);
        $produto->delete();
    }

    public function testMakeImg()
    {
        $produto = new Produto();
        $this->assertEquals('/static/img/produto.png', $produto->makeImagemURL(true));
        $produto->setImagemURL('imagem.png');
        $this->assertEquals('/static/img/produto/imagem.png', $produto->makeImagemURL());
    }

    public function testClean()
    {
        $old = new Produto();
        $old->setImagemURL('prodinvalido.png');
        $prod = new Produto();
        $prod->setImagemURL('prodinvalido1.png');
        $prod->clean($old);
        $this->assertEquals($old, $prod);
    }

    public function testTranslate()
    {
        $produto = self::create();
        try {
            $produto->setCodigo("2345678");
            $produto->insert();
            $this->fail('Fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['descricao'], array_keys($e->getErrors()));
        }
        //-------------
        $produto = self::create();
        try {
            $produto->insert();
            $this->fail('Fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['codigo'], array_keys($e->getErrors()));
        }
    }
}
