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
use \MZ\Database\DB;

class CaixaTest extends \PHPUnit_Framework_TestCase
{
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
        $caixa->filter($old_caixa);
        $this->assertEquals($old_caixa, $caixa);
    }

    public function testPublish()
    {
        $caixa = new Caixa();
        $values = $caixa->publish();
        $allowed = [
            'id',
            'descricao',
            'serie',
            'numeroinicial',
            'ativo',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $caixa = new Caixa();
        $caixa->setDescricao('Caixa 2');
        $caixa->insert();
        $this->setExpectedException('\MZ\Exception\ValidationException');
        try {
            $caixa->insert();
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(['descricao'], array_keys($e->getErrors()));
            throw $e;
        }
    }

    public function testUpdate()
    {
        $caixa = new Caixa();
        $caixa->setDescricao('Caixa 3');
        $caixa->insert();

        $caixa->setDescricao('Cash register 3');
        $caixa->update();
    }

    public function testFind()
    {
        $caixa = new Caixa();
        $caixa->setDescricao('Caixa 4');
        $caixa->insert();

        $found_caixa = Caixa::findByID($caixa->getID());
        $this->assertEquals($caixa, $found_caixa);
        $found_caixa->loadByID($caixa->getID());
        $this->assertEquals($caixa, $found_caixa);
        $found_caixa = Caixa::findByDescricao('Caixa 4');
        $this->assertEquals($caixa, $found_caixa);
        $found_caixa->loadByDescricao('Caixa 4');
        $this->assertEquals($caixa, $found_caixa);

        $caixa_sec = new Caixa();
        $caixa_sec->setDescricao('Caixa 48');
        $caixa_sec->insert();

        $caixas = Caixa::findAll(['search' => 'Caixa 4'], [], 2, 0);
        $this->assertEquals([$caixa, $caixa_sec], $caixas);

        $count = Caixa::count(['search' => 'Caixa 4']);
        $this->assertEquals(2, $count);
    }

    public function testSerie()
    {
        global $app;

        $old_value = $app->getSystem()->getSettings()->getValue('Sistema', 'Fiscal.Mostrar');
        $app->getSystem()->getSettings()->setValue('Sistema', 'Fiscal.Mostrar', true);
        $caixa = new Caixa();
        $caixa->setDescricao('Caixa 6');
        $caixa->setSerie(4);
        $caixa->setNumeroInicial(100);
        $caixa->setAtivo('Y');
        $caixa->insert();
        $app->getSystem()->getSettings()->setValue('Sistema', 'Fiscal.Mostrar', $old_value);

        $found_caixa = Caixa::findBySerie(4);
        $this->assertEquals($caixa, $found_caixa);

        $caixa->setAtivo('N');
        $caixa->update();

        $found_caixa = Caixa::findBySerie(4);
        $this->assertEquals(new Caixa(), $found_caixa);

        Caixa::resetBySerie(4);
        $found_caixa = Caixa::findByID($caixa->getID());
        $this->assertEquals($caixa, $found_caixa);

        $caixa->setAtivo('Y');
        $caixa->update();
        Caixa::resetBySerie(4);
        $found_caixa = Caixa::findBySerie(4);
        $new_caixa = new Caixa($caixa);
        $new_caixa->setNumeroInicial(1);
        $this->assertEquals($new_caixa, $found_caixa);
    }

    public function testSearch()
    {
        $caixa = new Caixa();
        $caixa->setDescricao('Caixa 5');
        $caixa->insert();

        $found_caixa = Caixa::find(['search' => 'xa 5']);
        $this->assertEquals($caixa, $found_caixa);
    }

    public function testValidate()
    {
        global $app;
        
        $old_value = $app->getSystem()->getSettings()->getValue('Sistema', 'Fiscal.Mostrar');
        $app->getSystem()->getSettings()->setValue('Sistema', 'Fiscal.Mostrar', true);
        $this->setExpectedException('\MZ\Exception\ValidationException');
        try {
            $caixa = new Caixa();
            $caixa->setAtivo('A');
            $caixa->insert();
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                ['descricao', 'serie', 'numeroinicial', 'ativo'],
                array_keys($e->getErrors())
            );
            throw $e;
        } finally {
            $app->getSystem()->getSettings()->setValue('Sistema', 'Fiscal.Mostrar', $old_value);
        }
    }

    public function testDesativarEmUso()
    {
        $caixa = new Caixa();
        $caixa->setDescricao('Caixa 7');
        $caixa->setAtivo('Y');
        $caixa->insert();

        $sessao = new Sessao();
        $sessao->setAberta('Y');
        $sessao->insert();

        $cliente = new Cliente();
        $cliente->setNomeCompleto('Fulano da Silva');
        $cliente->setGenero(Cliente::GENERO_MASCULINO);
        $cliente->setEmail('fulano@email.com');
        $cliente->setLogin('fulano');
        $cliente->setSenha('1234');
        $cliente->insert();

        $funcao = \MZ\Employee\Funcao::find([], ['id' => 1]);

        $funcionario = new \MZ\Employee\Funcionario();
        $funcionario->setFuncaoID($funcao->getID());
        $funcionario->setClienteID($cliente->getID());
        $funcionario->insert();

        $movimentacao = new Movimentacao();
        $movimentacao->setSessaoID($sessao->getID());
        $movimentacao->setCaixaID($caixa->getID());
        $movimentacao->setFuncionarioAberturaID($funcionario->getID());
        $movimentacao->setAberta('Y');
        $movimentacao->insert();

        $this->setExpectedException('\MZ\Exception\ValidationException');
        try {
            $caixa->setAtivo('N');
            $caixa->update();
            $this->fail('Não deveria ter desativado um caixa em uso');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(['ativo'], array_keys($e->getErrors()));
            throw $e;
        } finally {
            try {
                $movimentacao->setDataFechamento(DB::now());
                $movimentacao->setFuncionarioFechamentoID($funcionario->getID());
                $movimentacao->update();
        
                $sessao->setAberta('N');
                $sessao->update();
            } catch (\Exception $e) {
                $this->fail($e->getMessage());
            }
        }
    }

    public function testDelete()
    {
        $caixa = new Caixa();
        $caixa->setDescricao('Caixa 9');
        $caixa->insert();
        $caixa->delete();
        $found_caixa = Caixa::findByID($caixa->getID());
        $this->assertEquals(new Caixa(), $found_caixa);
        $caixa->setID('');
        $this->setExpectedException('\Exception');
        $caixa->delete();
    }
}
