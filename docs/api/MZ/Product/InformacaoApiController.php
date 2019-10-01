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
 * Permite cadastrar informações da tabela nutricional
 */
class InformacaoApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Informações nutricionais
     * @Get("/api/informacoes_nutricionais", name="api_informacao_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Informacao::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $informacoes_nutricionais = Informacao::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($informacoes_nutricionais as $informacao) {
            $itens[] = $informacao->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Informação nutricional
     * @Post("/api/informacoes_nutricionais", name="api_informacao_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $informacao = new Informacao($this->getData());
        $informacao->filter(new Informacao(), app()->auth->provider, $localized);
        $informacao->insert();
        return $this->getResponse()->success(['item' => $informacao->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Informação nutricional
     * @Patch("/api/informacoes_nutricionais/{id}", name="api_informacao_update", params={ "id": "\d+" })
     *
     * @param int $id Informação nutricional id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $old_informacao = Informacao::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_informacao->toArray());
        $informacao = new Informacao($data);
        $informacao->filter($old_informacao, app()->auth->provider, $localized);
        $informacao->update();
        $old_informacao->clean($informacao);
        return $this->getResponse()->success(['item' => $informacao->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Informação nutricional
     * @Delete("/api/informacoes_nutricionais/{id}", name="api_informacao_delete", params={ "id": "\d+" })
     *
     * @param int $id Informação nutricional id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $informacao = Informacao::findOrFail(['id' => $id]);
        $informacao->delete();
        $informacao->clean(new Informacao());
        return $this->getResponse()->success([]);
    }
}
