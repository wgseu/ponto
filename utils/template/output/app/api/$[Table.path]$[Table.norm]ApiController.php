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

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class $[Table.norm]ApiController extends \MZ\Core\ApiController
{
    /**
     * Find all $[Table.name.plural]
     */
    public function find()
    {
        $this->needPermission(Permissao::NOME_$[TABLE.style]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = $[Table.norm]::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $$[table.unix.plural] = $[Table.norm]::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($$[table.unix.plural] as $$[table.unix]) {
            $itens[] = $$[table.unix]->publish();
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new $[Table.name]
     */
    public function add()
    {
        $this->needPermission(Permissao::NOME_$[TABLE.style]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $$[table.unix] = new $[Table.norm]($this->getJsonParams());
        $$[table.unix]->filter(new $[Table.norm](), $localized);
        $$[table.unix]->insert();
        return $this->getResponse()->success(['item' => $$[table.unix]->publish()]);
    }

    /**
     * Update an existing $[Table.name]
     * @param int $id $[Table.name] id to update
     */
    public function update($id)
    {
        $this->needPermission(Permissao::NOME_$[TABLE.style]);
        $old_$[table.unix] = $[Table.norm]::findOrFail(['$[primary]' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $$[table.unix] = new $[Table.norm]($this->getJsonParams());
        $$[table.unix]->filter($old_$[table.unix], $localized);
        $$[table.unix]->update();
        $old_$[table.unix]->clean($$[table.unix]);
        return $this->getResponse()->success(['item' => $$[table.unix]->publish()]);
    }

    /**
     * Delete existing $[Table.name]
     * @param int $id $[Table.name] id to delete
     */
    public function delete($id)
    {
        $this->needPermission(Permissao::NOME_$[TABLE.style]);
        $$[table.unix] = $[Table.norm]::findOrFail(['$[primary]' => $id]);
        $$[table.unix]->delete();
        $$[table.unix]->clean(new $[Table.norm]());
        return $this->getResponse()->success([]);
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'api_$[table.unix]_find',
                'path' => '/api/$[table.unix.plural]',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'api_$[table.unix]_add',
                'path' => '/api/$[table.unix.plural]',
                'method' => 'POST',
                'controller' => 'add',
            ],
            [
                'name' => 'api_$[table.unix]_update',
                'path' => '/api/$[table.unix.plural]/{id}',
                'method' => 'PUT',
                'requirements' => ['id' => '\d+'],
                'controller' => 'update',
            ],
            [
                'name' => 'api_$[table.unix]_delete',
                'path' => '/api/$[table.unix.plural]/{id}',
                'method' => 'DELETE',
                'requirements' => ['id' => '\d+'],
                'controller' => 'delete',
            ],
        ];
    }
}
