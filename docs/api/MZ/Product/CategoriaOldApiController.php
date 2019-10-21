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
namespace MZ\Product;

/**
 * Allow application to serve system resources
 */
class CategoriaOldApiController extends \MZ\Core\ApiController
{
    public function find()
    {
        $todas = $this->getRequest()->query->get('todas') == 'Y';
        if ($todas) {
            $condition = [];
        } else {
            $condition = ['disponivel' => 'Y'];
        }
        $categorias = Categoria::findAll($condition, ['vendas' => -1]);
        $items = [];
        foreach ($categorias as $categoria) {
            $item = $categoria->publish(app()->auth->provider);
            if ($todas) {
                $item['disponivel'] = $categoria->isAvailable();
            }
            $items[] = $item;
        }
        return $this->json()->success(['categorias' => $items]);
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'app_categoria_find',
                'path' => '/app/categoria/listar',
                'method' => 'GET',
                'controller' => 'find',
            ]
        ];
    }
}