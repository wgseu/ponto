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

    public function testAdd()
    {
        $localizacao = self::build();
        $localizacao->insert();
        $this->assertTrue($localizacao->exists());
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
