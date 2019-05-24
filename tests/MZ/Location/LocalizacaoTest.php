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

use MZ\Account\ClienteTest;
use MZ\Location\BairroTest;
use MZ\Exception\ValidationException;

class LocalizacaoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid localização
     * @param string $logradouro Localização logradouro
     * @return Localizacao
     */
    public static function build($logradouro = null)
    {
        $last = Localizacao::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $cliente = ClienteTest::create();
        $bairro = BairroTest::create();
        $localizacao = new Localizacao();
        $localizacao->setClienteID($cliente->getID());
        $localizacao->setBairroID($bairro->getID());
        $localizacao->setLogradouro($logradouro ?: "Localização {$id}");
        $localizacao->setNumero("{$id}");
        return $localizacao;
    }

    /**
     * Create a localização on database
     * @param string $logradouro Localização logradouro
     * @return Localizacao
     */
    public static function create($logradouro = null)
    {
        $localizacao = self::build($logradouro);
        $localizacao->insert();
        return $localizacao;
    }

    public function testFind()
    {
        $localizacao = self::create();
        $condition = ['logradouro' => $localizacao->getLogradouro()];
        $found_localizacao = Localizacao::find($condition);
        $this->assertEquals($localizacao, $found_localizacao);
        list($found_localizacao) = Localizacao::findAll($condition, [], 1);
        $this->assertEquals($localizacao, $found_localizacao);
        $this->assertEquals(1, Localizacao::count($condition));
    }

    public function testFinds()
    {
        $loc = self::create();

        $cliente = $loc->findClienteID();
        $this->assertEquals($loc->getClienteID(), $cliente->getID());

        $bairro = $loc->findBairroID();
        $this->assertEquals($loc->getBairroID(), $bairro->getID());

        $zona = $loc->findZonaID();
        $this->assertEquals($loc->getZonaID(), $zona->getID());

        $localizacao = $loc->findByClienteIDApelido($cliente->getID(), $loc->getApelido());
        $this->assertInstanceOf(get_class($loc), $localizacao);
    }

    public function testAdd()
    {
        $localizacao = self::build();
        $localizacao->insert();
        $this->assertTrue($localizacao->exists());
    }

    public function testAddInvalid()
    {
        $loc = self::build();
        $loc->setClienteID(null);
        $loc->setBairroID(null);
        $loc->setCEP('dfgh3489938338');
        $loc->setLogradouro(null);
        $loc->setNumero(null);
        $loc->setTipo(null);
        $loc->setMostrar(null);
        try {
            $loc->insert();
            $loc->fail('Não cadastrar valor nulo');
        } catch (ValidationException $e) {
            $this->assertEquals(
                ['clienteid', 'bairroid', 'cep', 'logradouro', 'numero', 'tipo', 'mostrar'],
                array_keys($e->getErrors())
            );
        }
    }

    public function testTranslate()
    {
        $loc = self::build();
        $loc->setClienteID(1);
        $loc->setApelido('teste');
        $loc->insert();
        try {
            $loc->insert();
            $this->fail('fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['clienteid', 'apelido'], array_keys($e->getErrors()));
        }
    }

    public function testIsSame()
    {
        $localizacao = self::build();
        $this->assertFalse($localizacao->isSame($localizacao));
        //---------------------
        $loc = self::create();
        $localizacao = self::build();
        $localizacao->setLogradouro('Testinho');
        $this->assertFalse($loc->isSame($localizacao));
        //---------------------
        $loc = self::create();
        $localizacao = self::build();
        $localizacao->setLogradouro($loc->getLogradouro());
        $localizacao->setNumero('888');
        $this->assertFalse($loc->isSame($localizacao));
        //---------------------
        $loc = self::build();
        $loc->setTipo(Localizacao::TIPO_APARTAMENTO);
        $loc->insert();
        $localizacao = self::build();
        $localizacao->setLogradouro($loc->getLogradouro());
        $localizacao->setNumero($loc->getNumero());
        $this->assertTrue($loc->isSame($localizacao));
        //---------------------
        $loc = self::build();
        $loc->setTipo(Localizacao::TIPO_APARTAMENTO);
        $loc->insert();
        $localizacao = self::build();
        $localizacao->setLogradouro($loc->getLogradouro());
        $localizacao->setNumero($loc->getNumero());
        $localizacao->setTipo($loc->getTipo());
        $localizacao->setCondominio('Teste condominio');
        $this->assertFalse($loc->isSame($localizacao));
        //---------------------
        $loc = self::build();
        $loc->setTipo(Localizacao::TIPO_APARTAMENTO);
        $loc->insert();
        $localizacao = self::build();
        $localizacao->setLogradouro($loc->getLogradouro());
        $localizacao->setNumero($loc->getNumero());
        $localizacao->setTipo($loc->getTipo());
        $localizacao->setCondominio($loc->getCondominio());
        $localizacao->setBloco('Teste');
        $this->assertFalse($loc->isSame($localizacao));
        //---------------------
        $loc = self::build();
        $loc->setTipo(Localizacao::TIPO_APARTAMENTO);
        $loc->insert();
        $localizacao = self::build();
        $localizacao->setLogradouro($loc->getLogradouro());
        $localizacao->setNumero($loc->getNumero());
        $localizacao->setTipo($loc->getTipo());
        $localizacao->setCondominio($loc->getCondominio());
        $localizacao->setBloco($loc->getBloco());
        $localizacao->setApartamento('teste');
        $this->assertFalse($loc->isSame($localizacao));
        //---------------------
        $loc = self::build();
        $loc->setTipo(Localizacao::TIPO_APARTAMENTO);
        $loc->insert();
        $localizacao = self::build();
        $localizacao->setBairroID(77);
        $localizacao->setLogradouro($loc->getLogradouro());
        $localizacao->setNumero($loc->getNumero());
        $localizacao->setTipo($loc->getTipo());
        $localizacao->setCondominio($loc->getCondominio());
        $localizacao->setBloco($loc->getBloco());
        $localizacao->setApartamento($loc->getApartamento());
        $this->assertTrue($loc->isSame($localizacao));
    }

    public function testGetOptions()
    {
        $localizacao = self::create();
        $options = Localizacao::getTipoOptions($localizacao->getTipo());
        $this->assertEquals($localizacao->getTipo(), $options);
    }

    public function testUpdate()
    {
        $localizacao = self::create();
        $localizacao->update();
        $this->assertTrue($localizacao->exists());
    }

    public function testDelete()
    {
        $localizacao = self::create();
        $localizacao->delete();
        $localizacao->loadByID();
        $this->assertFalse($localizacao->exists());
    }
}
