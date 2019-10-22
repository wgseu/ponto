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

namespace MZ\Session;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Resumo de fechamento de caixa, informa o valor contado no fechamento do
 * caixa para cada forma de pagamento
 */
class ResumoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Resumos
     * @Get("/api/resumos", name="api_resumo_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CONFERIRCAIXA]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Resumo::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $resumos = Resumo::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($resumos as $resumo) {
            $itens[] = $resumo->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Resumo
     * @Post("/api/resumos", name="api_resumo_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CONFERIRCAIXA]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $resumo = new Resumo($this->getData());
        $resumo->filter(new Resumo(), app()->auth->provider, $localized);
        $resumo->insert();
        return $this->getResponse()->success(['item' => $resumo->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Resumo
     * @Patch("/api/resumos/{id}", name="api_resumo_update", params={ "id": "\d+" })
     *
     * @param int $id Resumo id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CONFERIRCAIXA]);
        $old_resumo = Resumo::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_resumo->toArray());
        $resumo = new Resumo($data);
        $resumo->filter($old_resumo, app()->auth->provider, $localized);
        $resumo->update();
        $old_resumo->clean($resumo);
        return $this->getResponse()->success(['item' => $resumo->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Resumo
     * @Delete("/api/resumos/{id}", name="api_resumo_delete", params={ "id": "\d+" })
     *
     * @param int $id Resumo id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CONFERIRCAIXA]);
        $resumo = Resumo::findOrFail(['id' => $id]);
        $resumo->delete();
        $resumo->clean(new Resumo());
        return $this->getResponse()->success([]);
    }
}
