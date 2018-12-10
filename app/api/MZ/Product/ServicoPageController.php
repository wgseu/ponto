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
class ServicoPageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROSERVICOS]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $servico = new Servico($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Servico::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genPages();
        $servicos = Servico::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($servicos as $_servico) {
                $items[] = $_servico->publish(app()->auth->provider);
            }
            return $this->json()->success(['items' => $items]);
        }

        $tipos = Servico::getTipoOptions();

        return $this->view('gerenciar_servico_index', get_defined_vars());
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROSERVICOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $servico = Servico::findByID($id);
        $servico->setID(null);

        $focusctrl = 'nome';
        $errors = [];
        $old_servico = $servico;
        if ($this->getRequest()->isMethod('POST')) {
            $servico = new Servico($this->getData());
            try {
                $servico->filter($old_servico, app()->auth->provider, true);
                $servico->insert();
                $old_servico->clean($servico);
                $msg = sprintf(
                    'Serviço "%s" cadastrada com sucesso!',
                    $servico->getDescricao()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $servico->publish(app()->auth->provider)], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/servico/');
            } catch (\Exception $e) {
                $servico->clean($old_servico);
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
        } else {
            $servico->setAtivo('Y');
        }
        return $this->view('gerenciar_servico_cadastrar', get_defined_vars());
    }

    public function update()
    {
        $this->needPermission([Permissao::NOME_CADASTROSERVICOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $servico = Servico::findByID($id);
        if (!$servico->exists()) {
            $msg = 'O serviço não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/servico/');
        }
        $focusctrl = 'descricao';
        $errors = [];
        $old_servico = $servico;
        if ($this->getRequest()->isMethod('POST')) {
            $servico = new Servico($this->getData());
            try {
                $servico->filter($old_servico, app()->auth->provider, true);
                $servico->update();
                $old_servico->clean($servico);
                $msg = sprintf(
                    'Serviço "%s" atualizada com sucesso!',
                    $servico->getDescricao()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $servico->publish(app()->auth->provider)], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/servico/');
            } catch (\Exception $e) {
                $servico->clean($old_servico);
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
        return $this->view('gerenciar_servico_editar', get_defined_vars());
    }

    public function delete()
    {
        $this->needPermission([Permissao::NOME_CADASTROSERVICOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $servico = Servico::findByID($id);
        if (!$servico->exists()) {
            $msg = 'O serviço não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/servico/');
        }
        try {
            $servico->delete();
            $servico->clean(new Servico());
            $msg = sprintf('Serviço "%s" excluído com sucesso!', $servico->getDescricao());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o serviço "%s"',
                $servico->getDescricao()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/servico/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'servico_find',
                'path' => '/gerenciar/servico/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'servico_add',
                'path' => '/gerenciar/servico/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'servico_update',
                'path' => '/gerenciar/servico/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'servico_delete',
                'path' => '/gerenciar/servico/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
