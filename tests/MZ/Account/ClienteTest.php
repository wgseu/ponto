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
namespace MZ\Account;

use MZ\Database\DB;
use MZ\Exception\ValidationException;
use MZ\Employee\Funcionario;

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

    public function testFoneIndex()
    {
        $cliente = new Cliente();
        try {
            $cliente->getFone(0);
            $this->fail('O índice 0 não é válido');
        } catch (\Exception $e) {
        }
        try {
            $cliente->getFone(3);
            $this->fail('O índice 3 não é válido');
        } catch (\Exception $e) {
        }
        try {
            $cliente->setFone(0, '44988888888');
            $this->fail('O índice 0 não é válido para atribuição');
        } catch (\Exception $e) {
        }
        try {
            $cliente->setFone(3, '44988888888');
            $this->fail('O índice 3 não é válido para atribuição');
        } catch (\Exception $e) {
        }
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
        $cliente->setTipo(Cliente::TIPO_JURIDICA);
        $values = $cliente->publish();
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $cliente = new Cliente();
        $cliente->setTipo(null);
        $cliente->setLogin('Meu Login');
        try {
            $cliente->insert();
            $this->fail('Não deveria ter cadastrado o cliente');
        } catch (ValidationException $e) {
            $this->assertEquals(
                ['tipo', 'login', 'senha', 'nome', 'genero', 'fone1'],
                array_keys($e->getErrors())
            );
        }
        $cliente->setTipo(Cliente::TIPO_JURIDICA);
        try {
            $cliente->insert();
            $this->fail('Não deveria ter cadastrado o cliente');
        } catch (ValidationException $e) {
            $this->assertEquals(
                ['login', 'nome', 'genero', 'fone1'],
                array_keys($e->getErrors())
            );
        }
        $cliente->setTipo(Cliente::TIPO_FISICA);
        $cliente->setFone(2, '11223399');
        $cliente->setLimiteCompra(-6);
        $cliente->setCPF('12663254411');
        try {
            $cliente->insert();
            $this->fail('Não deveria ter cadastrado o cliente');
        } catch (ValidationException $e) {
            $this->assertEquals(
                ['login', 'nome', 'genero', 'cpf', 'fone2', 'limitecompra'],
                array_keys($e->getErrors())
            );
        }
        $cliente->setTipo(Cliente::TIPO_JURIDICA);
        $cliente->setCPF('12663254411');
        $cliente->setEmail('cicrano a@email.com');
        $cliente->setFone(1, '11223399');
        $cliente->setFone(2, null);
        $cliente->setLimiteCompra(null);
        try {
            $cliente->insert();
            $this->fail('Não deveria ter cadastrado o cliente');
        } catch (ValidationException $e) {
            $this->assertEquals(
                ['login', 'nome', 'genero', 'cpf', 'email', 'fone1'],
                array_keys($e->getErrors())
            );
        }
        $cliente->setLogin(null);
        $cliente->setTipo(Cliente::TIPO_FISICA);
        $cliente->setNomeCompleto('Cicrano da Silva');
        $cliente->setEmail('cicrano@email.com');
        $cliente->setLogin('cicrano');
        $cliente->setFone(1, '44911223399');
        $cliente->setCPF('12663254410');
        $cliente->setGenero(Cliente::GENERO_MASCULINO);
        $cliente->setSenha('1234');
        $cliente->setSecreto('jsdo6as4168dsa46546sa54d');
        $cliente->insert();
        try {
            $cliente->setSenha('1234');
            $cliente->insert();
            $this->fail('Não deveria ter cadastrado o cliente com a mesma chave secreta');
        } catch (ValidationException $e) {
            $this->assertEquals(['secreto'], array_keys($e->getErrors()));
        }
        try {
            $cliente->setSecreto(null);
            $cliente->setSenha('1234');
            $cliente->insert();
            $this->fail('Não deveria ter cadastrado o cliente com o mesmo login');
        } catch (ValidationException $e) {
            $this->assertEquals(['login'], array_keys($e->getErrors()));
        }
        try {
            $cliente->setLogin(null);
            $cliente->setSenha('1234');
            $cliente->insert();
            $this->fail('Não deveria ter cadastrado o cliente com o mesmo CPF');
        } catch (ValidationException $e) {
            $this->assertEquals(['cpf'], array_keys($e->getErrors()));
        }
        try {
            $cliente->setCPF(null);
            $cliente->setSenha('1234');
            $cliente->insert();
            $this->fail('Não deveria ter cadastrado o cliente com o mesmo e-mail');
        } catch (ValidationException $e) {
            $this->assertEquals(['email'], array_keys($e->getErrors()));
        }
        try {
            $cliente->setEmail(null);
            $cliente->setSenha('1234');
            $cliente->insert();
            $this->fail('Não deveria ter cadastrado o cliente com o mesmo telefone');
        } catch (ValidationException $e) {
            $this->assertEquals(['fone1'], array_keys($e->getErrors()));
        }
        $cliente = new Cliente();
        $cliente->setNomeCompleto('Cicrano da Silva');
        $cliente->setEmail('test2cicrano@email.com');
        $cliente->setGenero(Cliente::GENERO_MASCULINO);
        $cliente->setSenha('1234');
        $cliente->setAcionistaID(0);
        $this->setExpectedException('\PDOException');
        $cliente->insert();
    }

    public function testDelete()
    {
        $cliente = new Cliente();
        $cliente->setNomeCompleto('Cicrano da Silva');
        $cliente->setEmail('testcicrano@email.com');
        $cliente->setGenero(Cliente::GENERO_MASCULINO);
        $cliente->setSenha('1234');
        $cliente->insert();
        $id = $cliente->getID();
        $cliente->setID(null);
        try {
            $cliente->delete();
            $this->fail('Não deveria ter deletado o cliente sem o ID');
        } catch (\Exception $e) {
        }
        $cliente->setID($id);
        $cliente->delete();
        $cliente->loadByID($cliente->getID());
        $this->assertFalse($cliente->exists());
    }

    public function testUpdate()
    {
        $cliente = new Cliente();
        $cliente->setNomeCompleto('Cicrano da Silva');
        $cliente->setEmail('testcicrano@email.com');
        $cliente->setGenero(Cliente::GENERO_MASCULINO);
        $cliente->setSenha('1234');
        $cliente->insert();
        $first_cliente = new Cliente($cliente);
        $cliente->setEmail('testcicrano2@email.com');
        $cliente->setSenha('1234');
        $cliente->insert();
        $cliente->setSenha(null);
        $cliente->setImagem(true);
        $cliente->update();
        $found_cliente = Cliente::findByID($cliente->getID());
        $this->assertEquals($cliente, $found_cliente);
        try {
            $first_cliente->setEmail('testcicrano2@email.com');
            $first_cliente->update();
            $this->fail('Não deveria ter atualizado o cliente com E-mail repetido');
        } catch (ValidationException $e) {
            $this->assertEquals(['email'], array_keys($e->getErrors()));
        }
        $cliente->setID(null);
        try {
            $cliente->update();
            $this->fail('Não deveria ter atualizado o cliente sem o ID');
        } catch (\Exception $e) {
        }
    }

    public function testFindAndLoad()
    {
        $cliente = new Cliente();
        $cliente->setNomeCompleto('Beltrano da Silva');
        $cliente->setGenero(Cliente::GENERO_MASCULINO);
        $cliente->setSenha('1234');
        $cliente->setFone(1, '44967442288');
        $cliente->setFone(2, '44988552211');
        $cliente->insert();
        $first_cliente = new Cliente($cliente);
        $cliente->setEmail('beltrano@email.com');
        $cliente->setLogin('beltrano');
        $cliente->setFone(1, '44988552211');
        $cliente->setFone(2, '44967442288');
        $cliente->setCPF('95672167624');
        $cliente->setSenha('1234');
        $cliente->setSecreto('635sd89d5f4a68d7sd6s5a4');
        $cliente->setAcionistaID($first_cliente->getID());
        $cliente->setDataAniversario(DB::date());
        $cliente->insert();
        $empty_cliente = new Cliente();
        // find aniversariante
        $found_cliente = Cliente::find(['aniversariante' => true]);
        $this->assertEquals($cliente, $found_cliente);
        // findByLoginSenha
        $found_cliente = Cliente::findByLoginSenha('beltrano@email.com', '1234');
        $this->assertEquals($cliente, $found_cliente);
        $found_cliente = Cliente::findByLoginSenha('beltrano@email.com', ' 1234');
        $empty_cliente->setDataCadastro($found_cliente->getDataCadastro());
        $empty_cliente->setDataAtualizacao($found_cliente->getDataAtualizacao());
        $this->assertEquals($empty_cliente, $found_cliente);
        $found_cliente = Cliente::findByLoginSenha('beltrano', '1234');
        $this->assertEquals($cliente, $found_cliente);
        $found_cliente = Cliente::findByLoginSenha('beltrano', '1234 ');
        $empty_cliente->setDataCadastro($found_cliente->getDataCadastro());
        $empty_cliente->setDataAtualizacao($found_cliente->getDataAtualizacao());
        $this->assertEquals($empty_cliente, $found_cliente);
        $found_cliente = Cliente::findByLoginSenha('95672167624', '1234');
        $this->assertEquals($cliente, $found_cliente);
        $found_cliente = Cliente::findByLoginSenha('95672167624', ' 1234');
        $empty_cliente->setDataCadastro($found_cliente->getDataCadastro());
        $empty_cliente->setDataAtualizacao($found_cliente->getDataAtualizacao());
        $this->assertEquals($empty_cliente, $found_cliente);
        $found_cliente = Cliente::findByLoginSenha('44988552211', '1234');
        $this->assertEquals($cliente, $found_cliente);
        $found_cliente = Cliente::findByLoginSenha('44988552211', '1234 ');
        $empty_cliente->setDataCadastro($found_cliente->getDataCadastro());
        $empty_cliente->setDataAtualizacao($found_cliente->getDataAtualizacao());
        $this->assertEquals($empty_cliente, $found_cliente);
        // findByFone
        $found_cliente = Cliente::findByFone('88552211');
        $this->assertEquals($cliente, $found_cliente);
        $found_cliente = Cliente::findByFone('4488552211');
        $this->assertEquals($cliente, $found_cliente);
        $found_cliente = Cliente::findByFone('44988552211');
        $this->assertEquals($cliente, $found_cliente);
        // findByFone
        $found_cliente = Cliente::findByFone('67442288');
        $this->assertEquals($first_cliente, $found_cliente);
        // findByEmail
        $found_cliente = Cliente::findByEmail($cliente->getEmail());
        $this->assertEquals($cliente, $found_cliente);
        // findByCPF
        $found_cliente = Cliente::findByCPF($cliente->getCPF());
        $this->assertEquals($cliente, $found_cliente);
        // findByLogin
        $found_cliente = Cliente::findByLogin($cliente->getLogin());
        $this->assertEquals($cliente, $found_cliente);
        // findBySecreto
        $found_cliente = Cliente::findBySecreto($cliente->getSecreto());
        $this->assertEquals($cliente, $found_cliente);
        // findAcionistaID
        $found_cliente = $cliente->findAcionistaID();
        $this->assertEquals($first_cliente, $found_cliente);
        // findAcionistaID
        $found_cliente = $first_cliente->findAcionistaID();
        $empty_cliente->setDataCadastro($found_cliente->getDataCadastro());
        $empty_cliente->setDataAtualizacao($found_cliente->getDataAtualizacao());
        $this->assertEquals($empty_cliente, $found_cliente);
        // find
        $found_cliente = Cliente::find(['search' => $cliente->getEmail()]);
        $this->assertEquals($cliente, $found_cliente);
        $found_cliente = Cliente::find(['search' => $cliente->getCPF()]);
        $this->assertEquals($cliente, $found_cliente);
        $found_cliente = Cliente::find(['search' => $cliente->getFone(1)]);
        $this->assertEquals($cliente, $found_cliente);
        // findAll
        $clientes = Cliente::findAll(['search' => $cliente->getNomeCompleto()], ['id' => -1], 2, 0);
        $this->assertEquals([$cliente, $first_cliente], $clientes);
        // findAll
        $compradores = Cliente::findAll([
            'comprador' => true,
            'apartir_compra' => $first_cliente->getDataCadastro(),
            'ate_compra' => $cliente->getDataCadastro(),
            'id' => [$cliente->getID(), $first_cliente->getID()]
        ]);
        $this->assertEquals([], $compradores);
        // date time
        $clientes = Cliente::findAll([
            'apartir_cadastro' => $first_cliente->getDataCadastro(),
            'ate_cadastro' => $cliente->getDataCadastro(),
            'id' => [$cliente->getID(), $first_cliente->getID()]
        ]);
        $this->assertEquals([$first_cliente, $cliente], $clientes);
        // rawFindAll
        $clientes = Cliente::rawFindAll(['search' => $cliente->getNomeCompleto()], ['id' => 1], 2, 0);
        $this->assertEquals([$first_cliente->toArray(), $cliente->toArray()], $clientes);
        // count
        $quantidade = Cliente::count(['search' => $cliente->getNomeCompleto()]);
        $this->assertEquals(2, $quantidade);
    }

    public function testAssinatura()
    {
        $cliente = new Cliente();
        $cliente->setNomeCompleto('Beltrano da Silva Correia');
        $this->assertEquals('Beltrano Silva', $cliente->getAssinatura());
        $cliente->setNomeCompleto('Beltrano dos Santos Correia');
        $this->assertEquals('Beltrano Santos', $cliente->getAssinatura());
        $cliente->setNomeCompleto('Maria das Dores Soares');
        $this->assertEquals('Maria Dores', $cliente->getAssinatura());
        $cliente->setNomeCompleto('Beltrano Silva Correia');
        $this->assertEquals('Beltrano Silva', $cliente->getAssinatura());
    }

    public function testMakeImagem()
    {
        $cliente = new Cliente();
        $cliente->loadImagem();
        $this->assertEquals('/static/img/cliente.png', $cliente->makeImagem(true));
        $cliente->setImagem('imagem.png');
        $this->assertEquals('/static/img/cliente/imagem.png', $cliente->makeImagem());
    }

    public function testClean()
    {
        $old_cliente = new Cliente();
        $old_cliente->setImagem('imagem.png');
        $cliente = new Cliente();
        $cliente->setDataCadastro($old_cliente->getDataCadastro());
        $cliente->setDataAtualizacao($old_cliente->getDataAtualizacao());
        $cliente->clean($old_cliente);
        $this->assertEquals($old_cliente, $cliente);
    }

    public function testNomeCompleto()
    {
        $cliente = new Cliente();
        $nome_completo = 'Beltrano da Silva Correia';
        $cliente->setNomeCompleto($nome_completo);
        $this->assertEquals($nome_completo, $cliente->getNomeCompleto());
        $nome_completo = 'Beltrano';
        $cliente->setNomeCompleto($nome_completo);
        $this->assertEquals($nome_completo, $cliente->getNomeCompleto());
        $nome_completo = 'Beltrano Silva';
        $cliente->setNomeCompleto($nome_completo);
        $this->assertEquals($nome_completo, $cliente->getNomeCompleto());
        $fantasia = 'Minha Empresa';
        $cliente->setTipo(Cliente::TIPO_JURIDICA);
        $cliente->setNome($fantasia);
        $cliente->setSobrenome('Empresa LTDA');
        $this->assertEquals($fantasia, $cliente->getNomeCompleto());
    }

    public function testGeneroAndTipoName()
    {
        $cliente = new Cliente();
        $cliente->setGenero(Cliente::GENERO_MASCULINO);
        $this->assertEquals(
            Cliente::getGeneroOptions(Cliente::GENERO_MASCULINO),
            $cliente->getGeneroName()
        );
        $cliente->setGenero(Cliente::GENERO_FEMININO);
        $this->assertEquals(
            Cliente::getGeneroOptions(Cliente::GENERO_FEMININO),
            $cliente->getGeneroName()
        );
        $cliente->setTipo(Cliente::TIPO_JURIDICA);
        $this->assertEquals('Empresa', $cliente->getGeneroName());
        $this->assertNotNull(Cliente::getTipoOptions(Cliente::TIPO_JURIDICA));
    }

    public function testFilter()
    {
        $cliente_obj = new Cliente([
            'id' => 123,
            'acionistaid' => ' 123 ',
            'cpf' => '331.196.564-70',
            'email' => 'cliente@email.com',
            'dataaniversario' => '05/12/2010',
            'fone1' => '(44) 9988-5544',
        ]);
        $filter_cliente = new Cliente([
            'id' => null,
            'acionistaid' => 123,
            'cpf' => '33119656470',
            'email' => 'cliente@email.com',
            'dataaniversario' => '2010-12-05',
            'fone1' => '4499885544',
            'limitecompra' => '1.012,5'
        ]);
        $cliente = new Cliente($cliente_obj);
        $cliente->filter($filter_cliente);
        $filter_cliente->setLimiteCompra(1012.5);
        $this->assertEquals($filter_cliente, $cliente);
        $cliente_obj->setTipo(Cliente::TIPO_JURIDICA);
        $cliente_obj->setCPF('73.591.671/0001-48');
        $cliente_obj->setImagem('imagem.png');
        $filter_cliente->setTipo(Cliente::TIPO_JURIDICA);
        $filter_cliente->setGenero(Cliente::GENERO_FEMININO);
        $filter_cliente->setLimiteCompra('1.012,5');
        $filter_cliente->setCPF('73591671000148');
        $filter_cliente->setImagem(true);
        $cliente_obj->filter($filter_cliente);
        $filter_cliente->setLimiteCompra(1012.5);
        $this->assertEquals($filter_cliente, $cliente_obj);
    }

    public function testPasswordMatch()
    {
        $cliente = new Cliente(['senha' => 'c1Cçí a']);
        $cliente->passwordMatch('c1Cçí a');
        $this->setExpectedException('\MZ\Exception\ValidationException');
        $cliente->passwordMatch('c1Cçí A');
    }

    public function testInvalidaFuncionario()
    {
        $funcionario = Funcionario::findByID(1);
        $cliente = $funcionario->findClienteID();
        $cliente->setLogin(null);
        $cliente->setTipo(Cliente::TIPO_JURIDICA);
        try {
            $cliente->update();
            $this->fail('Não deveria ter atualizado o cliente funcionário');
        } catch (ValidationException $e) {
            $this->assertEquals(['tipo', 'login'], array_keys($e->getErrors()));
        }
    }
}
