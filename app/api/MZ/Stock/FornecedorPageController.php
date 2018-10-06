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
namespace MZ\Stock;

use MZ\System\Permissao;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class FornecedorPageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROFORNECEDORES]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $fornecedor = new Fornecedor($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Fornecedor::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genBasic();
        $fornecedores = Fornecedor::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($fornecedores as $_fornecedor) {
                $items[] = $_fornecedor->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $empresa_id_obj = $fornecedor->findEmpresaID();
        return $this->view('gerenciar_fornecedor_index', get_defined_vars());
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROFORNECEDORES]);
        $id = $this->getRequest()->query->getInt('id', null);
        $fornecedor = Fornecedor::findByID($id);
        $fornecedor->setID(null);

        $focusctrl = 'empresaid';
        $errors = [];
        $old_fornecedor = $fornecedor;
        if ($this->getRequest()->isMethod('POST')) {
            $fornecedor = new Fornecedor($this->getData());
            try {
                $fornecedor->filter($old_fornecedor, true);
                $fornecedor->insert();
                $old_fornecedor->clean($fornecedor);
                $msg = sprintf(
                    'Fornecedor "%s" cadastrado com sucesso!',
                    $fornecedor->getEmpresaID()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $fornecedor->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/fornecedor/');
            } catch (\Exception $e) {
                $fornecedor->clean($old_fornecedor);
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
            $fornecedor->setPrazoPagamento(30);
        }
        $empresa_id_obj = $fornecedor->findEmpresaID();
        return $this->view('gerenciar_fornecedor_cadastrar', get_defined_vars());
    }

    public function update()
    {
        $this->needPermission([Permissao::NOME_CADASTROFORNECEDORES]);
        $id = $this->getRequest()->query->getInt('id', null);
        $fornecedor = Fornecedor::findByID($id);
        if (!$fornecedor->exists()) {
            $msg = 'O fornecedor não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/fornecedor/');
        }
        $focusctrl = 'empresaid';
        $errors = [];
        $old_fornecedor = $fornecedor;
        if ($this->getRequest()->isMethod('POST')) {
            $fornecedor = new Fornecedor($this->getData());
            try {
                $fornecedor->filter($old_fornecedor, true);
                $fornecedor->update();
                $old_fornecedor->clean($fornecedor);
                $msg = sprintf(
                    'Fornecedor "%s" atualizado com sucesso!',
                    $fornecedor->getEmpresaID()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $fornecedor->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/fornecedor/');
            } catch (\Exception $e) {
                $fornecedor->clean($old_fornecedor);
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
        $empresa_id_obj = $fornecedor->findEmpresaID();
        return $this->view('gerenciar_fornecedor_editar', get_defined_vars());
    }

    public function delete()
    {
        $this->needPermission([Permissao::NOME_CADASTROFORNECEDORES]);
        $id = $this->getRequest()->query->getInt('id', null);
        $fornecedor = Fornecedor::findByID($id);
        if (!$fornecedor->exists()) {
            $msg = 'O fornecedor não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/fornecedor/');
        }
        $empresa_id_obj = $fornecedor->findEmpresaID();
        try {
            $fornecedor->delete();
            $fornecedor->clean(new Fornecedor());
            $msg = sprintf('Fornecedor "%s" excluído com sucesso!', $empresa_id_obj->getNomeCompleto());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o fornecedor "%s"',
                $empresa_id_obj->getNomeCompleto()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/fornecedor/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'fornecedor_find',
                'path' => '/gerenciar/fornecedor/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'fornecedor_add',
                'path' => '/gerenciar/fornecedor/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'fornecedor_update',
                'path' => '/gerenciar/fornecedor/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'fornecedor_delete',
                'path' => '/gerenciar/fornecedor/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
