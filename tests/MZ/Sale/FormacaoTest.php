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
namespace MZ\Sale;

use MZ\Sale\ItemTest;
use MZ\Session\MovimentacaoTest;
use MZ\Exception\ValidationException;
use MZ\Sale\PedidoTest;
use MZ\Session\Caixa;
use MZ\Session\Movimentacao;
use MZ\Product\ComposicaoTest;

class FormacaoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid formação
     * @return Formacao
     */
    public static function build()
    {
        $last = Formacao::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $item = ItemTest::build();
        $composicao = ComposicaoTest::create();
        $item->setProdutoID($composicao->getComposicaoID());
        $item->insert();
        $formacao = new Formacao();
        $formacao->setItemID($item->getID());
        $formacao->setComposicaoID($composicao->getID());
        $formacao->setTipo(Formacao::TIPO_COMPOSICAO);
        $formacao->setQuantidade(12.3);
        return $formacao;
    }

    /**
     * Create a formação on database
     * @return Formacao
     */
    public static function create()
    {
        $movimentacoes = Movimentacao::findAll(['aberta' => 'Y']);
        $formacao = self::build();
        $formacao->insert();
        return $formacao;
    }

    public function testFind()
    {
        $movimentacao = MovimentacaoTest::create();
        $formacao = self::create();
        $condition = ['composicaoid' => $formacao->getComposicaoID()];
        $found_formacao = Formacao::find($condition);
        $this->assertEquals($formacao, $found_formacao);

        list($found_formacao) = Formacao::findAll($condition, [], 1);
        $this->assertEquals($formacao, $found_formacao);
        $this->assertEquals(1, Formacao::count($condition));

        $item = $formacao->findItemID();
        $pedido = $item->findPedidoID();
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testAddInvalid()
    {
        $movimentacao = MovimentacaoTest::create();
        $formacao = self::build();
        $item = $formacao->findItemID();
        $formacao->setItemID(null);
        $formacao->setTipo('Teste');
        $formacao->setQuantidade(null);
        try {
            $formacao->insert();
            $this->fail('Não cadastrar valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['itemid', 'tipo', 'quantidade'], array_keys($e->getErrors()));
        }
        $pedido = $item->findPedidoID();
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testFinds()
    {
        $movimentacao = MovimentacaoTest::create();
        $formacao = self::create();
        
        $item = $formacao->findItemID();
        $this->assertEquals($formacao->getItemID(), $item->getID());

        $pacote = $formacao->findPacoteID();
        $this->assertEquals($formacao->getPacoteID(), $pacote->getID());

        $composicao = $formacao->findComposicaoID();
        $this->assertEquals($formacao->getComposicaoID(), $composicao->getID());

        $found_formacao = $formacao->findByItemIDPacoteID($item->getID(), $pacote->getID());
        $this->assertInstanceOf(get_class($formacao), $found_formacao);
        $pedido = $item->findPedidoID();
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testGetOptions()
    {
        $formacao = new Formacao(['tipo' => Formacao::TIPO_COMPOSICAO]);
        $options = Formacao::getTipoOptions();
        $this->assertEquals(
            Formacao::getTipoOptions($formacao->getTipo()),
            $options[$formacao->getTipo()]
        );
    }
}
