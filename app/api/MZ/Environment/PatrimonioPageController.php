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
namespace MZ\Environment;

use MZ\System\Permissao;
use MZ\Database\DB;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class PatrimonioPageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_permission(Permissao::NOME_CADASTROPATRIMONIO, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $patrimonio = new Patrimonio($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Patrimonio::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $patrimonios = Patrimonio::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($patrimonios as $_patrimonio) {
                $items[] = $_patrimonio->publish();
            }
            json(['status' => 'ok', 'items' => $items]);
        }

        $_empresa =  $patrimonio->findEmpresaID();
        $_fornecedor = $patrimonio->findFornecedorID();
        $_estado_names = Patrimonio::getEstadoOptions();
        return $this->view('gerenciar_patrimonio_index', get_defined_vars());
    }

    public function add()
    {
        need_permission(Permissao::NOME_CADASTROPATRIMONIO, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $patrimonio = Patrimonio::findByID($id);
        $patrimonio->setID(null);
        $patrimonio->setImagemAnexada(null);

        $focusctrl = 'descricao';
        $errors = [];
        $old_patrimonio = $patrimonio;
        if (is_post()) {
            $patrimonio = new Patrimonio($_POST);
            try {
                $patrimonio->filter($old_patrimonio);
                $patrimonio->insert();
                $old_patrimonio->clean($patrimonio);
                $msg = sprintf(
                    'Patrimônio "%s" cadastrado com sucesso!',
                    $patrimonio->getDescricao()
                );
                if (is_output('json')) {
                    json(null, ['item' => $patrimonio->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/patrimonio/');
            } catch (\Exception $e) {
                $patrimonio->clean($old_patrimonio);
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
        } elseif (is_null($patrimonio->getDescricao())) {
            $patrimonio->setCusto(0.0);
            $patrimonio->setAltura(0.0);
            $patrimonio->setLargura(0.0);
            $patrimonio->setComprimento(0.0);
            $patrimonio->setValor(0.0);
            $patrimonio->setAtivo('Y');
        }
        return $this->view('gerenciar_patrimonio_cadastrar', get_defined_vars());
    }

    public function update()
    {
        need_permission(Permissao::NOME_CADASTROPATRIMONIO, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $patrimonio = Patrimonio::findByID($id);
        if (!$patrimonio->exists()) {
            $msg = 'O patrimônio não foi informado ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/patrimonio/');
        }
        $focusctrl = 'descricao';
        $errors = [];
        $old_patrimonio = $patrimonio;
        if (is_post()) {
            $patrimonio = new Patrimonio($_POST);
            try {
                $patrimonio->filter($old_patrimonio);
                $patrimonio->update();
                $old_patrimonio->clean($patrimonio);
                $msg = sprintf(
                    'Patrimônio "%s" atualizado com sucesso!',
                    $patrimonio->getDescricao()
                );
                if (is_output('json')) {
                    json(null, ['item' => $patrimonio->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/patrimonio/');
            } catch (\Exception $e) {
                $patrimonio->clean($old_patrimonio);
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
        return $this->view('gerenciar_patrimonio_editar', get_defined_vars());
    }

    public function delete()
    {
        need_permission(Permissao::NOME_CADASTROPATRIMONIO, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $patrimonio = Patrimonio::findByID($id);
        if (!$patrimonio->exists()) {
            $msg = 'O patrimônio não foi informado ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/patrimonio/');
        }
        try {
            $patrimonio->delete();
            $patrimonio->clean(new Patrimonio());
            $msg = sprintf('Patrimônio "%s" excluído com sucesso!', $patrimonio->getDescricao());
            if (is_output('json')) {
                json('msg', $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o patrimônio "%s"',
                $patrimonio->getDescricao()
            );
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::error($msg);
        }
        redirect('/gerenciar/patrimonio/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'patrimonio_find',
                'path' => '/gerenciar/patrimonio/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'patrimonio_add',
                'path' => '/gerenciar/patrimonio/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'patrimonio_update',
                'path' => '/gerenciar/patrimonio/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'patrimonio_delete',
                'path' => '/gerenciar/patrimonio/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
