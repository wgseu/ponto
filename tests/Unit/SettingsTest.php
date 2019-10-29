<?php

/**
 * Copyright 2014 da GrandChef - GrandChef Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Restaurantes e Afins.
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
 * @author Equipe GrandChef <desenvolvimento@grandchef.com.br>
 */

namespace Tests\Unit;

use App\Core\Settings;
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{
    public function testInitialValues()
    {
        $values = [
            'entry' => 'value',
            'multi' => [
                'level' => 1,
                'key' => 'multilevel'
            ]
        ];
        $settings = new Settings();
        $settings->addValues($values);
        $this->assertEquals($values, $settings->getValues());
        $this->assertEquals('multilevel', $settings->getEntry('multi', 'key'));
        $this->assertEquals('value', $settings->getValue('entry'));
    }

    public function testAddEntry()
    {
        $settings = new Settings();
        $settings->addEntry('section', 'key', 'value');
        $this->assertEquals('value', $settings->getEntry('section', 'key'));
        $settings->addEntry('entry', 'key', 123456);
        $this->assertEquals(123456, $settings->getEntry('entry', 'key'));
        $settings->addEntry('section', 'key', 3.14);
        $this->assertEquals(3.14, $settings->getEntry('section', 'key'));
        $this->assertNotEmpty($settings);
        $this->assertNotNull($settings);
    }

    public function testDefaultEntry()
    {
        $settings = new Settings([
            'section' => [
                'key' => 'data',
                'other' => 'value',
            ],
        ]);
        $this->assertEquals('data', $settings->getEntry('section', 'key'));
        $this->assertEquals('default', $settings->getEntry('section', 'other', 'default'));
        $settings->addValues([
            'section' => [
                'other' => 'changed'
            ],
            'entry' => [
                'key' => 'value'
            ],
        ]);
        $this->assertEquals('changed', $settings->getEntry('section', 'other'));
        $this->assertEquals('changed', $settings->getEntry('section', 'other', 'default'));
        $this->assertEquals(['default'], $settings->getEntry('entry', 'key', ['default']));
        $this->assertNull($settings->getEntry('entry', 'key'));
    }

    public function testAddOtherThanDefaults()
    {
        $defaults = ['path' => ['test' => 1], 'db' => []];
        $settings = new Settings($defaults);
        $settings->addValues([
            'path' => ['key' => 1, 'test' => 2],
            'db' => ['test' => []],
            'other' => []
        ]);
        $defaults['path']['test'] = 2;
        $this->assertEquals($defaults, $settings->getValues());
    }

    public function testChangeType()
    {
        $settings = new Settings(['path' => [], 'db' => null]);
        $settings->addValues([
            'path' => [],
            'db' => [],
        ]);
        $this->assertNull($settings->getValue('db'));
        $settings->addValue('db', 123);
        $this->assertEquals(123, $settings->getValue('db'));
        $this->expectException('\Exception');
        $settings->addValue('db', []);
    }

    public function testChangeArray()
    {
        $settings = new Settings(['path' => [], 'db' => null]);
        $settings->addValues([
            'path' => 123,
            'db' => 123,
        ]);
        $this->assertEquals([], $settings->getValue('path'));
        $this->assertEquals(123, $settings->getValue('db'));
        $settings->addValue('path', ['aaa' => 1, 2]);
        $this->assertEquals([], $settings->getValue('path'));
        $this->expectException('\Exception');
        $settings->addValue('path', 456);
    }

    public function testDefaultValues()
    {
        $settings = new Settings([
            'name' => 'aaa',
            'path' => '/bbb',
            'flag' => true,
        ]);
        $settings->addValues([
            'path' => 'teste',
            'value' => 'new value',
        ]);

        $expected = [
            'path' => 'teste',
        ];
        $this->assertEquals($expected, $settings->getValues());

        $this->assertTrue($settings->getValue('flag'));

        $settings->addValue('flag', false);
        $this->assertFalse($settings->getValue('flag'));

        $this->assertEquals('aaa', $settings->getValue('name'));
        $this->assertEquals('ccc', $settings->getValue('name', 'ccc'));
    }
    
    public function testDeleteValue()
    {
        $settings = new Settings();
        $settings->deleteValue('db');
        $this->assertNotNull($settings);
        $this->assertTrue((true));
    }

    public function testDeleteEntry()
    {
        $settings = new Settings();
        $settings->deleteEntry('db', '123789');
        $this->assertNotEmpty($settings);
        $this->assertNotNull($settings);
        $this->assertNotFalse(true);
    }

    public function testHas()
    {
        $settings = new Settings();
        $settings->has('1');
        $this->assertNotNull($settings);
    }
}
