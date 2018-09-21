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
namespace MZ\Wallet;

class BancoTest extends \MZ\Framework\TestCase
{
    public function testFromArray()
    {
        $old_banco = new Banco([
            'id' => 123,
            'numero' => 'Banco',
            'razaosocial' => 'Banco',
            'agenciamascara' => 'Banco',
            'contamascara' => 'Banco',
        ]);
        $banco = new Banco();
        $banco->fromArray($old_banco);
        $this->assertEquals($banco, $old_banco);
        $banco->fromArray(null);
        $this->assertEquals($banco, new Banco());
    }

    public function testFilter()
    {
        $old_banco = new Banco([
            'id' => 1234,
            'numero' => 'Banco filter',
            'razaosocial' => 'Banco filter',
            'agenciamascara' => 'Banco filter',
            'contamascara' => 'Banco filter',
        ]);
        $banco = new Banco([
            'id' => 321,
            'numero' => ' Banco <script>filter</script> ',
            'razaosocial' => ' Banco <script>filter</script> ',
            'agenciamascara' => ' Banco <script>filter</script> ',
            'contamascara' => ' Banco <script>filter</script> ',
        ]);
        $banco->filter($old_banco);
        $this->assertEquals($old_banco, $banco);
    }

    public function testPublish()
    {
        $banco = new Banco();
        $values = $banco->publish();
        $allowed = [
            'id',
            'numero',
            'razaosocial',
            'agenciamascara',
            'contamascara',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $banco = new Banco();
        try {
            $banco->insert();
            $this->fail('Não deveria ter cadastrado o banco');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'numero',
                    'razaosocial',
                ],
                array_keys($e->getErrors())
            );
        }
        $banco->setNumero('Banco to insert');
        $banco->setRazaoSocial('Banco to insert');
        $banco->insert();
    }

    public function testUpdate()
    {
        $banco = new Banco();
        $banco->setNumero('Banco to update');
        $banco->setRazaoSocial('Banco to update');
        $banco->insert();
        $banco->setNumero('Banco updated');
        $banco->setRazaoSocial('Banco updated');
        $banco->setAgenciaMascara('Banco updated');
        $banco->setContaMascara('Banco updated');
        $banco->update();
        $found_banco = Banco::findByID($banco->getID());
        $this->assertEquals($banco, $found_banco);
        $banco->setID('');
        $this->expectException('\Exception');
        $banco->update();
    }

    public function testDelete()
    {
        $banco = new Banco();
        $banco->setNumero('Banco to delete');
        $banco->setRazaoSocial('Banco to delete');
        $banco->insert();
        $banco->delete();
        $banco->clean(new Banco());
        $found_banco = Banco::findByID($banco->getID());
        $this->assertEquals(new Banco(), $found_banco);
        $banco->setID('');
        $this->expectException('\Exception');
        $banco->delete();
    }

    public function testFind()
    {
        $banco = new Banco();
        $banco->setNumero('Banco find');
        $banco->setRazaoSocial('Banco find');
        $banco->insert();
        $found_banco = Banco::find(['id' => $banco->getID()]);
        $this->assertEquals($banco, $found_banco);
        $found_banco = Banco::findByID($banco->getID());
        $this->assertEquals($banco, $found_banco);
        $found_banco = Banco::findByRazaoSocial($banco->getRazaoSocial());
        $this->assertEquals($banco, $found_banco);
        $found_banco = Banco::findByNumero($banco->getNumero());
        $this->assertEquals($banco, $found_banco);

        $banco_sec = new Banco();
        $banco_sec->setNumero('Banco find second');
        $banco_sec->setRazaoSocial('Banco find second');
        $banco_sec->insert();

        $bancos = Banco::findAll(['search' => 'Banco find'], [], 2, 0);
        $this->assertEquals([$banco, $banco_sec], $bancos);

        $count = Banco::count(['search' => 'Banco find']);
        $this->assertEquals(2, $count);
    }
}
