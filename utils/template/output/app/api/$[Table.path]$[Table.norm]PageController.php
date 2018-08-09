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
$[table.if(package)]
namespace $[Table.package];
$[table.end]

/**
 * Allow application to serve system resources
 */
class $[Table.norm]PageController extends \MZ\Core\Controller
{
    public function view()
    {
        $app = $this->getApplication();
        return require($this->getApplication()->getPath('pages') . '/$[table.unix]/index.php');
    }

    public function find()
    {
        $app = $this->getApplication();
        return require($this->getApplication()->getPath('pages') . '/gerenciar/$[table.unix]/index.php');
    }

    public function add()
    {
        $app = $this->getApplication();
        return require($this->getApplication()->getPath('pages') . '/gerenciar/$[table.unix]/cadastrar.php');
    }

    public function update()
    {
        $app = $this->getApplication();
        return require($this->getApplication()->getPath('pages') . '/gerenciar/$[table.unix]/editar.php');
    }

    public function delete()
    {
        $app = $this->getApplication();
        return require($this->getApplication()->getPath('pages') . '/gerenciar/$[table.unix]/excluir.php');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => '$[table.unix]_view',
                'path' => '/$[table.unix]/',
                'method' => 'GET',
                'controller' => 'view',
            ],
            [
                'name' => '$[table.unix]_find',
                'path' => '/gerenciar/$[table.unix]/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => '$[table.unix]_add',
                'path' => '/gerenciar/$[table.unix]/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => '$[table.unix]_update',
                'path' => '/gerenciar/$[table.unix]/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => '$[table.unix]_delete',
                'path' => '/gerenciar/$[table.unix]/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
