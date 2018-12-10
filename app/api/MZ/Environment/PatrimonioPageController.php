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
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class PatrimonioPageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROPATRIMONIO]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $patrimonio = new Patrimonio($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Patrimonio::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genPages();
        $patrimonios = Patrimonio::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($patrimonios as $_patrimonio) {
                $items[] = $_patrimonio->publish(app()->auth->provider);
            }
            return $this->json()->success(['items' => $items]);
        }

        $_empresa =  $patrimonio->findEmpresaID();
        $_fornecedor = $patrimonio->findFornecedorID();
        $_estado_names = Patrimonio::getEstadoOptions();
        return $this->view('gerenciar_patrimonio_index', get_defined_vars());
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROPATRIMONIO]);
        $id = $this->getRequest()->query->getInt('id', null);
        $patrimonio = Patrimonio::findByID($id);
        $patrimonio->setID(null);
        $patrimonio->setImagemAnexada(null);

        $focusctrl = 'descricao';
        $errors = [];
        $old_patrimonio = $patrimonio;
        if ($this->getRequest()->isMethod('POST')) {
            $patrimonio = new Patrimonio($this->getData());
            try {
                $patrimonio->filter($old_patrimonio, app()->auth->provider, true);
                $patrimonio->insert();
                $old_patrimonio->clean($patrimonio);
                $msg = sprintf(
                    'Patrimônio "%s" cadastrado com sucesso!',
                    $patrimonio->getDescricao()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $patrimonio->publish(app()->auth->provider)], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/patrimonio/');
            } catch (\Exception $e) {
                $patrimonio->clean($old_patrimonio);
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
        } elseif (is_null($patrimonio->getDescricao())) {
            $patrimonio->setCusto(0.0);
            $patrimonio->setAltura(0.0);
            $patrimonio->setLargura(0.0);
            $patrimonio->setComprimento(0.0);
            $patrimonio->setValor(0.0);
            $patrimonio->setAtivo('Y');
        }
        return $this->view('gerenciar_patrimonio_cadastrar', get_defined_vars());
    }

    public function update()
    {
        $this->needPermission([Permissao::NOME_CADASTROPATRIMONIO]);
        $id = $this->getRequest()->query->getInt('id', null);
        $patrimonio = Patrimonio::findByID($id);
        if (!$patrimonio->exists()) {
            $msg = 'O patrimônio não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/patrimonio/');
        }
        $focusctrl = 'descricao';
        $errors = [];
        $old_patrimonio = $patrimonio;
        if ($this->getRequest()->isMethod('POST')) {
            $patrimonio = new Patrimonio($this->getData());
            try {
                $patrimonio->filter($old_patrimonio, app()->auth->provider, true);
                $patrimonio->update();
                $old_patrimonio->clean($patrimonio);
                $msg = sprintf(
                    'Patrimônio "%s" atualizado com sucesso!',
                    $patrimonio->getDescricao()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $patrimonio->publish(app()->auth->provider)], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/patrimonio/');
            } catch (\Exception $e) {
                $patrimonio->clean($old_patrimonio);
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
        return $this->view('gerenciar_patrimonio_editar', get_defined_vars());
    }

    public function delete()
    {
        $this->needPermission([Permissao::NOME_CADASTROPATRIMONIO]);
        $id = $this->getRequest()->query->getInt('id', null);
        $patrimonio = Patrimonio::findByID($id);
        if (!$patrimonio->exists()) {
            $msg = 'O patrimônio não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/patrimonio/');
        }
        try {
            $patrimonio->delete();
            $patrimonio->clean(new Patrimonio());
            $msg = sprintf('Patrimônio "%s" excluído com sucesso!', $patrimonio->getDescricao());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o patrimônio "%s"',
                $patrimonio->getDescricao()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/patrimonio/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'patrimonio_find',
                'path' => '/gerenciar/patrimonio/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'patrimonio_add',
                'path' => '/gerenciar/patrimonio/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'patrimonio_update',
                'path' => '/gerenciar/patrimonio/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'patrimonio_delete',
                'path' => '/gerenciar/patrimonio/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
