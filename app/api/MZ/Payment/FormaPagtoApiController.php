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
 * Allow application to serve system resources
 */
class FormaPagtoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Formas de pagamento
     * @Get("/api/formas_de_pagamento", name="api_forma_pagto_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROFORMASPAGTO]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = FormaPagto::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $formas_de_pagamento = FormaPagto::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($formas_de_pagamento as $forma_pagto) {
            $itens[] = $forma_pagto->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Forma de pagamento
     * @Post("/api/formas_de_pagamento", name="api_forma_pagto_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROFORMASPAGTO]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $forma_pagto = new FormaPagto($this->getData());
        $forma_pagto->filter(new FormaPagto(), app()->auth->provider, $localized);
        $forma_pagto->insert();
        return $this->getResponse()->success(['item' => $forma_pagto->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Forma de pagamento
     * @Patch("/api/formas_de_pagamento/{id}", name="api_forma_pagto_update", params={ "id": "\d+" })
     * 
     * @param int $id Forma de pagamento id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROFORMASPAGTO]);
        $old_forma_pagto = FormaPagto::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = array_merge($old_forma_pagto->toArray(), $this->getData());
        $forma_pagto = new FormaPagto($data);
        $forma_pagto->filter($old_forma_pagto, app()->auth->provider, $localized);
        $forma_pagto->update();
        $old_forma_pagto->clean($forma_pagto);
        return $this->getResponse()->success(['item' => $forma_pagto->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Forma de pagamento
     * @Delete("/api/formas_de_pagamento/{id}", name="api_forma_pagto_delete", params={ "id": "\d+" })
     * 
     * @param int $id Forma de pagamento id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROFORMASPAGTO]);
        $forma_pagto = FormaPagto::findOrFail(['id' => $id]);
        $forma_pagto->delete();
        $forma_pagto->clean(new FormaPagto());
        return $this->getResponse()->success([]);
    }
}
