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
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class CidadePageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCIDADES]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Cidade::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genBasic();
        $cidades = Cidade::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($cidades as $cidade) {
                $items[] = $cidade->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $pais = \MZ\Location\Pais::findByID($this->getRequest()->query->get('paisid'));
        $estado = \MZ\Location\Estado::findByID($this->getRequest()->query->get('estadoid'));
        $_paises = \MZ\Location\Pais::findAll();
        $_estados = \MZ\Location\Estado::findAll(['paisid' => $pais->getID()]);

        return $this->view('gerenciar_cidade_index', get_defined_vars());
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROCIDADES]);
        $id = $this->getRequest()->query->getInt('id', null);
        $cidade = Cidade::findByID($id);
        $cidade->setID(null);

        $focusctrl = 'nome';
        $errors = [];
        $old_cidade = $cidade;
        if ($this->getRequest()->isMethod('POST')) {
            $cidade = new Cidade($this->getData());
            try {
                $cidade->filter($old_cidade, true);
                $cidade->save();
                $old_cidade->clean($cidade);
                $msg = sprintf(
                    'Cidade "%s" atualizada com sucesso!',
                    $cidade->getNome()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $cidade->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/cidade/');
            } catch (\Exception $e) {
                $cidade->clean($old_cidade);
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
        if (is_null($cidade->getEstadoID())) {
            $cidade->setEstadoID(app()->getSystem()->getState()->getID());
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
        $this->needPermission([Permissao::NOME_CADASTROCIDADES]);
        $id = $this->getRequest()->query->getInt('id', null);
        $cidade = Cidade::findByID($id);
        if (!$cidade->exists()) {
            $msg = 'A cidade não foi informada ou não existe!';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cidade/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_cidade = $cidade;
        if ($this->getRequest()->isMethod('POST')) {
            $cidade = new Cidade($this->getData());
            try {
                $cidade->filter($old_cidade, true);
                $cidade->save();
                $old_cidade->clean($cidade);
                $msg = sprintf(
                    'Cidade "%s" atualizada com sucesso!',
                    $cidade->getNome()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $cidade->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/cidade/');
            } catch (\Exception $e) {
                $cidade->clean($old_cidade);
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
        $this->needPermission([Permissao::NOME_CADASTROCIDADES]);
        $id = $this->getRequest()->query->getInt('id', null);
        $cidade = Cidade::findByID($id);
        if (!$cidade->exists()) {
            $msg = 'A cidade não foi informada ou não existe!';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cidade/');
        }
        try {
            $cidade->delete();
            $cidade->clean(new Cidade());
            $msg = sprintf('Cidade "%s" excluída com sucesso!', $cidade->getNome());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a cidade "%s"!',
                $cidade->getNome()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/cidade/');
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
