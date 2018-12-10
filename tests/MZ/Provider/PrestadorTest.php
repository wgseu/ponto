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
use MZ\System\Permissao;

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
            'codigobarras',
            'porcentagem',
            'pontuacao',
            'ativo',
            'remuneracao',
            'datatermino',
            'datacadastro',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }
}
