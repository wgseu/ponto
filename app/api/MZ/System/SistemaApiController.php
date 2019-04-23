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
 * Classe que informa detalhes da empresa, parceiro e opções do sistema
 * como a versão do banco de dados e a licença de uso
 */
class SistemaApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Sistemas
     * @Get("/api/sistemas", name="api_sistema_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Sistema::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $sistemas = Sistema::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($sistemas as $sistema) {
            $itens[] = $sistema->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Sistema
     * @Post("/api/sistemas", name="api_sistema_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $sistema = new Sistema($this->getData());
        $sistema->filter(new Sistema(), app()->auth->provider, $localized);
        $sistema->insert();
        return $this->getResponse()->success(['item' => $sistema->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Sistema
     * @Patch("/api/sistemas/{id}", name="api_sistema_update", params={ "id": "\d+" })
     *
     * @param int $id Sistema id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $old_sistema = Sistema::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_sistema->toArray());
        $sistema = new Sistema($data);
        $sistema->filter($old_sistema, app()->auth->provider, $localized);
        $sistema->update();
        $old_sistema->clean($sistema);
        return $this->getResponse()->success(['item' => $sistema->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Sistema
     * @Delete("/api/sistemas/{id}", name="api_sistema_delete", params={ "id": "\d+" })
     *
     * @param int $id Sistema id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $sistema = Sistema::findOrFail(['id' => $id]);
        $sistema->delete();
        $sistema->clean(new Sistema());
        return $this->getResponse()->success([]);
    }
}
