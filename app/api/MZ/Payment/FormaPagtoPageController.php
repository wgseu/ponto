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
namespace MZ\Payment;

use MZ\System\Permissao;
use MZ\Wallet\Carteira;
use MZ\Util\Filter;
use MZ\Core\PageController;
use MZ\System\Integracao;

/**
 * Allow application to serve system resources
 */
class FormaPagtoPageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROFORMASPAGTO]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $forma_pagto = new FormaPagto($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = FormaPagto::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genPages();
        $formas_de_pagamento = FormaPagto::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($formas_de_pagamento as $_forma_pagto) {
                $items[] = $_forma_pagto->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $estados = [
            'Y' => 'Ativos',
            'N' => 'Inativos',
        ];

        $tipos = FormaPagto::getTipoOptions();

        return $this->view('gerenciar_forma_pagto_index', get_defined_vars());
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROFORMASPAGTO]);
        $id = $this->getRequest()->query->getInt('id', null);
        $forma_pagto = FormaPagto::findByID($id);
        $forma_pagto->setID(null);

        $focusctrl = 'descricao';
        $errors = [];
        $old_forma_pagto = $forma_pagto;
        if ($this->getRequest()->isMethod('POST')) {
            $forma_pagto = new FormaPagto($this->getData());
            try {
                $forma_pagto->filter($old_forma_pagto, true);
                $forma_pagto->insert();
                $old_forma_pagto->clean($forma_pagto);
                $msg = sprintf(
                    'Forma de pagamento "%s" cadastrada com sucesso!',
                    $forma_pagto->getDescricao()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $forma_pagto->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/forma_pagto/');
            } catch (\Exception $e) {
                $forma_pagto->clean($old_forma_pagto);
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
            $forma_pagto->setAtiva('Y');
        }
        $_carteiras = Carteira::findAll();
        $_integracoes = Integracao::findAll();
        $tipo_options = FormaPagto::getTipoOptions();
        return $this->view('gerenciar_forma_pagto_cadastrar', get_defined_vars());
    }

    public function update()
    {
        $this->needPermission([Permissao::NOME_CADASTROFORMASPAGTO]);
        $id = $this->getRequest()->query->getInt('id', null);
        $forma_pagto = FormaPagto::findByID($id);
        if (!$forma_pagto->exists()) {
            $msg = 'A forma de pagamento não foi informada ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/forma_pagto/');
        }
        $focusctrl = 'descricao';
        $errors = [];
        $old_forma_pagto = $forma_pagto;
        if ($this->getRequest()->isMethod('POST')) {
            $forma_pagto = new FormaPagto($this->getData());
            try {
                $forma_pagto->filter($old_forma_pagto, true);
                $forma_pagto->update();
                $old_forma_pagto->clean($forma_pagto);
                $msg = sprintf(
                    'Forma de pagamento "%s" atualizada com sucesso!',
                    $forma_pagto->getDescricao()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $forma_pagto->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/forma_pagto/');
            } catch (\Exception $e) {
                $forma_pagto->clean($old_forma_pagto);
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
        $_integracoes = Integracao::findAll();
        $tipo_options = FormaPagto::getTipoOptions();
        return $this->view('gerenciar_forma_pagto_editar', get_defined_vars());
    }

    public function delete()
    {
        $this->needPermission([Permissao::NOME_CADASTROFORMASPAGTO]);
        $id = $this->getRequest()->query->getInt('id', null);
        $forma_pagto = FormaPagto::findByID($id);
        if (!$forma_pagto->exists()) {
            $msg = 'A forma de pagamento não foi informada ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/forma_pagto/');
        }
        try {
            $forma_pagto->delete();
            $forma_pagto->clean(new FormaPagto());
            $msg = sprintf('Forma de pagamento "%s" excluída com sucesso!', $forma_pagto->getDescricao());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a forma de pagamento "%s"',
                $forma_pagto->getDescricao()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/forma_pagto/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'forma_pagto_find',
                'path' => '/gerenciar/forma_pagto/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'forma_pagto_add',
                'path' => '/gerenciar/forma_pagto/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'forma_pagto_update',
                'path' => '/gerenciar/forma_pagto/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'forma_pagto_delete',
                'path' => '/gerenciar/forma_pagto/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
