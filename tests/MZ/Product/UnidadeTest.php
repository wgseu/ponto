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

use MZ\Exception\ValidationException;

class UnidadeTest extends \MZ\Framework\TestCase
{
    public static function build($nome = null, $sigla = null)
    {
        $last = Unidade::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $unidade = new Unidade();
        $unidade->setNome($nome ?: "Unidade #{$id}");
        $unidade->setSigla($sigla ?: "S{$id}");
        return $unidade;
    }

    public static function create($nome = null)
    {
        $unidade = self::build($nome);
        $unidade->insert();
        return $unidade;
    }

    public function testFromArray()
    {
        $old_unidade = new Unidade([
            'id' => 123,
            'nome' => 'Unidade',
            'descricao' => 'Unidade',
            'sigla' => 'Unidade',
        ]);
        $unidade = new Unidade();
        $unidade->fromArray($old_unidade);
        $this->assertEquals($unidade, $old_unidade);
        $unidade->fromArray(null);
        $this->assertEquals($unidade, new Unidade());
    }

    public function testFilter()
    {
        $old_unidade = new Unidade([
            'id' => 1234,
            'nome' => 'Unidade filter',
            'descricao' => 'Unidade filter',
            'sigla' => 'Unidade filter',
        ]);
        $unidade = new Unidade([
            'id' => 321,
            'nome' => ' Unidade <script>filter</script> ',
            'descricao' => ' Unidade <script>filter</script> ',
            'sigla' => ' Unidade <script>filter</script> ',
        ]);
        $unidade->filter($old_unidade, app()->auth->provider, true);
        $this->assertEquals($old_unidade, $unidade);
    }

    public function testPublish()
    {
        $unidade = new Unidade();
        $values = $unidade->publish(app()->auth->provider);
        $allowed = [
            'id',
            'nome',
            'descricao',
            'sigla',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $unidade = new Unidade();
        try {
            $unidade->insert();
            $this->fail('Não deveria ter cadastrado a unidade');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'nome',
                    'sigla',
                ],
                array_keys($e->getErrors())
            );
        }
        $unidade->setNome('Unidade to insert');
        $unidade->setSigla('Unidade to insert');
        $unidade->insert();
    }

    public function testTranslate()
    {
        $unidade = self::create();

        try {
            $unidade->insert();
            $this->fail('Não cadastrar com fk repetida');
        } catch (ValidationException $e) {
            $this->assertEquals(['sigla'], array_keys($e->getErrors()));
        }
    }

    public function testUpdate()
    {
        $unidade = new Unidade();
        $unidade->setNome('Unidade to update');
        $unidade->setSigla('Unidade to update');
        $unidade->insert();
        $unidade->setNome('Unidade updated');
        $unidade->setDescricao('Unidade updated');
        $unidade->setSigla('Unidade updated');
        $unidade->update();
        $found_unidade = Unidade::findByID($unidade->getID());
        $this->assertEquals($unidade, $found_unidade);
        $unidade->setID('');
        $this->expectException('\Exception');
        $unidade->update();
    }

    public function testDelete()
    {
        $unidade = new Unidade();
        $unidade->setNome('Unidade to delete');
        $unidade->setSigla('Unidade to delete');
        $unidade->insert();
        $unidade->delete();
        $unidade->clean(new Unidade());
        $found_unidade = Unidade::findByID($unidade->getID());
        $this->assertEquals(new Unidade(), $found_unidade);
        $unidade->setID('');
        $this->expectException('\Exception');
        $unidade->delete();
    }

    public function testFind()
    {
        $unidade = new Unidade();
        $unidade->setNome('Unidade find');
        $unidade->setSigla('Unidade find');
        $unidade->insert();
        $found_unidade = Unidade::find(['id' => $unidade->getID()]);
        $this->assertEquals($unidade, $found_unidade);
        $found_unidade = Unidade::findByID($unidade->getID());
        $this->assertEquals($unidade, $found_unidade);
        $found_unidade = Unidade::findBySigla($unidade->getSigla());
        $this->assertEquals($unidade, $found_unidade);

        $unidade_sec = new Unidade();
        $unidade_sec->setNome('Unidade find second');
        $unidade_sec->setSigla('Unidade find second');
        $unidade_sec->insert();

        $unidades = Unidade::findAll(['search' => 'Unidade find'], [], 2, 0);
        $this->assertEquals([$unidade, $unidade_sec], $unidades);

        $count = Unidade::count(['search' => 'Unidade find']);
        $this->assertEquals(2, $count);
    }
}
