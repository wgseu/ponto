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
namespace MZ\Device;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Computadores e tablets com opções de acesso
 */
class DispositivoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Dispositivos
     * @Get("/api/dispositivos", name="api_dispositivo_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCOMPUTADORES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Dispositivo::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $dispositivos = Dispositivo::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($dispositivos as $dispositivo) {
            $itens[] = $dispositivo->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Dispositivo
     * @Post("/api/dispositivos", name="api_dispositivo_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROCOMPUTADORES]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $dispositivo = new Dispositivo($this->getData());
        $dispositivo->filter(new Dispositivo(), app()->auth->provider, $localized);
        $dispositivo->insert();
        return $this->getResponse()->success(['item' => $dispositivo->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Dispositivo
     * @Patch("/api/dispositivos/{id}", name="api_dispositivo_update", params={ "id": "\d+" })
     *
     * @param int $id Dispositivo id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCOMPUTADORES]);
        $old_dispositivo = Dispositivo::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_dispositivo->toArray());
        $dispositivo = new Dispositivo($data);
        $dispositivo->filter($old_dispositivo, app()->auth->provider, $localized);
        $dispositivo->update();
        $old_dispositivo->clean($dispositivo);
        return $this->getResponse()->success(['item' => $dispositivo->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Dispositivo
     * @Delete("/api/dispositivos/{id}", name="api_dispositivo_delete", params={ "id": "\d+" })
     *
     * @param int $id Dispositivo id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCOMPUTADORES]);
        $dispositivo = Dispositivo::findOrFail(['id' => $id]);
        $dispositivo->delete();
        $dispositivo->clean(new Dispositivo());
        return $this->getResponse()->success([]);
    }
}
