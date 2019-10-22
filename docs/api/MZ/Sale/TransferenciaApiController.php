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
 * Informa a transferência de uma mesa / comanda para outra, ou de um
 * produto para outra mesa / comanda
 */
class TransferenciaApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Transferências
     * @Get("/api/transferencias", name="api_transferencia_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_TRANSFERIRPRODUTOS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Transferencia::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $transferencias = Transferencia::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($transferencias as $transferencia) {
            $itens[] = $transferencia->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Transferência
     * @Post("/api/transferencias", name="api_transferencia_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_TRANSFERIRPRODUTOS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $transferencia = new Transferencia($this->getData());
        $transferencia->filter(new Transferencia(), app()->auth->provider, $localized);
        $transferencia->insert();
        return $this->getResponse()->success(['item' => $transferencia->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Transferência
     * @Patch("/api/transferencias/{id}", name="api_transferencia_update", params={ "id": "\d+" })
     *
     * @param int $id Transferência id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_TRANSFERIRPRODUTOS]);
        $old_transferencia = Transferencia::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_transferencia->toArray());
        $transferencia = new Transferencia($data);
        $transferencia->filter($old_transferencia, app()->auth->provider, $localized);
        $transferencia->update();
        $old_transferencia->clean($transferencia);
        return $this->getResponse()->success(['item' => $transferencia->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Transferência
     * @Delete("/api/transferencias/{id}", name="api_transferencia_delete", params={ "id": "\d+" })
     *
     * @param int $id Transferência id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_TRANSFERIRPRODUTOS]);
        $transferencia = Transferencia::findOrFail(['id' => $id]);
        $transferencia->delete();
        $transferencia->clean(new Transferencia());
        return $this->getResponse()->success([]);
    }
}
