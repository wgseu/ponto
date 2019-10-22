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

use MZ\Account\Cliente;

class GenderTest extends \MZ\Framework\TestCase
{
    public function testDetectMasculino()
    {
        $this->assertEquals(Cliente::GENERO_MASCULINO, Gender::detect('João'));
        $this->assertEquals(Cliente::GENERO_MASCULINO, Gender::detect('Pedro'));
        $this->assertEquals(Cliente::GENERO_MASCULINO, Gender::detect('Julian'));
        $this->assertEquals(Cliente::GENERO_MASCULINO, Gender::detect('Amauri'));
        $this->assertEquals(Cliente::GENERO_MASCULINO, Gender::detect('Vasconcelos'));
        $this->assertEquals(Cliente::GENERO_MASCULINO, Gender::detect('Cauã'));
        $this->assertEquals(Cliente::GENERO_MASCULINO, Gender::detect('Marcos'));
        $this->assertEquals(Cliente::GENERO_MASCULINO, Gender::detect('Guilherme'));
        $this->assertEquals(Cliente::GENERO_MASCULINO, Gender::detect('Arthur'));
        $this->assertEquals(Cliente::GENERO_MASCULINO, Gender::detect('Samuel'));
        $this->assertEquals(Cliente::GENERO_MASCULINO, Gender::detect('Matheus'));
        $this->assertEquals(Cliente::GENERO_MASCULINO, Gender::detect('Alex'));
    }

    public function testDetectFeminino()
    {
        $this->assertEquals(Cliente::GENERO_FEMININO, Gender::detect('Maria'));
        $this->assertEquals(Cliente::GENERO_FEMININO, Gender::detect('Aline'));
        $this->assertEquals(Cliente::GENERO_FEMININO, Gender::detect('Suelem'));
        $this->assertEquals(Cliente::GENERO_FEMININO, Gender::detect('Carmem'));
        $this->assertEquals(Cliente::GENERO_FEMININO, Gender::detect('Matilde'));
        $this->assertEquals(Cliente::GENERO_FEMININO, Gender::detect('Suely'));
        $this->assertEquals(Cliente::GENERO_FEMININO, Gender::detect('Mariany'));
        $this->assertEquals(Cliente::GENERO_FEMININO, Gender::detect('Elizabeth'));
        $this->assertEquals(Cliente::GENERO_FEMININO, Gender::detect('Elizabete'));
        $this->assertEquals(Cliente::GENERO_FEMININO, Gender::detect('Marlu'));
        $this->assertEquals(Cliente::GENERO_FEMININO, Gender::detect('Carmen'));
    }
}
