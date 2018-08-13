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
namespace MZ\Sale;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class ComandaPageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_permission(Permissao::NOME_CADASTROCOMANDAS, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $comanda = new Comanda($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Comanda::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $comandas = Comanda::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($comandas as $_comanda) {
                $items[] = $_comanda->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $ativas = [
            'Y' => 'Ativas',
            'N' => 'Inativas',
        ];
        return $this->view('gerenciar_comanda_index', get_defined_vars());
    }

    public function add()
    {
        need_permission(Permissao::NOME_CADASTROCOMANDAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $comanda = Comanda::findByID($id);
        $comanda->setID(null);

        $focusctrl = 'nome';
        $errors = [];
        $old_comanda = $comanda;
        if (is_post()) {
            $comanda = new Comanda($_POST);
            try {
                $comanda->filter($old_comanda);
                $comanda->insert();
                $old_comanda->clean($comanda);
                $msg = sprintf(
                    'Comanda "%s" cadastrada com sucesso!',
                    $comanda->getNome()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $comanda->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/comanda/');
            } catch (\Exception $e) {
                $comanda->clean($old_comanda);
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
            $comanda->setID(Comanda::getNextID());
            $comanda->setNome('Comanda ' . $comanda->getID());
            $comanda->setAtiva('Y');
        }
        return $this->view('gerenciar_comanda_cadastrar', get_defined_vars());
    }

    public function update()
    {
        need_permission(Permissao::NOME_CADASTROCOMANDAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $comanda = Comanda::findByID($id);
        if (!$comanda->exists()) {
            $msg = 'A comanda não foi informada ou não existe';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/comanda/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_comanda = $comanda;
        if (is_post()) {
            $comanda = new Comanda($_POST);
            try {
                $comanda->filter($old_comanda);
                $comanda->update();
                $old_comanda->clean($comanda);
                $msg = sprintf(
                    'Comanda "%s" atualizada com sucesso!',
                    $comanda->getNome()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $comanda->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/comanda/');
            } catch (\Exception $e) {
                $comanda->clean($old_comanda);
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
        return $this->view('gerenciar_comanda_editar', get_defined_vars());
    }

    public function delete()
    {
        need_permission(Permissao::NOME_CADASTROCOMANDAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $comanda = Comanda::findByID($id);
        if (!$comanda->exists()) {
            $msg = 'A comanda não foi informada ou não existe!';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/comanda/');
        }
        try {
            $comanda->delete();
            $comanda->clean(new Comanda());
            $msg = sprintf('Comanda "%s" excluída com sucesso!', $comanda->getNome());
            if (is_output('json')) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a comanda "%s"',
                $comanda->getNome()
            );
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/comanda/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'comanda_find',
                'path' => '/gerenciar/comanda/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'comanda_add',
                'path' => '/gerenciar/comanda/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'comanda_update',
                'path' => '/gerenciar/comanda/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'comanda_delete',
                'path' => '/gerenciar/comanda/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
