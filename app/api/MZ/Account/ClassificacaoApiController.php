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
namespace MZ\Account;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Classificação se contas, permite atribuir um grupo de contas
 */
class ClassificacaoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Classificações
     * @Get("/api/classificacoes", name="api_classificacao_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCONTAS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        if (isset($condition['classificacaoid']) && intval($condition['classificacaoid']) < 0) {
            unset($condition['classificacaoid']);
        } elseif ($this->getRequest()->query->has('classificacaoid')) {
            $condition['classificacaoid'] = isset($condition['classificacaoid']) ? $condition['classificacaoid'] : null;
        }
        $classificacao = new Classificacao($condition);
        $order = $this->getRequest()->query->get('order', '');
        $count = Classificacao::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $classificacoes = Classificacao::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($classificacoes as $classificacao) {
            $itens[] = $classificacao->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Classificação
     * @Post("/api/classificacoes", name="api_classificacao_add")
     */
    public function add()
    {

        $this->needPermission([Permissao::NOME_CADASTROCONTAS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $classificacao = new Classificacao($this->getData());
        $classificacao->filter(new Classificacao(), app()->auth->provider, $localized);
        $classificacao->insert();
        return $this->getResponse()->success(['item' => $classificacao->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Classificação
     * @Patch("/api/classificacoes/{id}", name="api_classificacao_update", params={ "id": "\d+" })
     *
     * @param int $id Classificação id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCONTAS]);
        $old_classificacao = Classificacao::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_classificacao->toArray());
        $classificacao = new Classificacao($data);
        $classificacao->filter($old_classificacao, app()->auth->provider, $localized);
        $classificacao->update();
        $old_classificacao->clean($classificacao);
        return $this->getResponse()->success(['item' => $classificacao->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Classificação
     * @Delete("/api/classificacoes/{id}", name="api_classificacao_delete", params={ "id": "\d+" })
     *
     * @param int $id Classificação id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROCONTAS]);
        $classificacao = Classificacao::findOrFail(['id' => $id]);
        $classificacao->delete();
        $classificacao->clean(new Classificacao());
        return $this->getResponse()->success([]);
    }
}
