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

$[table.if(comment)]
/**
$[table.each(comment)]
 * $[Table.comment]
$[table.end]
 */
$[table.else]
/**
 * Allow application to serve system resources
 */
$[table.end]
class $[Table.norm]ApiController extends \MZ\Core\ApiController
{
    /**
     * Find all $[Table.name.plural]
     * @Get("/api/$[table.unix.plural]", name="api_$[table.unix]_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_$[TABLE.style]]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = $[Table.norm]::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $$[table.unix.plural] = $[Table.norm]::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($$[table.unix.plural] as $$[table.unix]) {
            $itens[] = $$[table.unix]->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new $[Table.name]
     * @Post("/api/$[table.unix.plural]", name="api_$[table.unix]_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_$[TABLE.style]]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $$[table.unix] = new $[Table.norm]($this->getData());
        $$[table.unix]->filter(new $[Table.norm](), app()->auth->provider, $localized);
        $$[table.unix]->insert();
        return $this->getResponse()->success(['item' => $$[table.unix]->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing $[Table.name]
     * @Patch("/api/$[table.unix.plural]/{id}", name="api_$[table.unix]_update", params={ "id": "\d+" })
     *
     * @param int $id $[Table.name] id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_$[TABLE.style]]);
        $old_$[table.unix] = $[Table.norm]::findOrFail(['$[primary]' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = array_merge($old_$[table.unix]->toArray(), $this->getData());
        $$[table.unix] = new $[Table.norm]($data);
        $$[table.unix]->filter($old_$[table.unix], app()->auth->provider, $localized);
        $$[table.unix]->update();
        $old_$[table.unix]->clean($$[table.unix]);
        return $this->getResponse()->success(['item' => $$[table.unix]->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing $[Table.name]
     * @Delete("/api/$[table.unix.plural]/{id}", name="api_$[table.unix]_delete", params={ "id": "\d+" })
     *
     * @param int $id $[Table.name] id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_$[TABLE.style]]);
        $$[table.unix] = $[Table.norm]::findOrFail(['$[primary]' => $id]);
        $$[table.unix]->delete();
        $$[table.unix]->clean(new $[Table.norm]());
        return $this->getResponse()->success([]);
    }
}
