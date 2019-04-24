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
 * Registra todas as atividades importantes do sistema
 */
class AuditoriaApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Auditorias
     * @Get("/api/auditorias", name="api_auditoria_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_RELATORIOAUDITORIA]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Auditoria::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $auditorias = Auditoria::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($auditorias as $auditoria) {
            $itens[] = $auditoria->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Auditoria
     * @Post("/api/auditorias", name="api_auditoria_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_RELATORIOAUDITORIA]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $auditoria = new Auditoria($this->getData());
        $auditoria->filter(new Auditoria(), app()->auth->provider, $localized);
        $auditoria->insert();
        return $this->getResponse()->success(['item' => $auditoria->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Auditoria
     * @Patch("/api/auditorias/{id}", name="api_auditoria_update", params={ "id": "\d+" })
     *
     * @param int $id Auditoria id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_RELATORIOAUDITORIA]);
        $old_auditoria = Auditoria::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_auditoria->toArray());
        $auditoria = new Auditoria($data);
        $auditoria->filter($old_auditoria, app()->auth->provider, $localized);
        $auditoria->update();
        $old_auditoria->clean($auditoria);
        return $this->getResponse()->success(['item' => $auditoria->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Auditoria
     * @Delete("/api/auditorias/{id}", name="api_auditoria_delete", params={ "id": "\d+" })
     *
     * @param int $id Auditoria id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_RELATORIOAUDITORIA]);
        $auditoria = Auditoria::findOrFail(['id' => $id]);
        $auditoria->delete();
        $auditoria->clean(new Auditoria());
        return $this->getResponse()->success([]);
    }
}