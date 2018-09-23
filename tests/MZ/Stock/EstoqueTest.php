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
use MZ\Environment\SetorTest;
use MZ\Provider\PrestadorTest;

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
        $values = $estoque->publish();
        $allowed = [
            'id',
            'produtoid',
            'transacaoid',
            'entradaid',
            'fornecedorid',
            'setorid',
            'funcionarioid',
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
}
