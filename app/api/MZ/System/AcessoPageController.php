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
use MZ\Provider\Funcao;
use MZ\Util\Filter;
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class AcessoPageController extends PageController
{
    public function manage()
    {
        app()->needOwner();
        $funcao_id = $this->getRequest()->query->get('funcao');
        $funcao_id = $this->getRequest()->request->get('funcao', $funcao_id);
        $funcao = Funcao::findByID($funcao_id);
        if (!$funcao->exists()) {
            if ($this->isJson()) {
                return $this->json()->error('A função não foi informada ou não existe');
            }
            return $this->redirect('/gerenciar/funcao/');
        }
        $errors = [];
        if ($this->getRequest()->isMethod('POST')) {
            try {
                $permissao_id = $this->getRequest()->request->get('permissao');
                $permissao = Permissao::findByID($permissao_id);
                if (!$permissao->exists()) {
                    throw new \Exception('A permissão não foi informada ou não existe', 404);
                }
                if ($this->getRequest()->request->get('marcado') == 'Y') {
                    $acesso = new Acesso();
                    $acesso->setFuncaoID($funcao->getID());
                    $acesso->setPermissaoID($permissao->getID());
                    $acesso->insert();
                } else {
                    $acesso = Acesso::find([
                        'funcaoid' => $funcao->getID(),
                        'permissaoid' => $permissao->getID()
                    ]);
                    $acesso->delete();
                }
                if ($this->isJson()) {
                    return $this->json()->success();
                }
                return $this->redirect('/gerenciar/acesso/?funcao=' . $funcao->getID());
            } catch (\Exception $e) {
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if ($this->isJson()) {
                    return $this->json()->error($e->getMessage(), null, $errors);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
            }
        }
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $permissoes = Permissao::findAll($condition, $order);

        if ($this->isJson()) {
            $items = [];
            foreach ($permissoes as $permissao) {
                $item = $permissao->publish(app()->auth->provider);
                $acesso = Acesso::find([
                    'funcaoid' => $funcao->getID(),
                    'permissaoid' => $permissao->getID()
                ]);
                $item['marcado'] = $acesso->exists() ? 'Y' : 'N';
                $items[] = $item;
            }
            return $this->json()->success(['items' => $items]);
        }
        return $this->view('gerenciar_acesso_index', get_defined_vars());
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'acesso_manage',
                'path' => '/gerenciar/acesso/',
                'method' => ['GET', 'POST'],
                'controller' => 'manage',
            ],
        ];
    }
}
