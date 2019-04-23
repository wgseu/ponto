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
 * Função ou atribuição de tarefas à um prestador
 */
class FuncaoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Funções
     * @Get("/api/funcoes", name="api_funcao_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Funcao::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $funcoes = Funcao::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($funcoes as $funcao) {
            $itens[] = $funcao->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Função
     * @Post("/api/funcoes", name="api_funcao_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $funcao = new Funcao($this->getData());
        $funcao->filter(new Funcao(), app()->auth->provider, $localized);
        $funcao->insert();
        return $this->getResponse()->success(['item' => $funcao->publish(app()->auth->provider)]);
    }
    /**
     * Modify parts of an existing Função
     * @Patch("/api/funcoes/{id}", name="api_funcao_update", params={ "id": "\d+" })
     *
     * @param int $id Função id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $old_funcao = Funcao::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_funcao->toArray());
        $funcao = new Funcao($data);
        $funcao->filter($old_funcao, app()->auth->provider, $localized);
        $funcao->update();
        $old_funcao->clean($funcao);
        return $this->getResponse()->success(['item' => $funcao->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Função
     * @Delete("/api/funcoes/{id}", name="api_funcao_delete", params={ "id": "\d+" })
     *
     * @param int $id Função id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $funcao = Funcao::findOrFail(['id' => $id]);
        $funcao->delete();
        $funcao->clean(new Funcao());
        return $this->getResponse()->success([]);
    }
}
