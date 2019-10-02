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
 * Informa todos os valores nutricionais da tabela nutricional
 */
class ValorNutricionalApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Valores nutricionais
     * @Get("/api/valores_nutricionais", name="api_valor_nutricional_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = ValorNutricional::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $valores_nutricionais = ValorNutricional::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($valores_nutricionais as $valor_nutricional) {
            $itens[] = $valor_nutricional->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Valor nutricional
     * @Post("/api/valores_nutricionais", name="api_valor_nutricional_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $valor_nutricional = new ValorNutricional($this->getData());
        $valor_nutricional->filter(new ValorNutricional(), app()->auth->provider, $localized);
        $valor_nutricional->insert();
        return $this->getResponse()->success(['item' => $valor_nutricional->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Valor nutricional
     * @Patch("/api/valores_nutricionais/{id}", name="api_valor_nutricional_update", params={ "id": "\d+" })
     *
     * @param int $id Valor nutricional id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $old_valor_nutricional = ValorNutricional::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_valor_nutricional->toArray());
        $valor_nutricional = new ValorNutricional($data);
        $valor_nutricional->filter($old_valor_nutricional, app()->auth->provider, $localized);
        $valor_nutricional->update();
        $old_valor_nutricional->clean($valor_nutricional);
        return $this->getResponse()->success(['item' => $valor_nutricional->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Valor nutricional
     * @Delete("/api/valores_nutricionais/{id}", name="api_valor_nutricional_delete", params={ "id": "\d+" })
     *
     * @param int $id Valor nutricional id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $valor_nutricional = ValorNutricional::findOrFail(['id' => $id]);
        $valor_nutricional->delete();
        $valor_nutricional->clean(new ValorNutricional());
        return $this->getResponse()->success([]);
    }
}
