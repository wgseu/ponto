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
namespace MZ\Invoice;

use \MZ\Exception\ValidationException;

class RegimeTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid regime
     * @param string $descricao Regime descrição
     * @return Regime
     */
    public static function build($descricao = null)
    {
        $last = Regime::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $regime = new Regime();
        $regime->setCodigo($id+100);
        $regime->setDescricao('Descrição do regime');
        return $regime;
    }

    /**
     * Create a regime on database
     * @param string $descricao Regime descrição
     * @return Regime
     */
    public static function create($descricao = null)
    {
        $regime = self::build($descricao);
        $regime->insert();
        return $regime;
    }

    public function testFromArray()
    {
        $old_regime = new Regime([
            'id' => 123,
            'codigo' => 123,
            'descricao' => 'Regime',
        ]);
        $regime = new Regime();
        $regime->fromArray($old_regime);
        $this->assertEquals($regime, $old_regime);
        $regime->fromArray(null);
        $this->assertEquals($regime, new Regime());
    }

    public function testFilter()
    {
        $old_regime = new Regime([
            'id' => 1234,
            'codigo' => 1234,
            'descricao' => 'Regime filter',
        ]);
        $regime = new Regime([
            'id' => 321,
            'codigo' => '1.234',
            'descricao' => ' Regime <script>filter</script> ',
        ]);
        $regime->filter($old_regime, app()->auth->provider, true);
        $this->assertEquals($old_regime, $regime);
    }

    public function testPublish()
    {
        $regime = new Regime();
        $values = $regime->publish(app()->auth->provider);
        $allowed = [
            'id',
            'codigo',
            'descricao',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $regime = new Regime();
        try {
            $regime->insert();
            $this->fail('Não deveria ter cadastrado o regime');
        } catch (ValidationException $e) {
            $this->assertEquals(
                [
                    'codigo',
                    'descricao',
                ],
                array_keys($e->getErrors())
            );
        }
        $regime->setCodigo(12345);
        $regime->setDescricao('Regime to insert');
        $regime->insert();
    }

    public function testUpdate()
    {
        $regime = new Regime();
        $regime->setCodigo(123456);
        $regime->setDescricao('Regime to update');
        $regime->insert();
        $regime->setCodigo(456);
        $regime->setDescricao('Regime updated');
        $regime->update();
        $found_regime = Regime::findByID($regime->getID());
        $this->assertEquals($regime, $found_regime);
        $regime->setID('');
        $this->expectException('\Exception');
        $regime->update();
    }

    public function testDelete()
    {
        $regime = new Regime();
        $regime->setCodigo(123123);
        $regime->setDescricao('Regime to delete');
        $regime->insert();
        $regime->delete();
        $regime->clean(new Regime());
        $found_regime = Regime::findByID($regime->getID());
        $this->assertEquals(new Regime(), $found_regime);
        $regime->setID('');
        $this->expectException('\Exception');
        $regime->delete();
    }

    public function testFind()
    {
        $regime = new Regime();
        $regime->setCodigo(123321);
        $regime->setDescricao('Regime find');
        $regime->insert();
        $found_regime = Regime::find(['id' => $regime->getID()]);
        $this->assertEquals($regime, $found_regime);
        $found_regime = Regime::findByID($regime->getID());
        $this->assertEquals($regime, $found_regime);
        $found_regime = Regime::findByCodigo($regime->getCodigo());
        $this->assertEquals($regime, $found_regime);

        $regime_sec = new Regime();
        $regime_sec->setCodigo(123451);
        $regime_sec->setDescricao('Regime find second');
        $regime_sec->insert();

        $regimes = Regime::findAll(['search' => 'Regime find'], [], 2, 0);
        $this->assertEquals([$regime, $regime_sec], $regimes);

        $count = Regime::count(['search' => 'Regime find']);
        $this->assertEquals(2, $count);
    }

    public function testTranslate()
    {
        $regime = self::create();
        try {
            $regime->insert();
            $this->fail('Fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['codigo'], array_keys($e->getErrors()));
        }
    }
}
