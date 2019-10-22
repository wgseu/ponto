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

namespace MZ\Session;

use MZ\Account\Cliente;
use MZ\Database\DB;
use MZ\Provider\PrestadorTest;
use MZ\Wallet\CarteiraTest;
use \MZ\Provider\FuncaoTest;

class CaixaTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid caixa
     * @param string $descricao Caixa descrição
     * @return Caixa
     */
    public static function build($descricao = null)
    {
        $last = Caixa::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $carteira = CarteiraTest::create();
        $caixa = new Caixa();
        $caixa->setCarteiraID($carteira->getID());
        $caixa->setDescricao($descricao ?: "Caixa {$id}");
        $caixa->setSerie(4);
        $caixa->setNumeroInicial(100);
        $caixa->setAtivo('Y');
        return $caixa;
    }

    /**
     * Create a caixa on database
     * @param string $descricao Caixa descrição
     * @return Caixa
     */
    public static function create($descricao = null)
    {
        $caixa = self::build($descricao);
        $caixa->insert();
        return $caixa;
    }

    public function testFromArray()
    {
        $old_caixa = new Caixa(['descricao' => 'Caixa 1']);
        $caixa = new Caixa();
        $caixa->fromArray($old_caixa);
        $this->assertEquals($caixa, $old_caixa);
        $caixa->fromArray(null);
        $this->assertEquals($caixa, new Caixa());
        // clean nothing
        $caixa->clean($old_caixa);
    }

    public function testFilter()
    {
        $old_caixa = new Caixa([
            'id' => 1,
            'descricao' => 'Caixa 1',
            'serie' => 12,
            'numeroinicial' => 53,
        ]);
        $caixa = new Caixa([
            'id' => 32,
            'descricao' => 'Caixa <script>1</script>',
            'serie' => 'a1t2',
            'numeroinicial' => 'b5a3',
        ]);
        $caixa->filter($old_caixa, app()->auth->provider, true);
        $this->assertEquals($old_caixa, $caixa);
    }

    public function testPublish()
    {
        $caixa = new Caixa();
        $values = $caixa->publish(app()->auth->provider);
        $allowed = [
            'id',
            'carteiraid',
            'descricao',
            'serie',
            'numeroinicial',
            'ativo',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testAdd()
    {
        $caixa = self::build();
        $caixa->insert();
        $this->assertTrue($caixa->exists());
    }

    public function testUpdate()
    {
        $caixa = self::create('Caixa de número 3');
        $this->assertTrue($caixa->exists());

        $caixa->setDescricao('Cash register 3');
        $caixa->update();
        $this->assertEquals('Cash register 3', $caixa->getDescricao());
    }

    public function testFind()
    {
        $caixa = self::create('Caixa de número 4');

        $found_caixa = Caixa::findByID($caixa->getID());
        $this->assertEquals($caixa, $found_caixa);
        $found_caixa = Caixa::findByDescricao('Caixa de número 4');
        $this->assertEquals($caixa, $found_caixa);

        $caixa_sec = self::create('Caixa de número 48');

        $caixas = Caixa::findAll(['search' => 'Caixa de número 4'], [], 2, 0);
        $this->assertEquals([$caixa, $caixa_sec], $caixas);

        $count = Caixa::count(['search' => 'Caixa de número 4']);
        $this->assertEquals(2, $count);
    }

    public function testSerie()
    {
        $old_value = app()->getSystem()->getBusiness()->getOptions()->getValue('Sistema', 'Fiscal.Mostrar');
        app()->getSystem()->getBusiness()->getOptions()->setValue('Sistema', 'Fiscal.Mostrar', true);
        $caixa = self::create('Caixa de número 6');
        app()->getSystem()->getBusiness()->getOptions()->setValue('Sistema', 'Fiscal.Mostrar', $old_value);

        $found_caixa = Caixa::findBySerie($caixa->getSerie());
        $this->assertEquals($caixa, $found_caixa);

        $caixa->setAtivo('N');
        $caixa->update();

        $found_caixa = Caixa::findBySerie($caixa->getSerie());
        $this->assertEquals(new Caixa(), $found_caixa);

        Caixa::resetBySerie($caixa->getSerie());
        $found_caixa = Caixa::findByID($caixa->getID());
        $this->assertEquals($caixa, $found_caixa);

        $caixa->setAtivo('Y');
        $caixa->update();
        Caixa::resetBySerie($caixa->getSerie());
        $found_caixa = Caixa::findBySerie($caixa->getSerie());
        $new_caixa = new Caixa($caixa);
        $new_caixa->setNumeroInicial(1);
        $this->assertEquals($new_caixa, $found_caixa);
        $caixa->delete();
    }

    public function testSearch()
    {
        $caixa = self::create('Caixa de número 5');

        $found_caixa = Caixa::find(['search' => 'xa de número 5']);
        $this->assertEquals($caixa, $found_caixa);
        $caixa->delete();
    }

    public function testValidate()
    {
        $old_value = app()->getSystem()->getBusiness()->getOptions()->getValue('Sistema', 'Fiscal.Mostrar');
        app()->getSystem()->getBusiness()->getOptions()->setValue('Sistema', 'Fiscal.Mostrar', true);
        $this->expectException('\MZ\Exception\ValidationException');
        try {
            $caixa = new Caixa();
            $caixa->setAtivo('A');
            $caixa->insert();
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                ['carteiraid', 'descricao', 'serie', 'numeroinicial', 'ativo'],
                array_keys($e->getErrors())
            );
            throw $e;
        } finally {
            app()->getSystem()->getBusiness()->getOptions()->setValue('Sistema', 'Fiscal.Mostrar', $old_value);
        }
    }

    public function testDesativarEmUso()
    {
        $movimentacao = MovimentacaoTest::create();
        $caixa = $movimentacao->findCaixaID();
        try {
            $caixa->setAtivo('N');
            $caixa->update();
            $this->fail('Não deveria ter desativado um caixa em uso');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(['ativo'], array_keys($e->getErrors()));
            MovimentacaoTest::close($movimentacao);
        }
    }

    public function testTranslate()
    {
        $caixa = self::create();
        try {
            $caixa->insert();
            $this->fail('fk duplicada');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(['descricao'], array_keys($e->getErrors()));
        }
    }

    public function testDelete()
    {
        $caixa = self::create('Caixa de número 9');
        $caixa->delete();
        $caixa->clean(new Caixa());
        $found_caixa = Caixa::findByID($caixa->getID());
        $this->assertEquals(new Caixa(), $found_caixa);
        $caixa->setID('');
        $this->expectException('\Exception');
        $caixa->delete();
    }
}
