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
namespace MZ\Stock;

use MZ\Provider\PrestadorTest;
use MZ\Exception\ValidationException;

class ListaTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid lista de compra
     * @param string $descricao Lista de compra descrição
     * @return Lista
     */
    public static function build($descricao = null)
    {
        $last = Lista::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $prestador = PrestadorTest::create();
        $lista = new Lista();
        $lista->setDescricao($descricao ?: "Lista de compra {$id}");
        $lista->setEstado(Lista::ESTADO_ANALISE);
        $lista->setEncarregadoID($prestador->getID());
        $lista->setDataViagem('2016-12-25 12:15:00');
        return $lista;
    }

    /**
     * Create a lista de compra on database
     * @param string $descricao Lista de compra descrição
     * @return Lista
     */
    public static function create($descricao = null)
    {
        $lista = self::build($descricao);
        $lista->insert();
        return $lista;
    }

    public function testFind()
    {
        $lista = self::create();
        $condition = ['descricao' => $lista->getDescricao()];
        $found_lista = Lista::find($condition);
        $this->assertEquals($lista, $found_lista);
        list($found_lista) = Lista::findAll($condition, [], 1);
        $this->assertEquals($lista, $found_lista);
        $this->assertEquals(1, Lista::count($condition));
    }

    public function testAdd()
    {
        $lista = self::build();
        $lista->insert();
        $this->assertTrue($lista->exists());
    }

    public function testAddInvalid()
    {
        $lista = self::build();
        $lista->setDescricao(null);
        $lista->setEstado(null);
        $lista->setEncarregadoID(null);
        $lista->setDataViagem(null);
        try {
            $lista->insert();
            $this->fail('Não cadastrar valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['descricao', 'estado', 'encarregadoid', 'dataviagem'], array_keys($e->getErrors()));
        }
    }

    public function testFinds()
    {
        $lista = self::create();

        $encarregado = $lista->findEncarregadoID();
        $this->assertEquals($lista->getEncarregadoID(), $encarregado->getID());

        $viagem = $lista->findViagemID();
        $this->assertEquals($lista->getViagemID(), $viagem->getID());
    }

    public function testUpdate()
    {
        $lista = self::create();
        $lista->update();
        $this->assertTrue($lista->exists());
    }

    public function testGetOptions()
    {
        $lista = self::build();
        $lista->setEstado("Fechada");
        $lista->insert();
        $options = Lista::getEstadoOptions($lista->getEstado());
        $this->assertEquals($lista->getEstado(), $options);
    }

    public function testDelete()
    {
        $lista = self::create();
        $lista->delete();
        $lista->loadByID();
        $this->assertFalse($lista->exists());
    }
}
