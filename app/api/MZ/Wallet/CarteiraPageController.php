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
class CarteiraPageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTEIRAS]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $carteira = new Carteira($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Carteira::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genPages();
        $carteiras = Carteira::findAll($condition, $order, $limite, $pager->offset);
        $total_disponivel = Carteira::sumAvailable($condition);
        $total_a_receber = Carteira::sumToReceive($condition);

        if ($this->isJson()) {
            $items = [];
            foreach ($carteiras as $_carteira) {
                $items[] = $_carteira->publish();
            }
            return $this->json()->success(['items' => $items]);
        }
        $_banco = $carteira->findBancoID();
        $tipos = Carteira::getTipoOptions();

        return $this->view('gerenciar_carteira_index', get_defined_vars());
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTEIRAS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $carteira = Carteira::findByID($id);
        $carteira->setID(null);

        $focusctrl = 'descricao';
        $errors = [];
        $carteira = new Carteira();
        $old_carteira = $carteira;
        if ($this->getRequest()->isMethod('POST')) {
            $carteira = new Carteira($this->getData());
            try {
                $carteira->filter($old_carteira, true);
                $carteira->insert();
                $old_carteira->clean($carteira);
                $msg = sprintf(
                    'Carteira "%s" cadastrada com sucesso!',
                    $carteira->getDescricao()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $carteira->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/carteira/');
            } catch (\Exception $e) {
                $carteira->clean($old_carteira);
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
            $carteira->setAtiva('Y');
        }
        $banco_id_obj = $carteira->findBancoID();
        $tipo_options = Carteira::getTipoOptions();
        $ambiente_options = Carteira::getAmbienteOptions();
        $_carteiras = Carteira::findAll(['carteiraid' => null]);
        return $this->view('gerenciar_carteira_cadastrar', get_defined_vars());
    }

    public function update()
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTEIRAS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $carteira = Carteira::findByID($id);
        if (!$carteira->exists()) {
            $msg = 'A carteira informada não existe!';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/carteira/');
        }
        $focusctrl = 'descricao';
        $errors = [];
        $old_carteira = $carteira;
        if ($this->getRequest()->isMethod('POST')) {
            $carteira = new Carteira($this->getData());
            try {
                $carteira->filter($old_carteira, true);
                $carteira->update();
                $old_carteira->clean($carteira);
                $msg = sprintf(
                    'Carteira "%s" atualizada com sucesso!',
                    $carteira->getDescricao()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $carteira->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/carteira/');
            } catch (\Exception $e) {
                $carteira->clean($old_carteira);
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
        $banco_id_obj = $carteira->findBancoID();
        $tipo_options = Carteira::getTipoOptions();
        $ambiente_options = Carteira::getAmbienteOptions();
        $_carteiras = Carteira::findAll(['carteiraid' => null]);
        return $this->view('gerenciar_carteira_editar', get_defined_vars());
    }

    public function delete()
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTEIRAS]);
        $id = $this->getRequest()->query->getInt('id', null);
        $carteira = Carteira::findByID($id);
        if (!$carteira->exists()) {
            $msg = 'A carteira não foi informada ou não existe!';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/carteira/');
        }
        try {
            $carteira->delete();
            $carteira->clean(new Carteira());
            $msg = sprintf('Carteira "%s" excluída com sucesso!', $carteira->getDescricao());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a carteira "%s"!',
                $carteira->getDescricao()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/carteira/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'carteira_find',
                'path' => '/gerenciar/carteira/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'carteira_add',
                'path' => '/gerenciar/carteira/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'carteira_update',
                'path' => '/gerenciar/carteira/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'carteira_delete',
                'path' => '/gerenciar/carteira/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
