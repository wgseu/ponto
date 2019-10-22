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
 * Allow application to serve system resources
 */
class ServicoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Serviços
     * @Get("/api/servicos", name="api_servico_find")
     */
    public function find()
    {
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        if (!app()->auth->has([Permissao::NOME_CADASTROSERVICOS])) {
            $condition['ativo'] = 'Y';
        }
        $order = $this->getRequest()->query->get('order', '');
        $count = Servico::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $servicos = Servico::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($servicos as $servico) {
            $itens[] = $servico->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Serviço
     * @Post("/api/servicos", name="api_servico_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROSERVICOS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $servico = new Servico($this->getData());
        $servico->filter(new Servico(), app()->auth->provider, $localized);
        $servico->insert();
        return $this->getResponse()->success(['item' => $servico->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Serviço
     * @Patch("/api/servicos/{id}", name="api_servico_update", params={ "id": "\d+" })
     * 
     * @param int $id Serviço id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROSERVICOS]);
        $old_servico = Servico::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = array_merge($old_servico->toArray(), $this->getData());
        $servico = new Servico($data);
        $servico->filter($old_servico, app()->auth->provider, $localized);
        $servico->update();
        $old_servico->clean($servico);
        return $this->getResponse()->success(['item' => $servico->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Serviço
     * @Delete("/api/servicos/{id}", name="api_servico_delete", params={ "id": "\d+" })
     * 
     * @param int $id Serviço id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROSERVICOS]);
        $servico = Servico::findOrFail(['id' => $id]);
        $servico->delete();
        $servico->clean(new Servico());
        return $this->getResponse()->success([]);
    }
}
