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

use MZ\Sale\PedidoTest;
use MZ\Session\CaixaTest;
use MZ\Session\MovimentacaoTest;
use MZ\Session\Movimentacao;

class ComandaTest extends \MZ\Framework\TestCase
{
    public static function build($nome = null)
    {
        $comanda = new Comanda();
        $comanda->loadNextNumero();
        $numero = $comanda->getNumero();
        $comanda->setNome($nome ?: "Comanda {$numero}");
        $comanda->setAtiva('Y');
        return $comanda;
    }

    public static function create($nome = null)
    {
        $comanda = self::build($nome);
        $comanda->insert();
        return $comanda;
    }

    public function testFromArray()
    {
        $old_comanda = new Comanda([
            'id' => 123,
            'numero' => 123,
            'nome' => 'Comanda',
            'ativa' => 'Y',
        ]);
        $comanda = new Comanda();
        $comanda->fromArray($old_comanda);
        $this->assertEquals($comanda, $old_comanda);
        $comanda->fromArray(null);
        $this->assertEquals($comanda, new Comanda());
    }

    public function testFilter()
    {
        $old_comanda = new Comanda([
            'id' => 1234,
            'numero' => 1230,
            'nome' => 'Comanda filter',
            'ativa' => 'Y',
        ]);
        $comanda = new Comanda([
            'id' => 1234,
            'numero' => '1.230',
            'nome' => ' Comanda <script>filter</script> ',
            'ativa' => 'Y',
        ]);
        $comanda->filter($old_comanda, app()->auth->provider, true);
        $this->assertEquals($old_comanda, $comanda);
    }

    public function testPublish()
    {
        $comanda = new Comanda();
        $values = $comanda->publish(app()->auth->provider);
        $allowed = [
            'id',
            'numero',
            'nome',
            'ativa',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $comanda = new Comanda();
        $comanda->setAtiva(null);
        try {
            $comanda->insert();
            $this->fail('Não deveria ter cadastrado a comanda');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'numero',
                    'nome',
                    'ativa',
                ],
                array_keys($e->getErrors())
            );
        }
    }

    public function testUpdate()
    {
        $comanda = self::create('Comanda to update');
        $comanda->loadNextNumero();
        $comanda->setNome('Comanda updated');
        $comanda->setAtiva('N');
        $comanda->update();
        $found_comanda = Comanda::findByID($comanda->getID());
        $this->assertEquals($comanda, $found_comanda);
        $comanda->setID('');
        $this->expectException('\Exception');
        $comanda->update();
    }

    public function testUpdateInvalid()
    {
        $movimentacao = MovimentacaoTest::create();
        $comanda = self::create('Comanda to update');
        $pedido = PedidoTest::build();
        $pedido->setComandaID($comanda->getID());
        $pedido->setMesaID(null);
        $pedido->setPessoas(1);
        $pedido->setTipo(Pedido::TIPO_COMANDA);
        $pedido->insert();
        $comanda->loadNextNumero();
        $comanda->setNome('Comanda updated');
        $comanda->setAtiva('N');
        try {
            $comanda->update();
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(['ativa'], array_keys($e->getErrors()));
        }
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testDelete()
    {
        $comanda = self::create('Comanda to delete');
        $comanda->delete();
        $comanda->clean(new Comanda());
        $found_comanda = Comanda::findByID($comanda->getID());
        $this->assertEquals(new Comanda(), $found_comanda);
        $comanda->setID('');
        $this->expectException('\Exception');
        $comanda->delete();
    }

    public function testFind()
    {
        $comanda = self::create('Comanda find');
        $found_comanda = Comanda::find(['id' => $comanda->getID()]);
        $this->assertEquals($comanda, $found_comanda);
        $found_comanda = Comanda::findByNumero($comanda->getNumero());
        $this->assertEquals($comanda, $found_comanda);
        $found_comanda = Comanda::findByNome($comanda->getNome());
        $this->assertEquals($comanda, $found_comanda);

        $comanda_sec = self::create('Comanda find second');

        $comandas = Comanda::findAll(['search' => 'Comanda find'], [], 2, 0);
        $this->assertEquals([$comanda, $comanda_sec], $comandas);

        $count = Comanda::count(['search' => 'Comanda find']);
        $this->assertEquals(2, $count);
    }

    public function testTranslate()
    {
        $comanda = self::create();
        try {
            $comanda->insert();
            $this->fail('Fk duplicada');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(['numero'], array_keys($e->getErrors()));
        }
        //---------------
        $comanda = self::create();
        try {
            $comanda->setNumero(81);
            $comanda->insert();
            $this->fail('Fk duplicada');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(['nome'], array_keys($e->getErrors()));
        }
    }
}
