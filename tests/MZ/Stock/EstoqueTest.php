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
namespace MZ\Stock;

use MZ\Product\ProdutoTest;
use MZ\Product\Produto;
use MZ\Environment\SetorTest;
use MZ\Provider\PrestadorTest;
use MZ\Exception\ValidationException;
use MZ\Product\ComposicaoTest;
use MZ\Product\Composicao;

class EstoqueTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid estoque
     * @param \MZ\Product\Produto $produto product to insert on stock
     * @return Estoque
     */
    public static function build($produto = null)
    {
        $last = Estoque::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $produto = $produto ?: ProdutoTest::create();
        $setor = SetorTest::create();
        $prestador = PrestadorTest::create();
        $estoque = new Estoque();
        $estoque->setProdutoID($produto->getID());
        $estoque->setSetorID($setor->getID());
        $estoque->setPrestadorID($prestador->getID());
        $estoque->setTipoMovimento(Estoque::TIPO_MOVIMENTO_ENTRADA);
        $estoque->setQuantidade(10);
        $estoque->setPrecoCompra(12.3);
        return $estoque;
    }

    /**
     * Create a estoque on database
     * @param \MZ\Product\Produto $produto product to insert on stock
     * @return Estoque
     */
    public static function create($produto = null)
    {
        $estoque = self::build();
        $estoque->insert();
        return $estoque;
    }

    public function testPublish()
    {
        $estoque = new Estoque();
        $values = $estoque->publish(app()->auth->provider);
        $allowed = [
            'id',
            'produtoid',
            'requisitoid',
            'transacaoid',
            'entradaid',
            'fornecedorid',
            'setorid',
            'prestadorid',
            'tipomovimento',
            'quantidade',
            'precocompra',
            'lote',
            'datafabricacao',
            'datavencimento',
            'detalhes',
            'cancelado',
            'datamovimento',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testFinds()
    {
        $estoque = self::create();

        $requisito = $estoque->findRequisitoID();
        $this->assertEquals($estoque->getRequisitoID(), $requisito->getID());

        $transacao = $estoque->findTransacaoID();
        $this->assertEquals($estoque->getTransacaoID(), $transacao->getID());

        $entrada = $estoque->findEntradaID();
        $this->assertEquals($estoque->getEntradaID(), $entrada->getID());

        $fornecedor = $estoque->findFornecedorID();
        $this->assertEquals($estoque->getFornecedorID(), $fornecedor->getID());

        $setor = $estoque->findSetorID();
        $this->assertEquals($estoque->getSetorID(), $setor->getID());

        $prestador = $estoque->findPrestadorID();
        $this->assertEquals($estoque->getPrestadorID(), $prestador->getID());

        $est = $estoque->findAvailableEntry();
        $this->assertInstanceOf(get_class($estoque), $est);
    }

    public function testAddInvalid()
    {
        $estoque = self::build();
        $estoque->setProdutoID(null);
        $estoque->setSetorID(null);
        $estoque->setPrestadorID(null);
        $estoque->setTipoMovimento('Teste');
        $estoque->setQuantidade(null);
        $estoque->setPrecoCompra(null);
        $estoque->setCancelado('E');
        try {
            $estoque->insert();
            $this->fail('Não cadastrar valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['produtoid', 'setorid', 'prestadorid', 'tipomovimento', 'quantidade',
            'precocompra', 'cancelado'], array_keys($e->getErrors()));
        }
        //----------------------------
        $produto = ProdutoTest::build();
        $produto->setTipo('Composicao');
        $produto->insert();
        $estoque = self::build();
        $estoque->setQuantidade(0);
        $estoque->setProdutoID($produto->getID());
        try {
            $estoque->insert();
            $this->fail('Tipo diferente de produto');
        } catch (ValidationException $e) {
            $this->assertEquals(['produtoid', 'quantidade'], array_keys($e->getErrors()));
        }
        //-----------------------------
        $produto = ProdutoTest::build();
        $produto->setDivisivel('N');
        $produto->insert();
        $estoque = self::build();
        $estoque->setQuantidade(2.5);
        $estoque->setProdutoID($produto->getID());
        try {
            $estoque->insert();
            $this->fail('O produto não é divisivel');
        } catch (ValidationException $e) {
            $this->assertEquals(['produtoid'], array_keys($e->getErrors()));
        }
        //---------------------------
        $estoque = self::build();
        $estoque->setTipoMovimento(Estoque::TIPO_MOVIMENTO_ENTRADA);
        $estoque->setQuantidade(-9);
        try {
            $estoque->insert();
            $this->fail('Não dar entrada no estoque com valor negativo');
        } catch (ValidationException $e) {
            $this->assertEquals(['quantidade'], array_keys($e->getErrors()));
        }
        //---------------------------
        $estoque = self::build();
        $estoque->setTransacaoID(1);
        $estoque->setQuantidade(10);
        try {
            $estoque->insert();
            $this->fail('Venda não pode add produto ao estoque');
        } catch (ValidationException $e) {
            $this->assertEquals(['quantidade'], array_keys($e->getErrors()));
        }
        //-----------------
        $estoque = self::build();
        $estoque->setCancelado('Y');
        try {
            $estoque->insert();
            $this->fail('Estoque já cancelado');
        } catch (ValidationException $e) {
            $this->assertEquals(['cancelado'], array_keys($e->getErrors()));
        }
    }

    public function testUpdateInvalid()
    {
        $estoque = self::build();
        $estoque->insert();
        $estoque->setCancelado('Y');
        $estoque->update();
        try {
            $estoque->update();
            $this->fail('Estoque já cancelado');
        } catch (ValidationException $e) {
            $this->assertEquals(['cancelado'], array_keys($e->getErrors()));
        }
        //---------------
    }

    public function testGetMovimentoOptions()
    {
        $estoque = self::create();
        $options = Estoque::getTipoMovimentoOptions($estoque->getTipoMovimento());
        $this->assertEquals($estoque->getTipoMovimento(), $options);
    }

    public function testGetUltimoPrecoCompra()
    {
        $estoque = self::create();
        $estoque1 = self::create();

        $valor = Estoque::getUltimoPrecoCompra($estoque1->getProdutoID());
        $this->assertEquals($estoque1->getPrecoCompra(), $valor);
    }

    public function testCancelar()
    {
        $estoque = self::create();
        $estoque->cancelar();
        $this->assertTrue($estoque->isCancelado());
    }

    public function testRetirar()
    {
        //------Setor de estoque onde retirar o produto
        $setorEstoque = SetorTest::build();
        $setorEstoque->setNome('Setor Estoque');
        $setorEstoque->insert();
        //-----

        //-----Retirar do Estoque TIPO_PRODUTO
        $produto = ProdutoTest::build();
        $produto->setTipo(Produto::TIPO_PRODUTO);
        $produto->setAbreviacao('Teste');
        $produto->setSetorEstoqueID($setorEstoque->getID());
        $produto->insert();
        //--- Cadastrar mais desse produto Teste
        $estoqueTeste = self::build();
        $estoqueTeste->setProdutoID($produto->getID());
        $estoqueTeste->setQuantidade(30.);
        $estoqueTeste->setSetorID($setorEstoque->getID());
        $estoqueTeste->insert();
        //------------
        $testeRet = $estoqueTeste->retirar([]);
        $this->assertFalse($estoqueTeste->isCancelado());
    }

}
