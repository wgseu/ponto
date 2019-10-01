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
 * Informações da empresa
 */
class EmpresaApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Empresas
     * @Get("/api/empresas", name="api_empresa_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Empresa::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $empresas = Empresa::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($empresas as $empresa) {
            $itens[] = $empresa->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Empresa
     * @Post("/api/empresas", name="api_empresa_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $empresa = new Empresa($this->getData());
        $empresa->filter(new Empresa(), app()->auth->provider, $localized);
        $empresa->insert();
        return $this->getResponse()->success(['item' => $empresa->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Empresa
     * @Patch("/api/empresas/{id}", name="api_empresa_update", params={ "id": "\d+" })
     *
     * @param int $id Empresa id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $old_empresa = Empresa::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_empresa->toArray());
        $empresa = new Empresa($data);
        $empresa->filter($old_empresa, app()->auth->provider, $localized);
        $empresa->update();
        $old_empresa->clean($empresa);
        return $this->getResponse()->success(['item' => $empresa->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Empresa
     * @Delete("/api/empresas/{id}", name="api_empresa_delete", params={ "id": "\d+" })
     *
     * @param int $id Empresa id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $empresa = Empresa::findOrFail(['id' => $id]);
        $empresa->delete();
        $empresa->clean(new Empresa());
        return $this->getResponse()->success([]);
    }
}
