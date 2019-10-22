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

namespace MZ\Util;

use Exception;

class CommonTest extends \MZ\Framework\TestCase
{
    public function testStrPlural()
    {
        $this->assertEquals('cartoes', str_plural('cartao'));
        $this->assertEquals('configuracoes', str_plural('configuracao'));
        $this->assertEquals('paineis', str_plural('painel'));
        $this->assertEquals('paes', str_plural('pao'));
        $this->assertEquals('prestadores', str_plural('prestador'));
        $this->assertEquals('clientes', str_plural('cliente'));
        $this->assertEquals('bons', str_plural('bom'));
        $this->assertEquals('paises', str_plural('pais'));
    }

    public function testClassBasename()
    {
        $this->assertEquals('CommonTest', class_basename(self::class));
        $this->assertEquals('Exception', class_basename(Exception::class));
    }

    public function testUnderscoreCase()
    {
        $this->assertEquals('Multilple_XML_Words_With_Upper', underscore_case('MultilpleXMLWordsWithUpper'));
        $this->assertEquals('Simple_XML', underscore_case('SimpleXML'));
        $this->assertEquals('DOM', underscore_case('DOM'));
        $this->assertEquals('Buy_A_Car', underscore_case('BuyACar'));
        $this->assertEquals('A_Car', underscore_case('ACar'));
    }

    public function testSnakeCase()
    {
        $this->assertEquals('multilple_xml_words_with_upper', snake_case('MultilpleXMLWordsWithUpper'));
        $this->assertEquals('simple_xml', snake_case('SimpleXML'));
        $this->assertEquals('dom', snake_case('DOM'));
        $this->assertEquals('buy_a_car', snake_case('BuyACar'));
        $this->assertEquals('a_car', snake_case('ACar'));
    }
}
