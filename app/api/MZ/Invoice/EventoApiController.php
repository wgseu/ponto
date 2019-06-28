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
 * Eventos de envio das notas
 */
class EventoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Eventos
     * @Get("/api/eventos", name="api_evento_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_RELATORIOAUDITORIA]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Evento::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $eventos = Evento::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($eventos as $evento) {
            $itens[] = $evento->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Evento
     * @Post("/api/eventos", name="api_evento_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_RELATORIOAUDITORIA]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $evento = new Evento($this->getData());
        $evento->filter(new Evento(), app()->auth->provider, $localized);
        $evento->insert();
        return $this->getResponse()->success(['item' => $evento->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Evento
     * @Patch("/api/eventos/{id}", name="api_evento_update", params={ "id": "\d+" })
     *
     * @param int $id Evento id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_RELATORIOAUDITORIA]);
        $old_evento = Evento::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_evento->toArray());
        $evento = new Evento($data);
        $evento->filter($old_evento, app()->auth->provider, $localized);
        $evento->update();
        $old_evento->clean($evento);
        return $this->getResponse()->success(['item' => $evento->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Evento
     * @Delete("/api/eventos/{id}", name="api_evento_delete", params={ "id": "\d+" })
     *
     * @param int $id Evento id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_RELATORIOAUDITORIA]);
        $evento = Evento::findOrFail(['id' => $id]);
        $evento->delete();
        $evento->clean(new Evento());
        return $this->getResponse()->success([]);
    }
}
