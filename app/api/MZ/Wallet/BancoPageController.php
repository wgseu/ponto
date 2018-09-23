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
namespace MZ\Wallet;

use MZ\System\Permissao;
use MZ\Util\Filter;
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class BancoPageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROBANCOS]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $banco = new Banco($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Banco::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genBasic();
        $bancos = Banco::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($bancos as $_banco) {
                $items[] = $_banco->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        return $this->view('gerenciar_banco_index', get_defined_vars());
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROBANCOS]);
        $focusctrl = 'numero';
        $errors = [];
        $banco = new Banco();
        $old_banco = $banco;
        if (is_post()) {
            $banco = new Banco($this->getData());
            try {
                $banco->filter($old_banco, true);
                $banco->insert();
                $msg = sprintf(
                    'Banco "%s" cadastrado com sucesso!',
                    $banco->getRazaoSocial()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $banco->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/banco/');
            } catch (\Exception $e) {
                $banco->clean($old_banco);
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
        return $this->view('gerenciar_banco_cadastrar', get_defined_vars());
    }

    public function update()
    {
        $this->needPermission([Permissao::NOME_CADASTROBANCOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $banco = Banco::findByID($id);
        if (!$banco->exists()) {
            $msg = 'O banco informado não existe!';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/banco/');
        }
        $focusctrl = 'razaosocial';
        $errors = [];
        $old_banco = $banco;
        if (is_post()) {
            $banco = new Banco($this->getData());
            try {
                $banco->filter($old_banco, true);
                $banco->update();
                $msg = sprintf(
                    'Banco "%s" atualizado com sucesso!',
                    $banco->getRazaoSocial()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $banco->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/banco/');
            } catch (\Exception $e) {
                $banco->clean($old_banco);
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
        return $this->view('gerenciar_banco_editar', get_defined_vars());
    }

    public function delete()
    {
        $this->needPermission([Permissao::NOME_CADASTROBANCOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $banco = Banco::findByID($id);
        if (!$banco->exists()) {
            $msg = 'O banco não foi informado ou não existe!';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/banco/');
        }
        try {
            $banco->delete();
            $banco->clean(new Banco());
            $msg = sprintf('Banco "%s" excluído com sucesso!', $banco->getRazaoSocial());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o banco "%s"!',
                $banco->getRazaoSocial()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/banco/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'banco_find',
                'path' => '/gerenciar/banco/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'banco_add',
                'path' => '/gerenciar/banco/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'banco_update',
                'path' => '/gerenciar/banco/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'banco_delete',
                'path' => '/gerenciar/banco/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
