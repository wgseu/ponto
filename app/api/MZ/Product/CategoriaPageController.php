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
namespace MZ\Product;

use MZ\System\Permissao;
use MZ\Database\DB;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class CategoriaPageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        if (isset($condition['categoriaid']) && intval($condition['categoriaid']) < 0) {
            unset($condition['categoriaid']);
        } elseif (array_key_exists('categoriaid', $_GET)) {
            $condition['categoriaid'] = isset($condition['categoriaid']) ? $condition['categoriaid'] : null;
        }
        $categoria = new Categoria($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Categoria::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $categorias = Categoria::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($categorias as $_categoria) {
                $items[] = $_categoria->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $_sup_categorias = Categoria::findAll(['categoriaid' => null]);
        $sup_categorias = [];
        foreach ($_sup_categorias as $_categoria) {
            $sup_categorias[$_categoria->getID()] = $_categoria->getDescricao();
        }

        return $this->view('gerenciar_categoria_index', get_defined_vars());
    }

    public function add()
    {
        need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $categoria = Categoria::findByID($id);
        $categoria->setID(null);
        $categoria->setImagem(null);

        $focusctrl = 'descricao';
        $errors = [];
        $old_categoria = $categoria;
        if (is_post()) {
            $categoria = new Categoria($_POST);
            try {
                $categoria->filter($old_categoria, true);
                $categoria->insert();
                $old_categoria->clean($categoria);
                $msg = sprintf(
                    'Categoria "%s" cadastrada com sucesso!',
                    $categoria->getDescricao()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $categoria->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/categoria/');
            } catch (\Exception $e) {
                $categoria->clean($old_categoria);
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
        } elseif (is_null($categoria->getDescricao())) {
            $categoria->setServico('Y');
        }
        $_categorias = Categoria::findAll(['categoriaid' => null]);
        return $this->view('gerenciar_categoria_cadastrar', get_defined_vars());
    }

    public function update()
    {
        need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $categoria = Categoria::findByID($id);
        if (!$categoria->exists()) {
            $msg = 'A categoria informada não existe!';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/categoria/');
        }
        $focusctrl = 'descricao';
        $errors = [];
        $old_categoria = $categoria;
        if (is_post()) {
            $categoria = new Categoria($_POST);
            try {
                $categoria->filter($old_categoria, true);
                $categoria->update();
                $old_categoria->clean($categoria);
                $msg = sprintf(
                    'Categoria "%s" atualizada com sucesso!',
                    $categoria->getDescricao()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $categoria->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/categoria/');
            } catch (\Exception $e) {
                $categoria->clean($old_categoria);
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
        }
        $_categorias = Categoria::findAll(['categoriaid' => null]);
        return $this->view('gerenciar_categoria_editar', get_defined_vars());
    }

    public function delete()
    {
        need_permission(Permissao::NOME_CADASTROPRODUTOS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $categoria = Categoria::findByID($id);
        if (!$categoria->exists()) {
            $msg = 'A categoria não foi informada ou não existe!';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/categoria/');
        }
        try {
            $categoria->delete();
            $categoria->clean(new Categoria());
            $msg = sprintf('Categoria "%s" excluída com sucesso!', $categoria->getDescricao());
            if (is_output('json')) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a categoria "%s"!',
                $categoria->getDescricao()
            );
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/categoria/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'categoria_find',
                'path' => '/gerenciar/categoria/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'categoria_add',
                'path' => '/gerenciar/categoria/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'categoria_update',
                'path' => '/gerenciar/categoria/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'categoria_delete',
                'path' => '/gerenciar/categoria/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
