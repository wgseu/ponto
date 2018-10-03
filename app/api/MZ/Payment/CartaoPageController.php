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
use MZ\Core\PageController;
use MZ\Integrator\IFood;
use MZ\Integrator\Kromax;

/**
 * Allow application to serve system resources
 */
class CartaoPageController extends PageController
{
    public function associate($name)
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTOES]);
        // TODO permitir cadastrar novo cartão na página de associação
        if ($name == 'ifood') {
            $codigos = IFood::CARDS;
        } else {
            $codigos = Kromax::CARDS;
        }
        $integracao = Integracao::findByAcessoURL($name);
        $association = new \MZ\Association\Card($integracao, $codigos);

        if (is_post() && $this->getRequest()->query->get('action') == 'update') {
            $this->needPermission([Permissao::NOME_CADASTROCARTOES]);
            try {
                $codigo = $this->getRequest()->request->get('codigo');
                $id = $this->getRequest()->request->get('id');
                $cartao = $association->update($codigo, $id);
                return $this->json()->success(['cartao' => $cartao->toArray()]);
            } catch (\Exception $e) {
                return $this->json()->error($e->getMessage());
            }
        }
        $codigos = $association->findAll();
        $_imagens = Cartao::getImages();
        return $this->view('gerenciar_cartao_associar', get_defined_vars());
    }

    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTOES]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $cartao = new Cartao($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Cartao::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genBasic();
        $cartoes = Cartao::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($cartoes as $_cartao) {
                $items[] = $_cartao->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $estados = [
            'Y' => 'Ativos',
            'N' => 'Inativos',
        ];
        return $this->view('gerenciar_cartao_index', get_defined_vars());
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTOES]);
        $id = $this->getRequest()->query->getInt('id', null);
        $cartao = Cartao::findByID($id);
        $cartao->setID(null);

        $focusctrl = 'descricao';
        $errors = [];
        $old_cartao = $cartao;
        if (is_post()) {
            $cartao = new Cartao($this->getData());
            try {
                $cartao->filter($old_cartao, true);
                $cartao->insert();
                $old_cartao->clean($cartao);
                $msg = sprintf(
                    'Cartão "%s" cadastrado com sucesso!',
                    $cartao->getDescricao()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $cartao->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/cartao/');
            } catch (\Exception $e) {
                $cartao->clean($old_cartao);
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
        } elseif (is_null($cartao->getDescricao())) {
            $cartao->setAtivo('Y');
        }
        $_carteiras = Carteira::findAll();
        $_imagens = Cartao::getImages();
        return $this->view('gerenciar_cartao_cadastrar', get_defined_vars());
    }

    public function update()
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTOES]);
        $id = $this->getRequest()->query->getInt('id', null);
        $cartao = Cartao::findByID($id);
        if (!$cartao->exists()) {
            $msg = 'O cartão informado não existe!';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cartao/');
        }
        $focusctrl = 'descricao';
        $errors = [];
        $old_cartao = $cartao;
        if (is_post()) {
            $cartao = new Cartao($this->getData());
            try {
                $cartao->filter($old_cartao, true);
                $cartao->update();
                $old_cartao->clean($cartao);
                $msg = sprintf(
                    'Cartão "%s" atualizado com sucesso!',
                    $cartao->getDescricao()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $cartao->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/cartao/');
            } catch (\Exception $e) {
                $cartao->clean($old_cartao);
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
        $_imagens = Cartao::getImages();
        return $this->view('gerenciar_cartao_editar', get_defined_vars());
    }

    public function delete()
    {
        $this->needPermission([Permissao::NOME_CADASTROCARTOES]);
        $id = $this->getRequest()->query->getInt('id', null);
        $cartao = Cartao::findByID($id);
        if (!$cartao->exists()) {
            $msg = 'O cartão não foi informado ou não existe!';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cartao/');
        }
        try {
            $cartao->delete();
            $cartao->clean(new Cartao());
            $msg = sprintf('Cartão "%s" excluído com sucesso!', $cartao->getDescricao());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o cartão "%s"!',
                $cartao->getDescricao()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/cartao/');
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
