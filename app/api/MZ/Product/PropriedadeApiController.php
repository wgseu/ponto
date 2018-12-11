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
 * Allow application to serve system resources
 */
class PropriedadeApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Propriedades
     * @Get("/api/propriedades", name="api_propriedade_find")
     */
    public function find()
    {
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $propriedades = Propriedade::findAll($condition, $order);
        $itens = [];
        foreach ($propriedades as $propriedade) {
            $itens[] = $propriedade->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens]);
    }

    /**
     * Create a new Propriedade
     * @Post("/api/propriedades", name="api_propriedade_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $propriedade = new Propriedade($this->getData());
        $propriedade->filter(new Propriedade(), app()->auth->provider, $localized);
        $propriedade->insert();
        return $this->getResponse()->success(['item' => $propriedade->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Propriedade
     * @Patch("/api/propriedades/{id}", name="api_propriedade_update", params={ "id": "\d+" })
     * 
     * @param int $id Propriedade id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $old_propriedade = Propriedade::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = array_merge($old_propriedade->toArray(), $this->getData());
        $propriedade = new Propriedade($data);
        $propriedade->filter($old_propriedade, app()->auth->provider, $localized);
        $propriedade->update();
        $old_propriedade->clean($propriedade);
        return $this->getResponse()->success(['item' => $propriedade->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Propriedade
     * @Delete("/api/propriedades/{id}", name="api_propriedade_delete", params={ "id": "\d+" })
     * 
     * @param int $id Propriedade id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $propriedade = Propriedade::findOrFail(['id' => $id]);
        $propriedade->delete();
        $propriedade->clean(new Propriedade());
        return $this->getResponse()->success([]);
    }
}
