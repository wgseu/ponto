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

use Tests\TestCase;
use App\Core\Settings;

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

    public function testAddValueToInexistentKey()
    {
        $defaults = ['key' => 'value'];
        $settings = new Settings($defaults);
        $this->expectException('\Exception');
        $settings->addValue('other_key', 'val');
    }

    public function testAddEntryToInexistentPath()
    {
        $defaults = ['section' => ['key' => 'value']];
        $settings = new Settings($defaults);
        $this->expectException('\Exception');
        $settings->addEntry('section', 'other_key', 'val');
    }

    public function testAddOtherThanDefaults()
    {
        $defaults = ['path' => ['test' => 1], 'db' => null];
        $settings = new Settings($defaults);
        $settings->addValues([
            'path' => ['key' => 1, 'test' => 2],
            'db' => [],
            'other' => []
        ]);
        $defaults['path']['test'] = 2;
        $this->assertEquals($defaults, $settings->getValues(true));
    }

    public function testAddValueDifferentType()
    {
        $settings = new Settings(['path' => ['key' => 3], 'db' => null]);
        $settings->addValues([
            'path' => 2,
            'db' => [],
        ]);
        $this->assertEquals(3, $settings->getEntry('path', 'key'));
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
        $settings->addValue('a', 1);
        $this->assertTrue($settings->has('a'));
        $settings->deleteValue('a');
        $this->assertFalse($settings->has('a'));
    }

    public function testDeleteEntry()
    {
        $settings = new Settings();
        $settings->deleteEntry('db', '123789');
        $this->assertNotEmpty($settings);
        $this->assertNotNull($settings);
        $this->assertNotFalse(true);
    }

    public function testHasKey()
    {
        $settings = new Settings();
        $settings->addValues(['a' => 1]);
        $this->assertTrue($settings->has('a'));
        $this->assertFalse($settings->has('b'));
    }

    public function testHasSectionKey()
    {
        $settings = new Settings();
        $settings->addValues(['a' => ['b' => 2]]);
        $this->assertTrue($settings->has('a', 'b'));
        $this->assertFalse($settings->has('b', 'c'));
    }

    public function testAddEntryArraySameAsDefault()
    {
        $settings = new Settings(['a' => ['b' => ['c' => 1]]]);
        $settings->addEntry('a', 'b', ['c' => 2]);
        $settings->addEntry('a', 'b', ['c' => 1]);
        $this->assertEquals([], $settings->getValues());
    }

    public function testAddEntryValueSameAsDefault()
    {
        $settings = new Settings(['a' => ['b' => 1]]);
        $settings->addEntry('a', 'b', 2);
        $settings->addEntry('a', 'b', 1);
        $this->assertEquals([], $settings->getValues());
    }

    public function testAddValueSameAsDefault()
    {
        $settings = new Settings(['a' => 1]);
        $settings->addValue('a', 2);
        $settings->addValue('a', 1);
        $this->assertEquals([], $settings->getValues());
    }

    public function testAddValueArraySameAsDefault()
    {
        $settings = new Settings(['a' => ['b' => 1]]);
        $settings->addValue('a', ['b' => 2]);
        $settings->addValue('a', ['b' => 1]);
        $this->assertEquals([], $settings->getValues());
    }

    public function testAddEntryReplaceValue()
    {
        $settings = new Settings();
        $settings->addValues(['a' => ['b' => ['c' => 1, 'd' => 3]]]);
        $settings->addEntry('a', 'b', ['c' => 2]);
        $this->assertEquals(['a' => ['b' => ['c' => 2, 'd' => 3]]], $settings->getValues());
    }

    public function testAddValuesNoArray()
    {
        $settings = new Settings();
        $settings->addValues(1);
        $this->assertEquals([], $settings->getValues());
    }

    public function testAddValuesSameAsDefault()
    {
        $settings = new Settings(['a' => ['b' => 1]]);
        $settings->addEntry('a', 'b', 2);
        $settings->addValues(['a' => ['b' => 1]]);
        $this->assertEquals([], $settings->getValues());
    }

    public function testAddEntryDifferentType()
    {
        $settings = new Settings(['a' => ['b' => ['c' => 1]]]);
        $this->expectException('\Exception');
        $settings->addEntry('a', 'b', 2);
    }

    public function testLoad()
    {
        $settings = new Settings(['settings_file' => ['key' => 'value']]);
        $settings->load(self::resourcePath());
        $this->assertEquals('value', $settings->getEntry('settings_file', 'key'));
    }

    public function testLoadInexistingFile()
    {
        $settings = new Settings(['inexisting_filename' => ['key' => 1]]);
        $this->expectException('\Exception');
        $settings->load(self::resourcePath());
    }
}
