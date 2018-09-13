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
namespace MZ\Environment;

class SetorTest extends \MZ\Framework\TestCase
{
    public function testFromArray()
    {
        $old_setor = new Setor([
            'id' => 123,
            'nome' => 'Setor',
            'descricao' => 'Setor',
        ]);
        $setor = new Setor();
        $setor->fromArray($old_setor);
        $this->assertEquals($setor, $old_setor);
        $setor->fromArray(null);
        $this->assertEquals($setor, new Setor());
    }

    public function testFilter()
    {
        $old_setor = new Setor([
            'id' => 1234,
            'nome' => 'Setor filter',
            'descricao' => 'Setor filter',
        ]);
        $setor = new Setor([
            'id' => 321,
            'nome' => ' Setor <script>filter</script> ',
            'descricao' => ' Setor <script>filter</script> ',
        ]);
        $setor->filter($old_setor);
        $this->assertEquals($old_setor, $setor);
    }

    public function testPublish()
    {
        $setor = new Setor();
        $values = $setor->publish();
        $allowed = [
            'id',
            'nome',
            'descricao',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $setor = new Setor();
        try {
            $setor->insert();
            $this->fail('Não deveria ter cadastrado o setor');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'nome',
                ],
                array_keys($e->getErrors())
            );
        }
        $setor->setNome('Setor to insert');
        $setor->insert();
    }

    public function testUpdate()
    {
        $setor = new Setor();
        $setor->setNome('Setor to update');
        $setor->insert();
        $setor->setNome('Setor updated');
        $setor->setDescricao('Setor updated');
        $setor->update();
        $found_setor = Setor::findByID($setor->getID());
        $this->assertEquals($setor, $found_setor);
        $setor->setID('');
        $this->expectException('\Exception');
        $setor->update();
    }

    public function testDelete()
    {
        $setor = new Setor();
        $setor->setNome('Setor to delete');
        $setor->insert();
        $setor->delete();
        $setor->clean(new Setor());
        $found_setor = Setor::findByID($setor->getID());
        $this->assertEquals(new Setor(), $found_setor);
        $setor->setID('');
        $this->expectException('\Exception');
        $setor->delete();
    }

    public function testFind()
    {
        $setor = new Setor();
        $setor->setNome('Setor find');
        $setor->insert();
        $found_setor = Setor::find(['id' => $setor->getID()]);
        $this->assertEquals($setor, $found_setor);
        $found_setor = Setor::findByID($setor->getID());
        $this->assertEquals($setor, $found_setor);
        $found_setor->loadByID($setor->getID());
        $this->assertEquals($setor, $found_setor);
        $found_setor = Setor::findByNome($setor->getNome());
        $this->assertEquals($setor, $found_setor);
        $found_setor->loadByNome($setor->getNome());
        $this->assertEquals($setor, $found_setor);

        $setor_sec = new Setor();
        $setor_sec->setNome('Setor find second');
        $setor_sec->insert();

        $setores = Setor::findAll(['search' => 'Setor find'], [], 2, 0);
        $this->assertEquals([$setor, $setor_sec], $setores);

        $count = Setor::count(['search' => 'Setor find']);
        $this->assertEquals(2, $count);
    }
}
