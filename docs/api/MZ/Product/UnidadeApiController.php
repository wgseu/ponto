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

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Unidades de medidas aplicadas aos produtos
 */
class UnidadeApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Unidades
     * @Get("/api/unidades", name="api_unidade_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Unidade::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $unidades = Unidade::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($unidades as $unidade) {
            $itens[] = $unidade->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Unidade
     * @Post("/api/unidades", name="api_unidade_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $unidade = new Unidade($this->getData());
        $unidade->filter(new Unidade(), app()->auth->provider, $localized);
        $unidade->insert();
        return $this->getResponse()->success(['item' => $unidade->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Unidade
     * @Patch("/api/unidades/{id}", name="api_unidade_update", params={ "id": "\d+" })
     *
     * @param int $id Unidade id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $old_unidade = Unidade::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_unidade->toArray());
        $unidade = new Unidade($data);
        $unidade->filter($old_unidade, app()->auth->provider, $localized);
        $unidade->update();
        $old_unidade->clean($unidade);
        return $this->getResponse()->success(['item' => $unidade->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Unidade
     * @Delete("/api/unidades/{id}", name="api_unidade_delete", params={ "id": "\d+" })
     *
     * @param int $id Unidade id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $unidade = Unidade::findOrFail(['id' => $id]);
        $unidade->delete();
        $unidade->clean(new Unidade());
        return $this->getResponse()->success([]);
    }
}
