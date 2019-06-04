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
namespace MZ\Provider;

use MZ\Provider\FuncaoTest;
use MZ\Account\ClienteTest;
use MZ\Account\AuthenticationTest;
use MZ\System\Permissao;
use MZ\Exception\ValidationException;
use MZ\Database\DB;

class PrestadorTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid prestador
     * @param Funcao $funcao provider function
     * @return Prestador
     */
    public static function build($funcao = null)
    {
        $funcao = $funcao ?: FuncaoTest::create(Permissao::getAll());
        $cliente = ClienteTest::create();
        $prestador = new Prestador();
        $prestador->loadNextCodigo();
        $prestador->setFuncaoID($funcao->getID());
        $prestador->setClienteID($cliente->getID());
        $prestador->setVinculo(Prestador::VINCULO_FUNCIONARIO);
        $prestador->setAtivo('Y');
        return $prestador;
    }

    /**
     * Create a prestador on database
     * @param Funcao $funcao provider function
     * @return Prestador
     */
    public static function create($funcao = null)
    {
        $prestador = self::build($funcao);
        $prestador->insert();
        return $prestador;
    }

    /**
     * Create a prestador on database
     * @return Prestador
     */
    public function testPublish()
    {
        $funcionario = new Prestador();
        $values = $funcionario->publish(app()->auth->provider);
        $allowed = [
            'id',
            'codigo',
            'funcaoid',
            'clienteid',
            'prestadorid',
            'vinculo',
            'porcentagem',
            'pontuacao',
            'ativo',
            'remuneracao',
            'datatermino',
            'datacadastro',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testAddInvalid()
    {
        $prestador = self::build();
        $prestador->setCodigo(null);
        $prestador->setFuncaoID(null);
        $prestador->setClienteID(null);
        $prestador->setVinculo(null);
        $prestador->setPorcentagem(null);
        $prestador->setPontuacao(null);
        $prestador->setAtivo(null);
        $prestador->setRemuneracao(null);
        try {
            $prestador->insert();
            $this->fail('Não cadastrar valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(
                ['codigo', 'funcaoid', 'clienteid', 'vinculo', 'porcentagem', 'pontuacao', 'ativo', 'remuneracao', 'datatermino'],
                array_keys($e->getErrors())
            );
        }
        //----------------------
        $cliente = ClienteTest::build();
        $cliente->setTipo("Juridica");
        $cliente->insert();
        $prestador = self::build();
        $prestador->setClienteID($cliente->getID());
        try {
            $prestador->insert();
            $this->fail('Não cadastrar cliente != P.Fisica');
        } catch (ValidationException $e) {
            $this->assertEquals(['clienteid'], array_keys($e->getErrors()));
        }
        //------------Cliente sem login
        $cliente = ClienteTest::build();
        $cliente->setID(55);
        $cliente->setLogin('');
        try {
            $prestador = self::build();
            $prestador->setClienteID($cliente->getID());
            $prestador->insert();
            $this->fail('cliente sem login');
        } catch (ValidationException $e) {
            $this->assertEquals(['clienteid'], array_keys($e->getErrors()));
        }
        //------------Cliente sem senha
        $cliente = ClienteTest::build();
        $cliente->setID(55);
        $cliente->setSenha(null);
        try {
            $prestador = self::build();
            $prestador->setClienteID($cliente->getID());
            $prestador->insert();
            $this->fail('cliente sem senha');
        } catch (ValidationException $e) {
            $this->assertEquals(['clienteid'], array_keys($e->getErrors()));
        }
        //----------------------
        $prestador = self::build();
        $prestador->setPorcentagem(-1);
        try {
            $prestador->insert();
            $this->fail('Não cadastra comissão negativa');
        } catch (ValidationException $e) {
            $this->assertEquals(['porcentagem'], array_keys($e->getErrors()));
        }
        //----------------------
        $prestador = self::build();
        $prestador->setPontuacao(-1);
        try {
            $prestador->insert();
            $this->fail('Não cadastra pontuação negativa');
        } catch (ValidationException $e) {
            $this->assertEquals(['pontuacao'], array_keys($e->getErrors()));
        }
        //----------------------
        $prestador = self::build();
        $prestador->setRemuneracao(-1);
        try {
            $prestador->insert();
            $this->fail('Não remuneracao comissão negativa');
        } catch (ValidationException $e) {
            $this->assertEquals(['remuneracao'], array_keys($e->getErrors()));
        }
        //----------(!$this->isAtivo() && is_null($this->getDataTermino())
        $prestador = self::build();
        $prestador->setAtivo('N');
        try {
            $prestador->insert();
            $this->fail('Não cadastrar funcionario inativo');
        } catch (ValidationException $e) {
            $this->assertEquals(['ativo', 'datatermino'], array_keys($e->getErrors()));
        }
        //----------------------
        $prestador = self::build();
        $prestador->setAtivo('Y');
        $prestador->setDataTermino(DB::now());
        try {
            $prestador->insert();
            $this->fail('Não remuneracao comissão negativa');
        } catch (ValidationException $e) {
            $this->assertEquals(['datatermino'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $prestador = self::create();
        try {
            $prestador->setCodigo('12346');
            $prestador->insert();
            $this->fail('Não cadastrar fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['clienteid'], array_keys($e->getErrors()));
        }

        $prestador = self::create();
        try {
            $prestador->insert();
            $this->fail('Não cadastrar fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['codigo'], array_keys($e->getErrors()));
        }
        //-------- return
        // $cliente = build();
        // $prestador = self::build();
        // $prestador->setClienteID($cliente->getID());
        // $prestador->insert();
        // try {
        //     $prestador->insert();
        //     $this->fail('Não cadastrar fk duplicada');
        // } catch (ValidationException $e) {
        //     $this->assertEquals(['clienteid'], array_keys($e->getErrors()));
        // }
    }

    public function testFinds()
    {
        //FAZER O TESTE FINDS
        $prestador = self::create();
        $funcao = $prestador->findFuncaoID();
        $this->assertEquals($prestador->getFuncaoID(), $funcao->getID());

        $prestadorFound = $prestador->findPrestadorID();
        $this->assertEquals($prestador->getPrestadorID(), $prestadorFound->getID());

        $prestByCod = $prestador->findByCodigo($prestador->getCodigo());
        $this->assertInstanceOF(get_class($prestador), $prestByCod);
    }

    public function testGetVinculo()
    {
        $prestador = self::create();
        $options = Prestador::getVinculoOptions($prestador->getVinculo());
        $this->assertEquals('Funcionário', $options);
    }

    public function testDelete()
    {
        $prestador = self::create();
        $prestador->delete();
        $prestador->loadByID();
        $this->assertFalse($prestador->exists());
    }

    public function testDeleteInvalid()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRESTADORES]);
        $prestador = PrestadorTest::create();
        $this->expectException('\Exception');
        $prestador->delete();
    }

    public function testDeleteSelf()
    {
        $prestador = AuthenticationTest::authSelf();
        $this->expectException('\Exception');
        $prestador->delete();
    }

    public function testDeleteOwner()
    {
        $prestador = AuthenticationTest::authOwner();
        $this->expectException('\Exception');
        $prestador->delete();
    }
}
