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
namespace MZ\Location;

use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class BairroOldApiController extends \MZ\Core\ApiController
{
    public function find()
    {
        $estado_id = $this->getRequest()->query->get('estadoid');
        $estado = Estado::findByID($estado_id);
        if (!$estado->exists()) {
            return $this->json()->error('O estado não foi informado ou não existe!');
        }
        $cidade_nome = $this->getRequest()->query->get('cidade');
        $cidade = Cidade::findByEstadoIDNome($estado_id, $cidade_nome);
        if (!$cidade->exists()) {
            return $this->json()->error('A cidade informada não existe!');
        }
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $condition = Filter::query($this->getRequest()->query->all());
        $condition['cidadeid'] = $cidade->getID();
        unset($condition['ordem']);
        $bairros = Bairro::findAll($condition, $order, 10);
        $items = [];
        foreach ($bairros as $bairro) {
            $items[] = $bairro->publish();
        }
        return $this->json()->success(['items' => $items]);
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'app_bairro_find',
                'path' => '/app/bairro/',
                'method' => 'GET',
                'controller' => 'find',
            ]
        ];
    }
}
