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
use MZ\System\Integracao;
use MZ\Util\Filter;
use MZ\Integrator\IFood;
use MZ\Integrator\Kromax;

/**
 * Allow application to serve system resources
 */
class CartaoPageController extends \MZ\Core\Controller
{
    public function associate($name)
    {
        need_permission(Permissao::NOME_CADASTROCARTOES, is_output('json'));
        // TODO permitir cadastrar novo cartão na página de associação
        if ($name == 'ifood') {
            $codigos = IFood::CARDS;
        } else {
            $codigos = Kromax::CARDS;
        }
        $integracao = Integracao::findByAcessoURL($name);
        $association = new \MZ\Association\Card($integracao, $codigos);

        if (isset($_GET['action']) && is_post() && $_GET['action'] == 'update') {
            need_permission(Permissao::NOME_CADASTROCARTOES, true);
            try {
                $codigo = isset($_POST['codigo'])?$_POST['codigo']:null;
                $id = array_key_exists('id', $_POST)?$_POST['id']:null;
                $cartao = $association->update($codigo, $id);
                json(null, ['cartao' => $cartao->toArray()]);
            } catch (\Exception $e) {
                json($e->getMessage());
            }
        }
        $codigos = $association->findAll();
        $_imagens = Cartao::getImages();
        return $this->view('gerenciar_cartao_associar', get_defined_vars());
    }

    public function find()
    {
        need_permission(Permissao::NOME_CADASTROCARTOES, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $cartao = new Cartao($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Cartao::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $cartoes = Cartao::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($cartoes as $_cartao) {
                $items[] = $_cartao->publish();
            }
            json(['status' => 'ok', 'items' => $items]);
        }

        $estados = [
            'Y' => 'Ativos',
            'N' => 'Inativos',
        ];
        $_imagens = [0 => ['id' => 0, 'name' => 'Sem imagem']] + Cartao::getImages();
        return $this->view('gerenciar_cartao_index', get_defined_vars());
    }

    public function add()
    {
        need_permission(Permissao::NOME_CADASTROCARTOES, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $cartao = Cartao::findByID($id);
        $cartao->setID(null);

        $focusctrl = 'descricao';
        $errors = [];
        $old_cartao = $cartao;
        if (is_post()) {
            $cartao = new Cartao($_POST);
            try {
                $cartao->filter($old_cartao);
                $cartao->insert();
                $old_cartao->clean($cartao);
                $msg = sprintf(
                    'Cartão "%s" cadastrado com sucesso!',
                    $cartao->getDescricao()
                );
                if (is_output('json')) {
                    json(null, ['item' => $cartao->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/cartao/');
            } catch (\Exception $e) {
                $cartao->clean($old_cartao);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if (is_output('json')) {
                    json($e->getMessage(), null, ['errors' => $errors]);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
            }
        } elseif (is_output('json')) {
            json('Nenhum dado foi enviado');
        } elseif (is_null($cartao->getDescricao())) {
            $cartao->setAtivo('Y');
        }
        $_carteiras = Carteira::findAll();
        $_imagens = Cartao::getImages();
        return $this->view('gerenciar_cartao_cadastrar', get_defined_vars());
    }

    public function update()
    {
        need_permission(Permissao::NOME_CADASTROCARTOES, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $cartao = Cartao::findByID($id);
        if (!$cartao->exists()) {
            $msg = 'O cartão informado não existe!';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/cartao/');
        }
        $focusctrl = 'descricao';
        $errors = [];
        $old_cartao = $cartao;
        if (is_post()) {
            $cartao = new Cartao($_POST);
            try {
                $cartao->filter($old_cartao);
                $cartao->update();
                $old_cartao->clean($cartao);
                $msg = sprintf(
                    'Cartão "%s" atualizado com sucesso!',
                    $cartao->getDescricao()
                );
                if (is_output('json')) {
                    json(null, ['item' => $cartao->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/cartao/');
            } catch (\Exception $e) {
                $cartao->clean($old_cartao);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if (is_output('json')) {
                    json($e->getMessage(), null, ['errors' => $errors]);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
            }
        } elseif (is_output('json')) {
            json('Nenhum dado foi enviado');
        }
        $_carteiras = Carteira::findAll();
        $_imagens = Cartao::getImages();
        return $this->view('gerenciar_cartao_editar', get_defined_vars());
    }

    public function delete()
    {
        need_permission(Permissao::NOME_CADASTROCARTOES, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $cartao = Cartao::findByID($id);
        if (!$cartao->exists()) {
            $msg = 'O cartão não foi informado ou não existe!';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/cartao/');
        }
        try {
            $cartao->delete();
            $cartao->clean(new Cartao());
            $msg = sprintf('Cartão "%s" excluído com sucesso!', $cartao->getDescricao());
            if (is_output('json')) {
                json('msg', $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o cartão "%s"!',
                $cartao->getDescricao()
            );
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::error($msg);
        }
        redirect('/gerenciar/cartao/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'cartao_find',
                'path' => '/gerenciar/cartao/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'cartao_add',
                'path' => '/gerenciar/cartao/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'cartao_update',
                'path' => '/gerenciar/cartao/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'cartao_delete',
                'path' => '/gerenciar/cartao/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
            [
                'name' => 'cartao_associate',
                'path' => '/gerenciar/cartao/{name}',
                'method' => 'GET',
                'requirements' => ['name' => 'ifood|kromax'],
                'controller' => 'associate',
            ],
        ];
    }
}
