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
 * @author  Francimar Alves <mazinsw@gmail.com>
 */
namespace MZ\Account;

use MZ\Database\Helper;

class ClienteTest extends \PHPUnit_Framework_TestCase
{
    public function testFromArray()
    {
        $old_cliente = new Cliente([
            'id' => 123,
            'tipo' => Cliente::TIPO_JURIDICA,
            'acionistaid' => 123,
            'login' => 'cliente',
            'senha' => '123',
            'nome' => 'Cliente',
            'sobrenome' => 'Cliente',
            'genero' => Cliente::GENERO_MASCULINO,
            'cpf' => '111.111.111-11',
            'rg' => '111222333',
            'im' => '123654',
            'email' => 'cliente@email.com',
            'dataaniversario' => '2010-12-05',
            'fone1' => '4499885544',
            'fone2' => '4499784512',
            'slogan' => 'O Cliente',
            'secreto' => 'ABC8987EFA',
            'limitecompra' => 12.5,
            'facebookurl' => 'facebook',
            'twitterurl' => 'twitter',
            'linkedinurl' => 'linkedin',
            'imagem' => "\x5\x0\x3",
            'dataatualizacao' => '2016-12-05 12:15:00',
            'datacadastro' => '2016-12-05 12:15:00',
        ]);
        $cliente = new Cliente();
        $cliente->fromArray($old_cliente);
        $this->assertEquals($cliente, $old_cliente);
        $cliente->fromArray(null);
        $new_cliente = new Cliente();
        $new_cliente->setDataCadastro($cliente->getDataCadastro());
        $new_cliente->setDataAtualizacao($cliente->getDataAtualizacao());
        $this->assertEquals($cliente, $new_cliente);
    }

    public function testPublish()
    {
        $cliente = new Cliente();
        $values = $cliente->publish();
        $allowed = [
            'id',
            'tipo',
            'acionistaid',
            'login',
            'nome',
            'sobrenome',
            'genero',
            'cpf',
            'rg',
            'im',
            'email',
            'dataaniversario',
            'fone1',
            'fone2',
            'slogan',
            'limitecompra',
            'facebookurl',
            'twitterurl',
            'linkedinurl',
            'imagem',
            'dataatualizacao',
            'datacadastro',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $cliente = new Cliente();
        $cliente->setNomeCompleto('Cicrano da Silva');
        $cliente->setEmail('cicrano@email.com');
        $cliente->setLogin('cicrano');
        $cliente->setGenero(Cliente::GENERO_MASCULINO);
        $cliente->setSenha('1234');
        $cliente->insert();
    }

    public function testFind()
    {
        $cliente = new Cliente();
        $cliente->setNomeCompleto('Beltrano da Silva');
        $cliente->setGenero(Cliente::GENERO_MASCULINO);
        $cliente->setEmail('beltrano@email.com');
        $cliente->setLogin('beltrano');
        $cliente->setDataAniversario(Helper::date());
        $cliente->setSenha('1234');
        $cliente->insert();
        $found_cliente = Cliente::find(['aniversariante' => true]);
        $this->assertEquals($cliente, $found_cliente);
    }
}
