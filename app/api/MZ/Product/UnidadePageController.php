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
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class UnidadePageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $unidade = new Unidade($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Unidade::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genPages();
        $unidades = Unidade::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($unidades as $_unidade) {
                $items[] = $_unidade->publish(app()->auth->provider);
            }
            return $this->json()->success(['items' => $items]);
        }

        return $this->view('gerenciar_unidade_index', get_defined_vars());
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $unidade = Unidade::findByID($id);
        $unidade->setID(null);

        $focusctrl = 'nome';
        $errors = [];
        $old_unidade = $unidade;
        if ($this->getRequest()->isMethod('POST')) {
            $unidade = new Unidade($this->getData());
            try {
                $unidade->filter($old_unidade, app()->auth->provider, true);
                $unidade->insert();
                $old_unidade->clean($unidade);
                $msg = sprintf(
                    'Unidade "%s" cadastrada com sucesso!',
                    $unidade->getNome()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $unidade->publish(app()->auth->provider)], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/unidade/');
            } catch (\Exception $e) {
                $unidade->clean($old_unidade);
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
        } elseif ($this->isJson()) {
            return $this->json()->error('Nenhum dado foi enviado');
        }
        return $this->view('gerenciar_unidade_cadastrar', get_defined_vars());
    }

    public function update()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $unidade = Unidade::findByID($id);
        if (!$unidade->exists()) {
            $msg = 'A unidade não foi informada ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/unidade/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_unidade = $unidade;
        if ($this->getRequest()->isMethod('POST')) {
            $unidade = new Unidade($this->getData());
            try {
                $unidade->filter($old_unidade, app()->auth->provider, true);
                $unidade->update();
                $old_unidade->clean($unidade);
                $msg = sprintf(
                    'Unidade "%s" atualizada com sucesso!',
                    $unidade->getNome()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $unidade->publish(app()->auth->provider)], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/unidade/');
            } catch (\Exception $e) {
                $unidade->clean($old_unidade);
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
        } elseif ($this->isJson()) {
            return $this->json()->error('Nenhum dado foi enviado');
        }
        return $this->view('gerenciar_unidade_editar', get_defined_vars());
    }

    public function delete()
    {
        $this->needPermission([Permissao::NOME_CADASTROPRODUTOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $unidade = Unidade::findByID($id);
        if (!$unidade->exists()) {
            $msg = 'A unidade não foi informada ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/unidade/');
        }
        try {
            $unidade->delete();
            $unidade->clean(new Unidade());
            $msg = sprintf('Unidade "%s" excluída com sucesso!', $unidade->getNome());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a unidade "%s"',
                $unidade->getNome()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/unidade/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'unidade_find',
                'path' => '/gerenciar/unidade/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'unidade_add',
                'path' => '/gerenciar/unidade/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'unidade_update',
                'path' => '/gerenciar/unidade/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'unidade_delete',
                'path' => '/gerenciar/unidade/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
