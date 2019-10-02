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
namespace MZ\Sale;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Informa qual foi a formação que gerou esse produto, assim como quais
 * item foram retirados/adicionados da composição
 */
class FormacaoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Formações
     * @Get("/api/formacoes", name="api_formacao_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Formacao::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $formacoes = Formacao::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($formacoes as $formacao) {
            $itens[] = $formacao->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Formação
     * @Post("/api/formacoes", name="api_formacao_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $formacao = new Formacao($this->getData());
        $formacao->filter(new Formacao(), app()->auth->provider, $localized);
        $formacao->insert();
        return $this->getResponse()->success(['item' => $formacao->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Formação
     * @Patch("/api/formacoes/{id}", name="api_formacao_update", params={ "id": "\d+" })
     *
     * @param int $id Formação id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $old_formacao = Formacao::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_formacao->toArray());
        $formacao = new Formacao($data);
        $formacao->filter($old_formacao, app()->auth->provider, $localized);
        $formacao->update();
        $old_formacao->clean($formacao);
        return $this->getResponse()->success(['item' => $formacao->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Formação
     * @Delete("/api/formacoes/{id}", name="api_formacao_delete", params={ "id": "\d+" })
     *
     * @param int $id Formação id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);
        $formacao = Formacao::findOrFail(['id' => $id]);
        $formacao->delete();
        $formacao->clean(new Formacao());
        return $this->getResponse()->success([]);
    }
}
