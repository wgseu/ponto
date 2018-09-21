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

/**
 * Allow application to serve system resources
 */
class CarteiraPageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_permission(Permissao::NOME_CADASTROCARTEIRAS, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $carteira = new Carteira($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Carteira::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $carteiras = Carteira::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
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
        need_permission(Permissao::NOME_CADASTROCARTEIRAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $carteira = Carteira::findByID($id);
        $carteira->setID(null);

        $focusctrl = 'descricao';
        $errors = [];
        $carteira = new Carteira();
        $old_carteira = $carteira;
        if (is_post()) {
            $carteira = new Carteira($_POST);
            try {
                $carteira->filter($old_carteira, true);
                $carteira->insert();
                $old_carteira->clean($carteira);
                $msg = sprintf(
                    'Carteira "%s" cadastrada com sucesso!',
                    $carteira->getDescricao()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $carteira->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/carteira/');
            } catch (\Exception $e) {
                $carteira->clean($old_carteira);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if (is_output('json')) {
                    return $this->json()->error($e->getMessage(), null, $errors);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
            }
        } elseif (is_output('json')) {
            return $this->json()->error('Nenhum dado foi enviado');
        } else {
            $carteira->setAtiva('Y');
        }
        $_banco = $carteira->findBancoID();
        return $this->view('gerenciar_carteira_cadastrar', get_defined_vars());
    }

    public function update()
    {
        need_permission(Permissao::NOME_CADASTROCARTEIRAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $carteira = Carteira::findByID($id);
        if (!$carteira->exists()) {
            $msg = 'A carteira informada não existe!';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/carteira/');
        }
        $focusctrl = 'descricao';
        $errors = [];
        $old_carteira = $carteira;
        if (is_post()) {
            $carteira = new Carteira($_POST);
            try {
                $carteira->filter($old_carteira, true);
                $carteira->update();
                $old_carteira->clean($carteira);
                $msg = sprintf(
                    'Carteira "%s" atualizada com sucesso!',
                    $carteira->getDescricao()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $carteira->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/carteira/');
            } catch (\Exception $e) {
                $carteira->clean($old_carteira);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if (is_output('json')) {
                    return $this->json()->error($e->getMessage(), null, $errors);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
            }
        } elseif (is_output('json')) {
            return $this->json()->error('Nenhum dado foi enviado');
        }
        $_banco = $carteira->findBancoID();
        return $this->view('gerenciar_carteira_editar', get_defined_vars());
    }

    public function delete()
    {
        need_permission(Permissao::NOME_CADASTROCARTEIRAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $carteira = Carteira::findByID($id);
        if (!$carteira->exists()) {
            $msg = 'A carteira não foi informada ou não existe!';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/carteira/');
        }
        try {
            $carteira->delete();
            $carteira->clean(new Carteira());
            $msg = sprintf('Carteira "%s" excluída com sucesso!', $carteira->getDescricao());
            if (is_output('json')) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a carteira "%s"!',
                $carteira->getDescricao()
            );
            if (is_output('json')) {
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
