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
namespace MZ\System;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Módulos do sistema que podem ser desativados/ativados
 */
class ModuloApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Módulos
     * @Get("/api/modulos", name="api_modulo_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = $this->getRequest()->query->get('order', '');
        $count = Modulo::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $modulos = Modulo::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($modulos as $modulo) {
            $itens[] = $modulo->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Modify parts of an existing Módulo
     * @Patch("/api/modulos/{id}", name="api_modulo_update", params={ "id": "\d+" })
     *
     * @param int $id Módulo id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $old_modulo = Modulo::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_modulo->toArray());
        $modulo = new Modulo($data);
        $modulo->filter($old_modulo, app()->auth->provider, $localized);
        $modulo->update();
        $old_modulo->clean($modulo);
        return $this->getResponse()->success(['item' => $modulo->publish(app()->auth->provider)]);
    }
}
