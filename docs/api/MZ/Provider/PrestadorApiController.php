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
namespace MZ\Provider;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Prestador de serviço que realiza alguma tarefa na empresa
 */
class PrestadorApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Prestadores
     * @Get("/api/prestadores", name="api_prestador_find")
     */
    public function find()
    {
        app()->needManager();
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        if (!app()->auth->has([Permissao::NOME_CADASTROPRESTADORES])) {
            $condition['id'] = app()->auth->provider->getID();
        }
        $order = $this->getRequest()->query->get('order', '');
        $count = Prestador::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $prestadores = Prestador::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($prestadores as $prestador) {
            $itens[] = $prestador->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Prestador
     * @Post("/api/prestadores", name="api_prestador_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRESTADORES]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData();
        $prestador = new Prestador($data);
        $old_prestador = new Prestador();
        $old_prestador->setClienteID($prestador->getClienteID());
        $prestador->filter($old_prestador, app()->auth->provider, $localized);
        $funcao_id = $data['funcaoid'] ?? null;
        $funcao = \MZ\Provider\Funcao::findByID($funcao_id);
        $prestador->setFuncaoID($funcao->getID());
        $prestador->setAtivo('Y');
        $prestador->insert();
        return $this->getResponse()->success(['item' => $prestador->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Prestador
     * @Patch("/api/prestadores/{id}", name="api_prestador_update", params={ "id": "\d+" })
     *
     * @param int $id Prestador id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRESTADORES]);
        $old_prestador = Prestador::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_prestador->toArray());
        $prestador = new Prestador($data);
        $prestador->filter($old_prestador, app()->auth->provider, $localized);
        $prestador->update();
        $old_prestador->clean($prestador);
        return $this->getResponse()->success(['item' => $prestador->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Prestador
     * @Delete("/api/prestadores/{id}", name="api_prestador_delete", params={ "id": "\d+" })
     *
     * @param int $id Prestador id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRESTADORES]);
        $prestador = Prestador::findOrFail(['id' => $id]);
        $prestador->delete();
        $prestador->clean(new Prestador());
        return $this->getResponse()->success([]);
    }
}
