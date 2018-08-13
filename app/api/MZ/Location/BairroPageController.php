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
class BairroPageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_permission(Permissao::NOME_CADASTROBAIRROS, is_output('json'));

        $limite = isset($_GET['limite'])?intval($_GET['limite']):10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $order = Filter::order(isset($_GET['ordem'])?$_GET['ordem']:'');
        $count = Bairro::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $bairros = Bairro::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($bairros as $bairro) {
                $items[] = $bairro->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $pais = \MZ\Location\Pais::findByID(isset($_GET['paisid']) ? $_GET['paisid'] : null);
        $estado = \MZ\Location\Estado::findByID(isset($_GET['estadoid']) ? $_GET['estadoid'] : null);
        $cidade = \MZ\Location\Cidade::findByID(isset($_GET['cidadeid']) ? $_GET['cidadeid'] : null);

        $_paises = \MZ\Location\Pais::findAll();
        $_estados = \MZ\Location\Estado::findAll(['paisid' => $pais->getID()]);

        return $this->view('gerenciar_bairro_index', get_defined_vars());
    }

    public function add()
    {
        need_permission(Permissao::NOME_CADASTROBAIRROS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $bairro = Bairro::findByID($id);
        $bairro->setID(null);

        $focusctrl = 'nome';
        $errors = [];
        $old_bairro = $bairro;
        if (is_post()) {
            $bairro = new Bairro($_POST);
            try {
                $bairro->filter($old_bairro);
                $bairro->save();
                $old_bairro->clean($bairro);
                $msg = sprintf(
                    'Bairro "%s" cadastrado com sucesso!',
                    $bairro->getNome()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $bairro->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/bairro/');
            } catch (\Exception $e) {
                $bairro->clean($old_bairro);
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
        } elseif (is_null($bairro->getNome())) {
            $bairro->setDisponivel('Y');
        }
        if (is_null($bairro->getCidadeID())) {
            $bairro->setCidadeID($this->getApplication()->getSystem()->getCity()->getID());
        }
        $cidade = $bairro->findCidadeID();
        $estado = $cidade->findEstadoID();
        $_paises = \MZ\Location\Pais::findAll();
        if ($estado->exists()) {
            $pais = $estado->findPaisID();
        } elseif (count($_paises) > 0) {
            $pais = current($_paises);
        } else {
            $pais = new \MZ\Location\Pais();
        }
        $_estados = \MZ\Location\Estado::findAll(['paisid' => $pais->getID()]);

        return $this->view('gerenciar_bairro_cadastrar', get_defined_vars());
    }

    public function update()
    {
        need_permission(Permissao::NOME_CADASTROBAIRROS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $bairro = Bairro::findByID($id);
        if (!$bairro->exists()) {
            $msg = 'Não existe Bairro com o ID informado!';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/bairro/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_bairro = $bairro;
        if (is_post()) {
            $bairro = new Bairro($_POST);
            try {
                $bairro->filter($old_bairro);
                $bairro->save();
                $old_bairro->clean($bairro);
                $msg = sprintf(
                    'Bairro "%s" atualizado com sucesso!',
                    $bairro->getNome()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $bairro->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/bairro/');
            } catch (\Exception $e) {
                $bairro->clean($old_bairro);
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
        $cidade = $bairro->findCidadeID();
        $estado = $cidade->findEstadoID();
        $_paises = \MZ\Location\Pais::findAll();
        if ($estado->exists()) {
            $pais = $estado->findPaisID();
        } elseif (count($_paises) > 0) {
            $pais = current($_paises);
        } else {
            $pais = new \MZ\Location\Pais();
        }
        $_estados = \MZ\Location\Estado::findAll(['paisid' => $pais->getID()]);
        return $this->view('gerenciar_bairro_editar', get_defined_vars());
    }

    public function delete()
    {
        need_permission(Permissao::NOME_CADASTROBAIRROS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $bairro = Bairro::findByID($id);
        if (!$bairro->exists()) {
            $msg = 'Não existe Bairro com o ID informado!';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/bairro/');
        }
        try {
            $bairro->delete();
            $bairro->clean(new Bairro());
            $msg = sprintf('Bairro "%s" excluído com sucesso!', $bairro->getNome());
            if (is_output('json')) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o Bairro "%s"!',
                $bairro->getNome()
            );
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/bairro/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'bairro_find',
                'path' => '/gerenciar/bairro/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'bairro_add',
                'path' => '/gerenciar/bairro/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'bairro_update',
                'path' => '/gerenciar/bairro/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'bairro_delete',
                'path' => '/gerenciar/bairro/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
