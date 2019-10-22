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

namespace MZ\Provider;

class FuncaoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid função
     * @param string $descricao Função descrição
     * @return Funcao
     */
    public static function build($descricao = null)
    {
        $last = Funcao::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $funcao = new Funcao();
        $funcao->setDescricao($descricao ?: "Função {$id}");
        $funcao->setRemuneracao(12.3);
        return $funcao;
    }

    /**
     * Create a função on database
     * @param array $permissions lista de permissões da função
     * @param string $descricao Função descrição
     * @return Funcao
     */
    public static function create($permissions = [], $descricao = null)
    {
        $funcao = self::build($descricao);
        $funcao->insert();
        \MZ\System\AcessoTest::create($funcao, $permissions);
        return $funcao;
    }

    public function testFromArray()
    {
        $old_funcao = new Funcao([
            'id' => 123,
            'descricao' => 'Função',
            'remuneracao' => 12.3,
        ]);
        $funcao = new Funcao();
        $funcao->fromArray($old_funcao);
        $this->assertEquals($funcao, $old_funcao);
        $funcao->fromArray(null);
        $this->assertEquals($funcao, new Funcao());
    }

    public function testFilter()
    {
        $old_funcao = new Funcao([
            'id' => 1234,
            'descricao' => 'Função filter',
            'remuneracao' => 12.3,
        ]);
        $funcao = new Funcao([
            'id' => 321,
            'descricao' => ' Função <script>filter</script> ',
            'remuneracao' => '12,3',
        ]);
        $funcao->filter($old_funcao, app()->auth->provider, true);
        $this->assertEquals($old_funcao, $funcao);
    }

    public function testPublish()
    {
        $funcao = new Funcao();
        $values = $funcao->publish(app()->auth->provider);
        $allowed = [
            'id',
            'descricao',
            'remuneracao',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $funcao = new Funcao();
        try {
            $funcao->insert();
            $this->fail('Não deveria ter cadastrado a função');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'descricao',
                    'remuneracao',
                ],
                array_keys($e->getErrors())
            );
        }
        //---------------------
        $funcao->setDescricao('Função to insert');
        $funcao->setRemuneracao(null);
        try {
            $funcao->insert();
            $this->fail('Remuneração não pode ser nula');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(['remuneracao'], array_keys($e->getErrors()));
        }
        //---------------------
        $funcao->setRemuneracao(-100);
        try {
            $funcao->insert();
            $this->fail('Remuneração não pode ser negativa');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(['remuneracao'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $funcao = self::create();

        try {
            $funcao->insert();
            $this->fail('Não inserir fk duplicada');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(['descricao'], array_keys($e->getErrors()));
        }
    }

    public function testUpdate()
    {
        $funcao = new Funcao();
        $funcao->setDescricao('Função to update');
        $funcao->setRemuneracao(12.3);
        $funcao->insert();
        $funcao->setDescricao('Função updated');
        $funcao->setRemuneracao(21.4);
        $funcao->update();
        $found_funcao = Funcao::findByID($funcao->getID());
        $this->assertEquals($funcao, $found_funcao);
        $funcao->setID('');
        $this->expectException('\Exception');
        $funcao->update();
    }

    public function testDelete()
    {
        $funcao = new Funcao();
        $funcao->setDescricao('Função to delete');
        $funcao->setRemuneracao(12.3);
        $funcao->insert();
        $funcao->delete();
        $funcao->clean(new Funcao());
        $found_funcao = Funcao::findByID($funcao->getID());
        $this->assertEquals(new Funcao(), $found_funcao);
        $funcao->setID('');
        $this->expectException('\Exception');
        $funcao->delete();
    }

    public function testFind()
    {
        $funcao = new Funcao();
        $funcao->setDescricao('Função find');
        $funcao->setRemuneracao(12.3);
        $funcao->insert();
        $found_funcao = Funcao::find(['id' => $funcao->getID()]);
        $this->assertEquals($funcao, $found_funcao);
        $found_funcao = Funcao::findByID($funcao->getID());
        $this->assertEquals($funcao, $found_funcao);
        $found_funcao = Funcao::findByDescricao($funcao->getDescricao());
        $this->assertEquals($funcao, $found_funcao);

        $funcao_sec = new Funcao();
        $funcao_sec->setDescricao('Função find second');
        $funcao_sec->setRemuneracao(12.3);
        $funcao_sec->insert();

        $funcoes = Funcao::findAll(['search' => 'Função find'], [], 2, 0);
        $this->assertEquals([$funcao, $funcao_sec], $funcoes);

        $count = Funcao::count(['search' => 'Função find']);
        $this->assertEquals(2, $count);
    }
}
