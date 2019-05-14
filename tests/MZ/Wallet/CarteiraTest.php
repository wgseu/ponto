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
namespace MZ\Wallet;
use MZ\Exception\ValidationException;


class CarteiraTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid carteira
     * @param string $descricao Carteira descrição
     * @return Carteira
     */
    public static function build($descricao = null)
    {
        $last = Carteira::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $carteira = new Carteira();
        $carteira->setTipo(Carteira::TIPO_LOCAL);
        $carteira->setDescricao($descricao ?: "Carteira {$id}");
        $carteira->setTransacao(10);
        $carteira->setAtiva('Y');
        return $carteira;
    }

    /**
     * Create a carteira on database
     * @param string $descricao Carteira descrição
     * @return Carteira
     */
    public static function create($descricao = null)
    {
        $carteira = self::build($descricao);
        $carteira->insert();
        return $carteira;
    }

    public function testFind()
    {
        $carteira = self::create();
        $condition = ['descricao' => $carteira->getDescricao()];
        $found_carteira = Carteira::find($condition);
        $this->assertEquals($carteira, $found_carteira);
        list($found_carteira) = Carteira::findAll($condition, [], 1);
        $this->assertEquals($carteira, $found_carteira);
        $this->assertEquals(1, Carteira::count($condition));
    }

    public function testFindCarteiraID()
    {
        $carteiraInit = self::create();
        $carteira = self::create();
        $carteira->setCarteiraID($carteiraInit->getID());
        $carteiraFind = $carteira->findCarteiraID();
        $this->assertEquals($carteiraInit->getID(), $carteiraFind->getID());
    }

    public function testFindBancoID()
    {
        $banco = BancoTest::create();
        $carteira = self::create();
        $carteira->setBancoID($banco->getID());
        $bancoFind = $carteira->findBancoID();
        $this->assertEquals($bancoFind->getID(), $carteira->getBancoID());
    }

    public function testAdd()
    {
        $carteira = self::build();
        $carteira->insert();
        $this->assertTrue($carteira->exists());
    }

    public function testAddInvalid()
    {
        // // Teste de carteira pai invalida
        $carteira = self::build();
        $carteira->setCarteiraID(999);
        try {
            $carteira->insert();
            $this->fail('Carteira não existe');
        } catch (ValidationException $e) {
            $this->assertEquals(['carteiraid'], array_keys($e->getErrors()));
        }
        // Já existe carteiraID
        $carteiraInit = self::build();
        $carteiraInit->insert();

        $carteira = self::build();
        $carteira->setCarteiraID($carteiraInit->getID());
        $carteira->insert();

        $carteiraPai = self::build();
        $carteiraPai->setCarteiraID($carteira->getID());
        try {
            $carteiraPai->insert();
            $this->fail('Já existe carteiraID');
        } catch (ValidationException $e) {
            $this->assertEquals(['carteiraid'], array_keys($e->getErrors()));
        }
        // Tipo e Ambiente de carteira invalido
        $carteira = self::build();
        $carteira->setTipo('tipo inválido');
        $carteira->setAmbiente('ambiente inválido');
        try {
            $carteira->insert();
            $this->fail('Tipo e Ambiente de carteira inválido');
        } catch (ValidationException $e) {
            $this->assertEquals(['tipo', 'ambiente'], array_keys($e->getErrors()));
        }
        // Banco, agencia e conta não informado em cateira tipo banco
        $carteira = self::build();
        $carteira->setTipo(Carteira::TIPO_BANCARIA);
        $carteira->setBancoID(null);
        $carteira->setAgencia(null);
        $carteira->setConta(null);
        try {
            $carteira->insert();
            $this->fail('Banco, agência e conta não informado em carteira bancária');
        } catch (ValidationException $e) {
            $this->assertEquals(['bancoid', 'agencia', 'conta'], array_keys($e->getErrors()));
        }
        // Banco informado em carteira tipo financeira
        $banco = BancoTest::build();
        $banco->insert();
        $carteira = self::build();
        $carteira->setTipo(Carteira::TIPO_FINANCEIRA);
        $carteira->setBancoID($banco->getID());
        try {
            $carteira->insert();
            $this->fail('Banco informado em carteira financeira');
        } catch (ValidationException $e) {
            $this->assertEquals(['bancoid'], array_keys($e->getErrors()));
        }
    }

    public function testUpdateInvalid()
    {
        //Teste carteiraID duplicada
        $carteiraInit = self::build();
        $carteiraInit->insert();

        $carteira = self::build();
        $carteira->setCarteiraID($carteiraInit->getID());
        $carteira->setID($carteiraInit->getID());
        try {
            $carteira->update();
            $this->fail('Já existe carteira com o mesmo ID');
        } catch (ValidationException $e) {
            $this->assertEquals(['carteiraid'], array_keys($e->getErrors()));
        }
        // Descrição e transação nula
        $carteira = self::build();
        $carteira->setDescricao(null);
        $carteira->setTransacao(null);
        try {
            $carteira->update();
            $this->fail('Descrição e transaçao não pode ser nula');
        } catch (ValidationException $e) {
            $this->assertEquals(['descricao', 'transacao'], array_keys($e->getErrors()));
        }
        // checa valor padrão de boolena no ativa
        $carteira = self::build();
        $carteira->setAtiva('tipo inválido');
        try {
            $carteira->insert();
            $this->fail('Valor inválido');
        } catch (ValidationException $e) {
            $this->assertEquals(['ativa'], array_keys($e->getErrors()));
        }

    }

    public function testGetTipoOptions()
    {
        $carteira = self::create();
        $options = Carteira::getTipoOptions($carteira->getTipo());
        $this->assertEquals(_t('carteira.tipo_local'), $options);
    }

    public function testGetAmbienteOptions()
    {
        $carteira = self::build();
        $carteira->setAmbiente(Carteira::AMBIENTE_TESTE);
        $options = Carteira::getAmbienteOptions($carteira->getAmbiente());
        $this->assertEquals(Carteira::AMBIENTE_TESTE, $options);
    }

    public function testSumToReceive()
    {
        $pagamento = \MZ\Payment\PagamentoTest::create();
        $pagamento->setEstado('Pago');
        $pagamento->update();
        $carteira = $pagamento->findCarteiraID();
        $soma = $carteira::sumToReceive();
        $this->assertEquals($pagamento->getValor(), $soma);
    }

    public function testFetchAvailable()
    {
        $pagamento = \MZ\Payment\PagamentoTest::create();
        $pagamento->setEstado('Pago');
        $pagamento->update();
        $carteira = $pagamento->findCarteiraID();
        $resul = $carteira->fetchAvailable();
        $this->assertEquals($pagamento->getValor(), $resul);
    }

    public function testIsAtivo()
    {
        $carteira = self::create();
        $verificador = $carteira->isAtiva();
        $this->assertTrue($verificador);
    }

    public function testmakeLogoURL()
    {
        $carteira = new Carteira();
        $this->assertEquals('/static/img/carteira.png', $carteira->makeLogoURL(true));
        $carteira->setLogoURL('imagem.png');
        $this->assertEquals('/static/img/carteira/imagem.png', $carteira->makeLogoURL());
    }

    public function testUpdate()
    {
        $carteira = self::create();
        $carteira->update();
        $this->assertTrue($carteira->exists());
    }

    public function testDelete()
    {
        $carteira = self::create();
        $carteira->delete();
        $carteira->loadByID();
        $this->assertFalse($carteira->exists());
    }

    public function testClean()
    {
        $old_carteira = new Carteira();
        $old_carteira->setLogoURL('imagem.png');

        $carteira = new Carteira();
        $carteira->setLogoURL('imagem.png');
        $carteira->clean($old_carteira);
        $this->assertEquals($old_carteira, $carteira);
    }
}
