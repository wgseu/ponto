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
namespace MZ\Invoice;

/**
 * Allow application to serve system resources
 */
class EmitentePageController extends \MZ\Core\Controller
{
    public function view()
    {
    }

    public function find()
    {
        need_manager(is_output('json'));
    }

    public function add()
    {
        need_manager(is_output('json'));
    }

    public function update()
    {
        need_manager(is_output('json'));
    }

    public function delete()
    {
        need_manager(is_output('json'));
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'emitente_view',
                'path' => '/emitente/',
                'method' => 'GET',
                'controller' => 'view',
            ],
            [
                'name' => 'emitente_find',
                'path' => '/gerenciar/emitente/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'emitente_add',
                'path' => '/gerenciar/emitente/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'emitente_update',
                'path' => '/gerenciar/emitente/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'emitente_delete',
                'path' => '/gerenciar/emitente/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
