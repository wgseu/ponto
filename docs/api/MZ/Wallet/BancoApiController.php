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

namespace MZ\Wallet;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Bancos disponíveis no país
 */
class BancoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Bancos
     * @Get("/api/bancos", name="api_banco_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROBANCOS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Banco::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $bancos = Banco::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($bancos as $banco) {
            $itens[] = $banco->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Banco
     * @Post("/api/bancos", name="api_banco_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROBANCOS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $banco = new Banco($this->getData());
        $banco->filter(new Banco(), app()->auth->provider, $localized);
        $banco->insert();
        return $this->getResponse()->success(['item' => $banco->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Banco
     * @Patch("/api/bancos/{id}", name="api_banco_update", params={ "id": "\d+" })
     *
     * @param int $id Banco id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROBANCOS]);
        $old_banco = Banco::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_banco->toArray());
        $banco = new Banco($data);
        $banco->filter($old_banco, app()->auth->provider, $localized);
        $banco->update();
        $old_banco->clean($banco);
        return $this->getResponse()->success(['item' => $banco->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Banco
     * @Delete("/api/bancos/{id}", name="api_banco_delete", params={ "id": "\d+" })
     *
     * @param int $id Banco id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROBANCOS]);
        $banco = Banco::findOrFail(['id' => $id]);
        $banco->delete();
        $banco->clean(new Banco());
        return $this->getResponse()->success([]);
    }
}
