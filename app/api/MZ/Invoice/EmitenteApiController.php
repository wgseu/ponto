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
namespace MZ\Invoice;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Dados do emitente das notas fiscais
 */
class EmitenteApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Emitentes
     * @Get("/api/emitentes", name="api_emitente_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Emitente::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $emitentes = Emitente::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($emitentes as $emitente) {
            $itens[] = $emitente->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Emitente
     * @Post("/api/emitentes", name="api_emitente_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $emitente = new Emitente($this->getData());
        $emitente->filter(new Emitente(), app()->auth->provider, $localized);
        $emitente->insert();
        return $this->getResponse()->success(['item' => $emitente->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Emitente
     * @Patch("/api/emitentes/{id}", name="api_emitente_update", params={ "id": "\d+" })
     *
     * @param int $id Emitente id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $old_emitente = Emitente::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_emitente->toArray());
        $emitente = new Emitente($data);
        $emitente->filter($old_emitente, app()->auth->provider, $localized);
        $emitente->update();
        $old_emitente->clean($emitente);
        return $this->getResponse()->success(['item' => $emitente->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Emitente
     * @Delete("/api/emitentes/{id}", name="api_emitente_delete", params={ "id": "\d+" })
     *
     * @param int $id Emitente id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $emitente = Emitente::findOrFail(['id' => $id]);
        $emitente->delete();
        $emitente->clean(new Emitente());
        return $this->getResponse()->success([]);
    }
}
