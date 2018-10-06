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
namespace MZ\Session;

use MZ\System\Permissao;
use MZ\Util\Filter;
use MZ\Core\PageController;
use MZ\Wallet\Carteira;

/**
 * Allow application to serve system resources
 */
class CaixaPageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCAIXAS]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $caixa = new Caixa($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Caixa::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genBasic();
        $caixas = Caixa::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($caixas as $_caixa) {
                $items[] = $_caixa->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $estados = [
            'Y' => 'Ativos',
            'N' => 'Inativos',
        ];

        return $this->view('gerenciar_caixa_index', get_defined_vars());
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROCAIXAS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $caixa = Caixa::findByID($id);
        $caixa->setID(null);

        $focusctrl = 'descricao';
        $errors = [];
        $old_caixa = $caixa;
        if ($this->getRequest()->isMethod('POST')) {
            $caixa = new Caixa($this->getData());
            try {
                $caixa->filter($old_caixa, true);
                $caixa->insert();
                $old_caixa->clean($caixa);
                $msg = sprintf(
                    'Caixa "%s" cadastrado com sucesso!',
                    $caixa->getDescricao()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $caixa->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/caixa/');
            } catch (\Exception $e) {
                $caixa->clean($old_caixa);
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
            $caixa->setAtivo('Y');
        }
        $_carteiras = Carteira::findAll();
        return $this->view('gerenciar_caixa_cadastrar', get_defined_vars());
    }

    public function update()
    {
        $this->needPermission([Permissao::NOME_CADASTROCAIXAS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $caixa = Caixa::findByID($id);
        if (!$caixa->exists()) {
            $msg = 'O caixa informado não existe!';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/caixa/');
        }
        $focusctrl = 'descricao';
        $errors = [];
        $old_caixa = $caixa;
        if ($this->getRequest()->isMethod('POST')) {
            $caixa = new Caixa($this->getData());
            try {
                $caixa->setID($old_caixa->getID());
                if (!app()->getSystem()->isFiscalVisible()) {
                    $caixa->setNumeroInicial($old_caixa->getNumeroInicial());
                    $caixa->setSerie($old_caixa->getSerie());
                }
                $caixa->filter($old_caixa, true);
                $caixa->update();
                $old_caixa->clean($caixa);
                $msg = sprintf(
                    'Caixa "%s" atualizado com sucesso!',
                    $caixa->getDescricao()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $caixa->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/caixa/');
            } catch (\Exception $e) {
                $caixa->clean($old_caixa);
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
        $_carteiras = Carteira::findAll();
        return $this->view('gerenciar_caixa_editar', get_defined_vars());
    }

    public function delete()
    {
        $this->needPermission([Permissao::NOME_CADASTROCAIXAS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $caixa = Caixa::findByID($id);
        if (!$caixa->exists()) {
            $msg = 'O caixa não foi informado ou não existe!';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/caixa/');
        }
        try {
            $caixa->delete();
            $caixa->clean(new Caixa());
            $msg = sprintf('Caixa "%s" excluído com sucesso!', $caixa->getDescricao());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o caixa "%s"!',
                $caixa->getDescricao()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/caixa/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'caixa_find',
                'path' => '/gerenciar/caixa/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'caixa_add',
                'path' => '/gerenciar/caixa/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'caixa_update',
                'path' => '/gerenciar/caixa/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'caixa_delete',
                'path' => '/gerenciar/caixa/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
