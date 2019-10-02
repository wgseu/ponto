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
namespace MZ\Sale;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Comanda individual, permite lançar pedidos em cartões de consumo
 */
class ComandaApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Comandas
     * @Get("/api/comandas", name="api_comanda_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCOMANDAS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Comanda::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $comandas = Comanda::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($comandas as $comanda) {
            $itens[] = $comanda->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Comanda
     * @Post("/api/comandas", name="api_comanda_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROCOMANDAS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $comanda = new Comanda($this->getData());
        $comanda->filter(new Comanda(), app()->auth->provider, $localized);
        $comanda->insert();
        return $this->getResponse()->success(['item' => $comanda->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Comanda
     * @Patch("/api/comandas/{id}", name="api_comanda_update", params={ "id": "\d+" })
     *
     * @param int $id Comanda id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCOMANDAS]);
        $old_comanda = Comanda::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_comanda->toArray());
        $comanda = new Comanda($data);
        $comanda->filter($old_comanda, app()->auth->provider, $localized);
        $comanda->update();
        $old_comanda->clean($comanda);
        return $this->getResponse()->success(['item' => $comanda->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Comanda
     * @Delete("/api/comandas/{id}", name="api_comanda_delete", params={ "id": "\d+" })
     *
     * @param int $id Comanda id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCOMANDAS]);
        $comanda = Comanda::findOrFail(['id' => $id]);
        $comanda->delete();
        $comanda->clean(new Comanda());
        return $this->getResponse()->success([]);
    }
}
