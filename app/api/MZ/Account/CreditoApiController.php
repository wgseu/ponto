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
namespace MZ\Account;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Créditos de clientes
 */
class CreditoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Créditos
     * @Get("/api/creditos", name="api_credito_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTRARCREDITOS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Credito::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $creditos = Credito::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($creditos as $credito) {
            $itens[] = $credito->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Cancel Créditos
     * @Get("/api/creditos", name="api_credito_cancel")
     */
    public function cancel()
    {
        $this->needPermission([Permissao::NOME_CADASTRARCREDITOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $credito = Credito::findOrFail($id);
        $credito->cancel();
        return $this->getResponse()->success([]);
    }

    /**
     * Create a new Crédito
     * @Post("/api/creditos", name="api_credito_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTRARCREDITOS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $credito = new Credito($this->getData());
        $credito->filter(new Credito(), app()->auth->provider, $localized);
        $credito->insert();
        return $this->getResponse()->success(['item' => $credito->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Crédito
     * @Patch("/api/creditos/{id}", name="api_credito_update", params={ "id": "\d+" })
     *
     * @param int $id Crédito id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTRARCREDITOS]);
        $old_credito = Credito::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_credito->toArray());
        $credito = new Credito($data);
        $credito->filter($old_credito, app()->auth->provider, $localized);
        $credito->update();
        $old_credito->clean($credito);
        return $this->getResponse()->success(['item' => $credito->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Crédito
     * @Delete("/api/creditos/{id}", name="api_credito_delete", params={ "id": "\d+" })
     *
     * @param int $id Crédito id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTRARCREDITOS]);
        $credito = Credito::findOrFail(['id' => $id]);
        $credito->delete();
        $credito->clean(new Credito());
        return $this->getResponse()->success([]);
    }
}
