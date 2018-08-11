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
namespace MZ\Location;

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class CidadePageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_permission(Permissao::NOME_CADASTROCIDADES, is_output('json'));

        $limite = isset($_GET['limite'])?intval($_GET['limite']):10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $order = Filter::order(isset($_GET['ordem'])?$_GET['ordem']:'');
        $count = Cidade::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $cidades = Cidade::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($cidades as $cidade) {
                $items[] = $cidade->publish();
            }
            json(['status' => 'ok', 'items' => $items]);
        }

        $pais = \MZ\Location\Pais::findByID(isset($_GET['paisid']) ? $_GET['paisid'] : null);
        $estado = \MZ\Location\Estado::findByID(isset($_GET['estadoid']) ? $_GET['estadoid'] : null);
        $_paises = \MZ\Location\Pais::findAll();
        $_estados = \MZ\Location\Estado::findAll(['paisid' => $pais->getID()]);

        return $this->view('gerenciar_cidade_index', get_defined_vars());
    }

    public function add()
    {
        need_permission(Permissao::NOME_CADASTROCIDADES, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $cidade = Cidade::findByID($id);
        $cidade->setID(null);

        $focusctrl = 'nome';
        $errors = [];
        $old_cidade = $cidade;
        if (is_post()) {
            $cidade = new Cidade($_POST);
            try {
                $cidade->filter($old_cidade);
                $cidade->save();
                $old_cidade->clean($cidade);
                $msg = sprintf(
                    'Cidade "%s" atualizada com sucesso!',
                    $cidade->getNome()
                );
                if (is_output('json')) {
                    json(null, ['item' => $cidade->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/cidade/');
            } catch (\Exception $e) {
                $cidade->clean($old_cidade);
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
        if (is_null($cidade->getEstadoID())) {
            $cidade->setEstadoID($this->getApplication()->getSystem()->getState()->getID());
        }
        $_estado = $cidade->findEstadoID();
        $_paises = \MZ\Location\Pais::findAll();
        if ($_estado->exists()) {
            $pais = $_estado->findPaisID();
        } elseif (count($_paises) > 0) {
            $pais = current($_paises);
        } else {
            $pais = new \MZ\Location\Pais();
        }
        $_estados = \MZ\Location\Estado::findAll();
        return $this->view('gerenciar_cidade_cadastrar', get_defined_vars());
    }

    public function update()
    {
        need_permission(Permissao::NOME_CADASTROCIDADES, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $cidade = Cidade::findByID($id);
        if (!$cidade->exists()) {
            $msg = 'A cidade não foi informada ou não existe!';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/cidade/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_cidade = $cidade;
        if (is_post()) {
            $cidade = new Cidade($_POST);
            try {
                $cidade->filter($old_cidade);
                $cidade->save();
                $old_cidade->clean($cidade);
                $msg = sprintf(
                    'Cidade "%s" atualizada com sucesso!',
                    $cidade->getNome()
                );
                if (is_output('json')) {
                    json(null, ['item' => $cidade->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/cidade/');
            } catch (\Exception $e) {
                $cidade->clean($old_cidade);
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

        $_estado = $cidade->findEstadoID();
        $_paises = \MZ\Location\Pais::findAll();
        if ($_estado->exists()) {
            $pais = $_estado->findPaisID();
        } elseif (count($_paises) > 0) {
            $pais = current($_paises);
        } else {
            $pais = new \MZ\Location\Pais();
        }
        $_estados = \MZ\Location\Estado::findAll();
        return $this->view('gerenciar_cidade_editar', get_defined_vars());
    }

    public function delete()
    {
        need_permission(Permissao::NOME_CADASTROCIDADES, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $cidade = Cidade::findByID($id);
        if (!$cidade->exists()) {
            $msg = 'A cidade não foi informada ou não existe!';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/cidade/');
        }
        try {
            $cidade->delete();
            $cidade->clean(new Cidade());
            $msg = sprintf('Cidade "%s" excluída com sucesso!', $cidade->getNome());
            if (is_output('json')) {
                json('msg', $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a cidade "%s"!',
                $cidade->getNome()
            );
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::error($msg);
        }
        redirect('/gerenciar/cidade/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'cidade_find',
                'path' => '/gerenciar/cidade/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'cidade_add',
                'path' => '/gerenciar/cidade/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'cidade_update',
                'path' => '/gerenciar/cidade/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'cidade_delete',
                'path' => '/gerenciar/cidade/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
