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

use MZ\Product\InformacaoTest;
use MZ\Product\UnidadeTest;
use MZ\Exception\ValidationException;

class ValorNutricionalTest extends \MZ\Framework\TestCase
{
    // public function testPublish()
    // {
    //     $valor_nutricional = new ValorNutricional();
    //     $values = $valor_nutricional->publish(app()->auth->provider);
    //     $allowed = [
    //         'id',
    //         'informacaoid',
    //         'unidadeid',
    //         'nome',
    //         'quantidade',
    //         'valordiario',
    //     ];
    //     $this->assertEquals($allowed, array_keys($values));
    // }

        /**
     * Build a valid valor nutricional
     * @param string $nome Valor nutricional nome
     * @return ValorNutricional
     */
    public static function build($nome = null)
    {
        $last = ValorNutricional::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $informacao = InformacaoTest::create();
        $unidade = UnidadeTest::create();
        $valor_nutricional = new ValorNutricional();
        $valor_nutricional->setInformacaoID($informacao->getID());
        $valor_nutricional->setUnidadeID($unidade->getID());
        $valor_nutricional->setNome($nome ?: "Valor nutricional {$id}");
        $valor_nutricional->setQuantidade(12.3);
        return $valor_nutricional;
    }

    /**
     * Create a valor nutricional on database
     * @param string $nome Valor nutricional nome
     * @return ValorNutricional
     */
    public static function create($nome = null)
    {
        $valor_nutricional = self::build($nome);
        $valor_nutricional->insert();
        return $valor_nutricional;
    }

    public function testFind()
    {
        $valor_nutricional = self::create();
        $condition = ['nome' => $valor_nutricional->getNome()];
        $found_valor_nutricional = ValorNutricional::find($condition);
        $this->assertEquals($valor_nutricional, $found_valor_nutricional);
        list($found_valor_nutricional) = ValorNutricional::findAll($condition, [], 1);
        $this->assertEquals($valor_nutricional, $found_valor_nutricional);
        $this->assertEquals(1, ValorNutricional::count($condition));
    }

    public function testAdd()
    {
        $valor_nutricional = self::build();
        $valor_nutricional->insert();
        $this->assertTrue($valor_nutricional->exists());
    }

    public function testAddInvalid()
    {
        $val_nutri = self::build();
        $val_nutri->setInformacaoID(null);
        $val_nutri->setUnidadeID(null);
        $val_nutri->setNome(null);
        $val_nutri->setQuantidade(null);
        try {
            $val_nutri->insert();
            $this->fail('Não cadastrar com valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['informacaoid', 'unidadeid', 'nome', 'quantidade'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $val_nutri = self::create();
        try {
            $val_nutri->insert();
            $this->fail('Não cadastrar com fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['informacaoid', 'nome'], array_keys($e->getErrors()));
        }
    }

    public function testFinds()
    {
        $val_nutri = self::create();

        $unidade = $val_nutri->findUnidadeID();
        $this->assertEquals($val_nutri->getUnidadeID(), $unidade->getID());

        $informacao = $val_nutri->findInformacaoID();
        $this->assertEquals($val_nutri->getInformacaoID(), $informacao->getID());

        $informacaoIDNome = $val_nutri->findByInformacaoIDNome($informacao->getID(), $val_nutri->getNome());
        $this->assertInstanceOf(get_class($val_nutri), $informacaoIDNome);
    }

    public function testUpdate()
    {
        $valor_nutricional = self::create();
        $valor_nutricional->update();
        $this->assertTrue($valor_nutricional->exists());
    }

    public function testDelete()
    {
        $valor_nutricional = self::create();
        $valor_nutricional->delete();
        $valor_nutricional->loadByID();
        $this->assertFalse($valor_nutricional->exists());
    }
}
