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
class MoedaPageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_permission(Permissao::NOME_CADASTROMOEDAS, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $moeda = new Moeda($condition);
        $order = Filter::order(isset($_GET['ordem'])?$_GET['ordem']:'');
        $count = Moeda::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $moedas = Moeda::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($moedas as $_moeda) {
                $items[] = $_moeda->publish();
            }
            json(['status' => 'ok', 'items' => $items]);
        }

        return $this->view('gerenciar_moeda_index', get_defined_vars());
    }

    public function add()
    {
        need_permission(Permissao::NOME_CADASTROMOEDAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $moeda = Moeda::findByID($id);
        $moeda->setID(null);

        $focusctrl = 'nome';
        $errors = [];
        $old_moeda = $moeda;
        if (is_post()) {
            $moeda = new Moeda($_POST);
            try {
                $moeda->filter($old_moeda);
                $moeda->save();
                $old_moeda->clean($moeda);
                $msg = sprintf(
                    'Moeda "%s" atualizada com sucesso!',
                    $moeda->getNome()
                );
                if (is_output('json')) {
                    json(null, ['item' => $moeda->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/moeda/');
            } catch (\Exception $e) {
                $moeda->clean($old_moeda);
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
        } elseif (is_null($moeda->getNome())) {
            $moeda->setDivisao(100);
            $moeda->setFracao('Centavo');
            $moeda->setFormato('$ %s');
        }
        return $this->view('gerenciar_moeda_cadastrar', get_defined_vars());
    }

    public function update()
    {
        need_permission(Permissao::NOME_CADASTROMOEDAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $moeda = Moeda::findByID($id);
        if (!$moeda->exists()) {
            $msg = 'A moeda não foi informada ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/moeda/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_moeda = $moeda;
        if (is_post()) {
            $moeda = new Moeda($_POST);
            try {
                $moeda->filter($old_moeda);
                $moeda->save();
                $old_moeda->clean($moeda);
                $msg = sprintf(
                    'Moeda "%s" atualizada com sucesso!',
                    $moeda->getNome()
                );
                if (is_output('json')) {
                    json(null, ['item' => $moeda->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/moeda/');
            } catch (\Exception $e) {
                $moeda->clean($old_moeda);
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
        return $this->view('gerenciar_moeda_editar', get_defined_vars());
    }

    public function delete()
    {
        need_permission(Permissao::NOME_CADASTROMOEDAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $moeda = Moeda::findByID($id);
        if (!$moeda->exists()) {
            $msg = 'A moeda não foi informada ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/moeda/');
        }
        try {
            $moeda->delete();
            $moeda->clean(new Moeda());
            $msg = sprintf('Moeda "%s" excluída com sucesso!', $moeda->getNome());
            if (is_output('json')) {
                json('msg', $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a moeda "%s"',
                $moeda->getNome()
            );
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::error($msg);
        }
        redirect('/gerenciar/moeda/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'moeda_find',
                'path' => '/gerenciar/moeda/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'moeda_add',
                'path' => '/gerenciar/moeda/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'moeda_update',
                'path' => '/gerenciar/moeda/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'moeda_delete',
                'path' => '/gerenciar/moeda/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
