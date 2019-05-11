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
namespace MZ\Location;

use MZ\Location\CidadeTest;
use MZ\Location\BairroTest;
use MZ\Exception\ValidationException;

class EnderecoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid endereço
     * @param string $logradouro Endereço logradouro
     * @return Endereco
     */
    public static function build($logradouro = null)
    {
        $last = Endereco::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $cidade = CidadeTest::create();
        $bairro = BairroTest::create();
        $endereco = new Endereco();
        $endereco->setCidadeID($cidade->getID());
        $endereco->setBairroID($bairro->getID());
        $endereco->setLogradouro($logradouro ?: "Endereço {$id}");
        $endereco->setCEP(\str_pad("{$id}", 8, '0'));
        return $endereco;
    }

    /**
     * Create a endereço on database
     * @param string $logradouro Endereço logradouro
     * @return Endereco
     */
    public static function create($logradouro = null)
    {
        $endereco = self::build($logradouro);
        $endereco->insert();
        return $endereco;
    }

    public function testFind()
    {
        $endereco = self::create();
        $condition = ['logradouro' => $endereco->getLogradouro()];
        $found_endereco = Endereco::find($condition);
        $this->assertEquals($endereco, $found_endereco);
        list($found_endereco) = Endereco::findAll($condition, [], 1);
        $this->assertEquals($endereco, $found_endereco);
        $this->assertEquals(1, Endereco::count($condition));
    }

    public function testFinds()
    {
        $endereco = self::create();

        $cidade = $endereco->findCidadeID();
        $this->assertEquals($endereco->getCidadeID(), $cidade->getID());

        $bairro = $endereco->findBairroID();
        $this->assertEquals($endereco->getBairroID(), $bairro->getID());

        $bairroByCep = $endereco->findByCEP($endereco->getCEP());
        $this->assertInstanceOf(get_class($endereco), $bairroByCep);

        $bairro2 = $endereco->findByBairroIDLogradouro($endereco->getID(), $endereco->getLogradouro());
        $this->assertInstanceOf(get_class($endereco), $bairro2);
    }

    public function testAdd()
    {
        $endereco = self::build();
        $endereco->insert();
        $this->assertTrue($endereco->exists());
    }

    public function testAddInvalid()
    {
        $endereco = self::build();
        $endereco->setCidadeID(null);
        $endereco->setBairroID(null);
        $endereco->setLogradouro(null);
        $endereco->setCEP('sdkfmn3902384');
        try {
            $endereco->insert();
            $this->fail('Não cadastrar');
        } catch (ValidationException $e) {
            $this->assertEquals(['cidadeid', 'bairroid', 'logradouro', 'cep'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $endereco = self::build();
        $endereco->setCEP('1234567899');
        $endereco->insert();
        try {
            $endereco->setBairroID(6);
            $endereco->setLogradouro('teste');
            $endereco->insert();
            $this->fail('Fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['cep'], array_keys($e->getErrors()));
        }
        //------------------------
        $endereco = self::build();
        $endereco->setBairroID(1);
        $endereco->setLogradouro('Rua sem saida');
        $endereco->insert();
        try {
            $endereco->insert();
            $this->fail('Fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['bairroid', 'logradouro'], array_keys($e->getErrors()));
        }


    }

    public function testUpdate()
    {
        $endereco = self::create();
        $endereco->update();
        $this->assertTrue($endereco->exists());
    }

    public function testDelete()
    {
        $endereco = self::create();
        $endereco->delete();
        $endereco->loadByID();
        $this->assertFalse($endereco->exists());
    }
}
