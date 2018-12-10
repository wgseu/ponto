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

class OperacaoTest extends \MZ\Framework\TestCase
{
    public function testFromArray()
    {
        $old_operacao = new Operacao([
            'id' => 123,
            'codigo' => 123,
            'descricao' => 'Operação',
            'detalhes' => 'Operação',
        ]);
        $operacao = new Operacao();
        $operacao->fromArray($old_operacao);
        $this->assertEquals($operacao, $old_operacao);
        $operacao->fromArray(null);
        $this->assertEquals($operacao, new Operacao());
    }

    public function testFilter()
    {
        $old_operacao = new Operacao([
            'id' => 1234,
            'codigo' => 1234,
            'descricao' => 'Operação filter',
            'detalhes' => 'Operação filter',
        ]);
        $operacao = new Operacao([
            'id' => 321,
            'codigo' => '1.234',
            'descricao' => ' Operação <script>filter</script> ',
            'detalhes' => ' Operação <script>filter</script> ',
        ]);
        $operacao->filter($old_operacao, app()->auth->provider, true);
        $this->assertEquals($old_operacao, $operacao);
    }

    public function testPublish()
    {
        $operacao = new Operacao();
        $values = $operacao->publish(app()->auth->provider);
        $allowed = [
            'id',
            'codigo',
            'descricao',
            'detalhes',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $operacao = new Operacao();
        try {
            $operacao->insert();
            $this->fail('Não deveria ter cadastrado a operação');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'codigo',
                    'descricao',
                ],
                array_keys($e->getErrors())
            );
        }
        $operacao->setCodigo(12345);
        $operacao->setDescricao('Operação to insert');
        $operacao->insert();
    }

    public function testUpdate()
    {
        $operacao = new Operacao();
        $operacao->setCodigo(123456);
        $operacao->setDescricao('Operação to update');
        $operacao->insert();
        $operacao->setCodigo(456123);
        $operacao->setDescricao('Operação updated');
        $operacao->setDetalhes('Operação updated');
        $operacao->update();
        $found_operacao = Operacao::findByID($operacao->getID());
        $this->assertEquals($operacao, $found_operacao);
        $operacao->setID('');
        $this->expectException('\Exception');
        $operacao->update();
    }

    public function testDelete()
    {
        $operacao = new Operacao();
        $operacao->setCodigo(123321);
        $operacao->setDescricao('Operação to delete');
        $operacao->insert();
        $operacao->delete();
        $operacao->clean(new Operacao());
        $found_operacao = Operacao::findByID($operacao->getID());
        $this->assertEquals(new Operacao(), $found_operacao);
        $operacao->setID('');
        $this->expectException('\Exception');
        $operacao->delete();
    }

    public function testFind()
    {
        $operacao = new Operacao();
        $operacao->setCodigo(123654);
        $operacao->setDescricao('Operação find');
        $operacao->insert();
        $found_operacao = Operacao::find(['id' => $operacao->getID()]);
        $this->assertEquals($operacao, $found_operacao);
        $found_operacao = Operacao::findByID($operacao->getID());
        $this->assertEquals($operacao, $found_operacao);
        $found_operacao = Operacao::findByCodigo($operacao->getCodigo());
        $this->assertEquals($operacao, $found_operacao);

        $operacao_sec = new Operacao();
        $operacao_sec->setCodigo(123789);
        $operacao_sec->setDescricao('Operação find second');
        $operacao_sec->insert();

        $operacoes = Operacao::findAll(['search' => 'Operação find'], [], 2, 0);
        $this->assertEquals([$operacao, $operacao_sec], $operacoes);

        $count = Operacao::count(['search' => 'Operação find']);
        $this->assertEquals(2, $count);
    }
}
