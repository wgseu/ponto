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
 * @author  Francimar Alves <mazinsw@gmail.com>
 */
namespace MZ\Product;

use \MZ\Database\DB;

class ServicoTest extends \PHPUnit_Framework_TestCase
{
    public function testFromArray()
    {
        $old_servico = new Servico([
            'id' => 123,
            'nome' => 'Serviço',
            'descricao' => 'Serviço',
            'detalhes' => 'Serviço',
            'tipo' => Servico::TIPO_EVENTO,
            'obrigatorio' => 'Y',
            'datainicio' => '2016-12-25 12:15:00',
            'datafim' => '2016-12-25 12:15:00',
            'valor' => 12.3,
            'individual' => 'Y',
            'ativo' => 'Y',
        ]);
        $servico = new Servico();
        $servico->fromArray($old_servico);
        $this->assertEquals($servico, $old_servico);
        $servico->fromArray(null);
        $this->assertEquals($servico, new Servico());
    }

    public function testFilter()
    {
        $old_servico = new Servico([
            'id' => 1234,
            'nome' => 'Serviço filter',
            'descricao' => 'Serviço filter',
            'detalhes' => 'Serviço filter',
            'tipo' => Servico::TIPO_TAXA,
            'obrigatorio' => 'Y',
            'datainicio' => '2016-12-23 12:15:00',
            'datafim' => '2016-12-23 12:15:00',
            'valor' => 12.3,
            'individual' => 'Y',
            'ativo' => 'Y',
        ]);
        $servico = new Servico([
            'id' => 321,
            'nome' => ' Serviço <script>filter</script> ',
            'descricao' => ' Serviço <script>filter</script> ',
            'detalhes' => ' Serviço <script>filter</script> ',
            'tipo' => Servico::TIPO_TAXA,
            'obrigatorio' => 'Y',
            'datainicio' => '23/12/2016 12:15',
            'datafim' => '23/12/2016 12:15',
            'valor' => '12,3',
            'individual' => 'Y',
            'ativo' => 'Y',
        ]);
        $servico->filter($old_servico);
        $this->assertEquals($old_servico, $servico);
    }

    public function testPublish()
    {
        $servico = new Servico();
        $values = $servico->publish();
        $allowed = [
            'id',
            'nome',
            'descricao',
            'detalhes',
            'tipo',
            'obrigatorio',
            'datainicio',
            'datafim',
            'valor',
            'individual',
            'ativo',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $servico = new Servico();
        $servico->setObrigatorio(null);
        $servico->setIndividual(null);
        $servico->setAtivo(null);
        try {
            $servico->insert();
            $this->fail('Não deveria ter cadastrado o serviço');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'nome',
                    'descricao',
                    'tipo',
                    'obrigatorio',
                    'valor',
                    'individual',
                    'ativo',
                ],
                array_keys($e->getErrors())
            );
        }
        $servico->setNome('Serviço to insert');
        $servico->setDescricao('Serviço to insert');
        $servico->setTipo(Servico::TIPO_EVENTO);
        $servico->setObrigatorio('Y');
        $servico->setValor(12.3);
        $servico->setIndividual('Y');
        $servico->setAtivo('Y');
        $servico->setDataInicio(DB::now());
        $servico->setDataFim(DB::now('+1 day'));
        $servico->insert();
    }

    public function testUpdate()
    {
        $servico = new Servico();
        $servico->setNome('Serviço to update');
        $servico->setDescricao('Serviço to update');
        $servico->setTipo(Servico::TIPO_TAXA);
        $servico->setObrigatorio('N');
        $servico->setValor(12.3);
        $servico->setIndividual('N');
        $servico->setAtivo('N');
        $servico->insert();
        $servico->setNome('Serviço updated');
        $servico->setDescricao('Serviço updated');
        $servico->setDetalhes('Serviço updated');
        $servico->setTipo(Servico::TIPO_EVENTO);
        $servico->setObrigatorio('N');
        $servico->setDataInicio('2016-12-25 14:15:00');
        $servico->setDataFim('2016-12-25 14:15:00');
        $servico->setValor(21.4);
        $servico->setIndividual('N');
        $servico->setAtivo('N');
        $servico->update();
        $found_servico = Servico::findByID($servico->getID());
        $this->assertEquals($servico, $found_servico);
    }

    public function testDelete()
    {
        $servico = new Servico();
        $servico->setNome('Serviço to delete');
        $servico->setDescricao('Serviço to delete');
        $servico->setTipo(Servico::TIPO_TAXA);
        $servico->setObrigatorio('Y');
        $servico->setValor(12.3);
        $servico->setIndividual('Y');
        $servico->setAtivo('Y');
        $servico->insert();
        $servico->delete();
        $found_servico = Servico::findByID($servico->getID());
        $this->assertEquals(new Servico(), $found_servico);
        $servico->setID('');
        $this->setExpectedException('\Exception');
        $servico->delete();
    }

    public function testFind()
    {
        $servico = new Servico();
        $servico->setNome('Serviço find');
        $servico->setDescricao('Serviço find');
        $servico->setTipo(Servico::TIPO_EVENTO);
        $servico->setDataInicio(DB::now());
        $servico->setDataFim(DB::now('+1 day'));
        $servico->setObrigatorio('Y');
        $servico->setValor(12.3);
        $servico->setIndividual('Y');
        $servico->setAtivo('Y');
        $servico->insert();
        $found_servico = Servico::findByID($servico->getID());
        $this->assertEquals($servico, $found_servico);
        $found_servico->loadByID($servico->getID());
        $this->assertEquals($servico, $found_servico);

        $servico_sec = new Servico();
        $servico_sec->setNome('Serviço find second');
        $servico_sec->setDescricao('Serviço find second');
        $servico_sec->setTipo(Servico::TIPO_TAXA);
        $servico_sec->setObrigatorio('Y');
        $servico_sec->setValor(12.3);
        $servico_sec->setIndividual('Y');
        $servico_sec->setAtivo('Y');
        $servico_sec->insert();

        $servicos = Servico::findAll(['search' => 'Serviço find'], [], 2, 0);
        $this->assertEquals([$servico, $servico_sec], $servicos);

        $count = Servico::count(['search' => 'Serviço find']);
        $this->assertEquals(2, $count);
    }
}
