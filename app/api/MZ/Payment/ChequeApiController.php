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
namespace MZ\Payment;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Folha de cheque lançado como pagamento
 */
class ChequeApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Cheques
     * @Get("/api/cheques", name="api_cheque_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Cheque::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $cheques = Cheque::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($cheques as $cheque) {
            $itens[] = $cheque->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Cheque
     * @Post("/api/cheques", name="api_cheque_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $cheque = new Cheque($this->getData());
        $cheque->filter(new Cheque(), app()->auth->provider, $localized);
        $cheque->insert();
        return $this->getResponse()->success(['item' => $cheque->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Cheque
     * @Patch("/api/cheques/{id}", name="api_cheque_update", params={ "id": "\d+" })
     *
     * @param int $id Cheque id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $old_cheque = Cheque::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_cheque->toArray());
        $cheque = new Cheque($data);
        $cheque->filter($old_cheque, app()->auth->provider, $localized);
        $cheque->update();
        $old_cheque->clean($cheque);
        return $this->getResponse()->success(['item' => $cheque->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Cheque
     * @Delete("/api/cheques/{id}", name="api_cheque_delete", params={ "id": "\d+" })
     *
     * @param int $id Cheque id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $cheque = Cheque::findOrFail(['id' => $id]);
        $cheque->delete();
        $cheque->clean(new Cheque());
        return $this->getResponse()->success([]);
    }
}
