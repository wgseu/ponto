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
namespace MZ\Environment;

use MZ\System\Permissao;
use MZ\Util\Filter;
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class SetorPageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_ESTOQUE]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $setor = new Setor($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Setor::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genPages();
        $setores = Setor::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($setores as $_setor) {
                $items[] = $_setor->publish(app()->auth->provider);
            }
            return $this->json()->success(['items' => $items]);
        }

        return $this->view('gerenciar_setor_index', get_defined_vars());
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_ESTOQUE]);
        $id = $this->getRequest()->query->getInt('id', null);
        $setor = Setor::findByID($id);
        $setor->setID(null);

        $focusctrl = 'nome';
        $errors = [];
        $old_setor = $setor;
        if ($this->getRequest()->isMethod('POST')) {
            $setor = new Setor($this->getData());
            try {
                $setor->filter($old_setor, app()->auth->provider, true);
                $setor->insert();
                $old_setor->clean($setor);
                $msg = sprintf(
                    'Setor "%s" cadastrado com sucesso!',
                    $setor->getNome()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $setor->publish(app()->auth->provider)], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/setor/');
            } catch (\Exception $e) {
                $setor->clean($old_setor);
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
        return $this->view('gerenciar_setor_cadastrar', get_defined_vars());
    }

    public function update()
    {
        $this->needPermission([Permissao::NOME_ESTOQUE]);
        $id = $this->getRequest()->query->getInt('id', null);
        $setor = Setor::findByID($id);
        if (!$setor->exists()) {
            $msg = 'O setor não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/setor/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_setor = $setor;
        if ($this->getRequest()->isMethod('POST')) {
            $setor = new Setor($this->getData());
            try {
                $setor->setID($old_setor->getID());
                $setor->filter($old_setor, app()->auth->provider, true);
                $setor->update();
                $old_setor->clean($setor);
                $msg = sprintf(
                    'Setor "%s" atualizado com sucesso!',
                    $setor->getNome()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $setor->publish(app()->auth->provider)], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/setor/');
            } catch (\Exception $e) {
                $setor->clean($old_setor);
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
        return $this->view('gerenciar_setor_editar', get_defined_vars());
    }

    public function delete()
    {
        $this->needPermission([Permissao::NOME_ESTOQUE]);
        $id = $this->getRequest()->query->getInt('id', null);
        $setor = Setor::findByID($id);
        if (!$setor->exists()) {
            $msg = 'O setor não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/setor/');
        }
        try {
            $setor->delete();
            $setor->clean(new Setor());
            $msg = sprintf('Setor "%s" excluído com sucesso!', $setor->getNome());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o setor "%s"',
                $setor->getNome()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/setor/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'setor_find',
                'path' => '/gerenciar/setor/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'setor_add',
                'path' => '/gerenciar/setor/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'setor_update',
                'path' => '/gerenciar/setor/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'setor_delete',
                'path' => '/gerenciar/setor/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
