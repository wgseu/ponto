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
        $item = ItemTest::create();
        $formacao = new Formacao();
        $formacao->setItemID($item->getID());
        $formacao->setTipo(Formacao::TIPO_PACOTE);
        $formacao->setQuantidade(12.3);
        return $formacao;
    }

    /**
     * Create a formação on database
     * @return Formacao
     */
    public static function create()
    {
        $formacao = self::build();
        $formacao->insert();
        return $formacao;
    }

    public function testFind()
    {
        $formacao = self::create();
        $condition = ['pacoteid' => $formacao->getPacoteID()];
        $found_formacao = Formacao::find($condition);
        $this->assertEquals($formacao, $found_formacao);
        list($found_formacao) = Formacao::findAll($condition, [], 1);
        $this->assertEquals($formacao, $found_formacao);
        $this->assertEquals(1, Formacao::count($condition));
    }

    public function testAddInvalid()
    {
        $movimentacao = MovimentacaoTest::create();
        $formacao = self::build();
        $formacao->setItemID(null);
        $formacao->setTipo('Teste');
        $formacao->setQuantidade(null);
        try {
            $formacao->insert();
            $this->fail('Não cadastrar valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['itemid', 'tipo', 'quantidade'], array_keys($e->getErrors()));
        }
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
    }

    public function testGetOptions()
    {
        $movimentacao = MovimentacaoTest::create();
        $formacao = self::create();
        $options = Formacao::getTipoOptions($formacao->getTipo());
        $this->assertEquals($formacao->getTipo(), $options);
    }
}
