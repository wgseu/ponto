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
namespace MZ\System;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Permite acesso à uma determinada funcionalidade da lista de permissões
 */
class AcessoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Acessos
     * @Get("/api/acessos", name="api_acesso_find")
     */
    public function find()
    {
        app()->needOwner();
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Acesso::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $acessos = Acesso::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($acessos as $acesso) {
            $itens[] = $acesso->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Acesso
     * @Post("/api/acessos", name="api_acesso_add")
     */
    public function add()
    {
        app()->needOwner();
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $acesso = new Acesso($this->getData());
        $acesso->filter(new Acesso(), app()->auth->provider, $localized);
        $acesso->insert();
        return $this->getResponse()->success(['item' => $acesso->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Acesso
     * @Patch("/api/acessos/{id}", name="api_acesso_update", params={ "id": "\d+" })
     *
     * @param int $id Acesso id
     */
    public function modify($id)
    {
        app()->needOwner();
        $old_acesso = Acesso::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_acesso->toArray());
        $acesso = new Acesso($data);
        $acesso->filter($old_acesso, app()->auth->provider, $localized);
        $acesso->update();
        $old_acesso->clean($acesso);
        return $this->getResponse()->success(['item' => $acesso->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Acesso
     * @Delete("/api/acessos/{id}", name="api_acesso_delete", params={ "id": "\d+" })
     *
     * @param int $id Acesso id to delete
     */
    public function delete($id)
    {
        app()->needOwner();
        $acesso = Acesso::findOrFail(['id' => $id]);
        $acesso->delete();
        $acesso->clean(new Acesso());
        return $this->getResponse()->success([]);
    }
}


