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
namespace MZ\Core;

class SettingsTest extends \MZ\Framework\TestCase
{
    public function testInitialValues()
    {
        $values = [
            'entry' => 'value',
            'multi' => [
                'level' => 1,
                'key' => 'multi.level'
            ]
        ];
        $settings = new Settings($values);
        $this->assertEquals($values, $settings->getValues());
        $this->assertEquals('multi.level', $settings->getValue('multi', 'key'));
        $this->assertEquals('value', $settings->getValue('entry'));
    }

    public function testSetValues()
    {
        $settings = new Settings();
        $settings->setValue('section', 'key', 'value');
        $this->assertEquals('value', $settings->getValue('section', 'key'));
        $settings->setValue('entry', 'key', 123456);
        $this->assertEquals(123456, $settings->getValue('entry', 'key'));
        $settings->setValue('section', 'key', 3.14);
        $this->assertEquals(3.14, $settings->getValue('section', 'key'));
    }

    public function testDefaultValues()
    {
        $settings = new Settings(['data' => '123456']);
        $this->assertEquals('123456', $settings->getValue('data', null, 'default'));
        $this->assertEquals('default', $settings->getValue('key', null, 'default'));
        $this->assertEquals('value', $settings->getValue('section', 'key', 'value'));
    }

    public function testIgnoreStaticEntries()
    {
        $settings = new Settings([
            'path' => [],
            'db' => [],
            'other' => []
        ]);
        $this->assertEquals(['other' => []], $settings->getValues());
    }

    public function testOverrideStatic()
    {
        $settings = new Settings([
            'path' => '123',
            'db' => '456',
        ]);
        $settings->addValues([
            'path' => 'teste',
            'db' => 'new value',
        ]);
        $this->assertEquals(null, $settings->getValue('path'));
        $this->assertEquals(null, $settings->getValue('db'));
        $this->expectException('\Exception');
        try {
            $settings->deleteEntry('db', null);
            $this->fail('Must trow \Exception');
        } catch (\Exception $e) {
        }
        $settings->deleteEntry('path', null);
    }

    public function testAddValues()
    {
        $settings = new Settings(['path' => '123456']);
        $settings->addValues([
            'path' => 'teste',
            'value' => 'new value',
        ]);
        $this->assertEquals('123456', $settings->getValue('path', null, '123456'));
        $this->assertEquals('new value', $settings->getValue('value'));
    }
}
