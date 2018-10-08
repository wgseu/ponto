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
namespace MZ\Account;

use MZ\System\Permissao;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class CreditoPageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTRARCREDITOS]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $credito = new Credito($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Credito::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genPages();
        $creditos = Credito::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($creditos as $_credito) {
                $items[] = $_credito->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $_cliente = $credito->findClienteID();
        $estados = [
            'Y' => 'Cancelados',
            'N' => 'Válidos',
        ];
        return $this->view('gerenciar_credito_index', get_defined_vars());
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTRARCREDITOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $credito = Credito::findByID($id);
        $credito->setID(null);
        $credito->setDataCadastro(DB::now());

        $focusctrl = 'detalhes';
        $errors = [];
        $old_credito = $credito;
        if ($this->getRequest()->isMethod('POST')) {
            $credito = new Credito($this->getData());
            try {
                $old_credito->setClienteID($credito->getClienteID());
                $credito->filter($old_credito, true);
                $credito->insert();
                $old_credito->clean($credito);
                $msg = sprintf(
                    'Crédito "%s" cadastrado com sucesso!',
                    $credito->getDetalhes()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $credito->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/credito/');
            } catch (\Exception $e) {
                $credito->clean($old_credito);
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
        return $this->view('gerenciar_credito_cadastrar', get_defined_vars());
    }

    public function update()
    {
        $this->needPermission([Permissao::NOME_CADASTRARCREDITOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $credito = Credito::findByID($id);
        if (!$credito->exists()) {
            $msg = 'O crédito não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/credito/');
        }
        $focusctrl = 'detalhes';
        $errors = [];
        $old_credito = $credito;
        if ($this->getRequest()->isMethod('POST')) {
            $credito = new Credito($this->getData());
            try {
                $credito->filter($old_credito, true);
                $credito->update();
                $old_credito->clean($credito);
                $msg = sprintf(
                    'Crédito "%s" atualizado com sucesso!',
                    $credito->getDetalhes()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $credito->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/credito/');
            } catch (\Exception $e) {
                $credito->clean($old_credito);
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
        return $this->view('gerenciar_credito_editar', get_defined_vars());
    }

    public function delete()
    {
        $this->needPermission([Permissao::NOME_CADASTRARCREDITOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $credito = Credito::findByID($id);
        if (!$credito->exists()) {
            $msg = 'O crédito não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/credito/');
        }
        try {
            $credito->delete();
            $credito->clean(new Credito());
            $msg = sprintf('Crédito "%s" excluído com sucesso!', $credito->getDetalhes());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o crédito "%s"',
                $credito->getDetalhes()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/credito/');
    }

    public function cancel()
    {
        $this->needPermission([Permissao::NOME_CADASTRARCREDITOS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $credito = Credito::findByID($id);
        if (!$credito->exists()) {
            $msg = 'O crédito não foi informado ou não existe';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/credito/');
        }
        try {
            $credito->cancel();
            $msg = sprintf('Crédito "%s" cancelado com sucesso!', $credito->getDetalhes());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível cancelar o crédito "%s"',
                $credito->getDetalhes()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/credito/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'credito_find',
                'path' => '/gerenciar/credito/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'credito_cancel',
                'path' => '/gerenciar/credito/cancelar',
                'method' => 'GET',
                'controller' => 'cancel',
            ],
        ];
    }
}
