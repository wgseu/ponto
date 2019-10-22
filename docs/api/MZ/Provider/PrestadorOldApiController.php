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

use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class PrestadorOldApiController extends \MZ\Core\ApiController
{
    public function find()
    {
        app()->needManager();
        $limit = max(1, min(20, $this->getRequest()->query->getInt('limite', 5)));
        $search = $this->getRequest()->query->get('search');
        if (check_fone($search, true)) {
            $limit = 1;
        }
        $condition = Filter::query($this->getRequest()->query->all());
        $prestadores = Prestador::findAll($condition, [], $limit);
        $campos = [
            'id',
            'nome',
            'fone1',
            'cpf',
            'email',
            'funcao',
            'imagemurl',
        ];
        $items = [];
        foreach ($prestadores as $prestador) {
            $funcao = $prestador->findFuncaoID();
            $cliente = $prestador->findClienteID();
            $cliente_item = $cliente->publish(app()->auth->provider);
            $item = $prestador->publish(app()->auth->provider);
            $item['nome'] = $cliente->getNomeCompleto();
            $item['fone1'] = $cliente_item['fone1'];
            $item['cpf'] = $cliente_item['cpf'];
            $item['email'] = $cliente->getEmail();
            $item['funcao'] = $funcao->getDescricao();
            $items[] = array_intersect_key($item, array_flip($campos));
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
                'name' => 'app_funcionario_find',
                'path' => '/app/funcionario/',
                'method' => 'GET',
                'controller' => 'find',
            ]
        ];
    }
}
