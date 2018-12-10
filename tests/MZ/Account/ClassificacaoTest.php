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
namespace MZ\Account;

class ClassificacaoTest extends \MZ\Framework\TestCase
{
    public function testFromArray()
    {
        $old_classificacao = new Classificacao([
            'id' => 123,
            'classificacaoid' => 123,
            'descricao' => 'Classificação',
        ]);
        $classificacao = new Classificacao();
        $classificacao->fromArray($old_classificacao);
        $this->assertEquals($classificacao, $old_classificacao);
        $classificacao->fromArray(null);
        $this->assertEquals($classificacao, new Classificacao());
    }

    public function testFilter()
    {
        $old_classificacao = new Classificacao([
            'id' => 1234,
            'classificacaoid' => 1234,
            'descricao' => 'Classificação filter',
        ]);
        $classificacao = new Classificacao([
            'id' => 321,
            'classificacaoid' => '1.234',
            'descricao' => ' Classificação <script>filter</script> ',
        ]);
        $classificacao->filter($old_classificacao, app()->auth->provider, true);
        $this->assertEquals($old_classificacao, $classificacao);
    }

    public function testPublish()
    {
        $classificacao = new Classificacao();
        $values = $classificacao->publish(app()->auth->provider);
        $allowed = [
            'id',
            'classificacaoid',
            'descricao',
            'iconeurl',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $classificacao = new Classificacao();
        try {
            $classificacao->insert();
            $this->fail('Não deveria ter cadastrado a classificação');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'descricao',
                ],
                array_keys($e->getErrors())
            );
        }
        $classificacao->setDescricao('Classificação to insert');
        $classificacao->insert();
    }

    public function testUpdate()
    {
        $classificacao = new Classificacao();
        $classificacao->setDescricao('Classificação to update');
        $classificacao->insert();
        $classificacao->setDescricao('Classificação updated');
        $classificacao->update();
        $found_classificacao = Classificacao::findByID($classificacao->getID());
        $this->assertEquals($classificacao, $found_classificacao);
        $classificacao->setID('');
        $this->expectException('\Exception');
        $classificacao->update();
    }

    public function testDelete()
    {
        $classificacao = new Classificacao();
        $classificacao->setDescricao('Classificação to delete');
        $classificacao->insert();
        $classificacao->delete();
        $classificacao->clean(new Classificacao());
        $found_classificacao = Classificacao::findByID($classificacao->getID());
        $this->assertEquals(new Classificacao(), $found_classificacao);
        $classificacao->setID('');
        $this->expectException('\Exception');
        $classificacao->delete();
    }

    public function testFind()
    {
        $classificacao = new Classificacao();
        $classificacao->setDescricao('Classificação find');
        $classificacao->insert();
        $found_classificacao = Classificacao::find(['id' => $classificacao->getID()]);
        $this->assertEquals($classificacao, $found_classificacao);
        $found_classificacao = Classificacao::findByID($classificacao->getID());
        $this->assertEquals($classificacao, $found_classificacao);
        $found_classificacao = Classificacao::findByDescricao($classificacao->getDescricao());
        $this->assertEquals($classificacao, $found_classificacao);

        $classificacao_sec = new Classificacao();
        $classificacao_sec->setDescricao('Classificação find second');
        $classificacao_sec->insert();

        $classificacoes = Classificacao::findAll(['search' => 'Classificação find'], [], 2, 0);
        $this->assertEquals([$classificacao, $classificacao_sec], $classificacoes);

        $count = Classificacao::count(['search' => 'Classificação find']);
        $this->assertEquals(2, $count);
    }
}
