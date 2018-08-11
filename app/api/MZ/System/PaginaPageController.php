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
namespace MZ\System;

use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class PaginaPageController extends \MZ\Core\Controller
{
    public function index()
    {
        return $this->getResponse()->output('index');
    }

    public function about()
    {
        $pagina = Pagina::findByName(Pagina::NOME_SOBRE);
        $response = $this->getResponse();
        $response->setTitle('Sobre');
        $response->getEngine()->pagina = $pagina;
        return $response->output('sobre_index');
    }

    public function privacy()
    {
        $pagina = Pagina::findByName(Pagina::NOME_PRIVACIDADE);
        $response = $this->getResponse();
        $response->setTitle('Privacidade');
        $response->getEngine()->pagina = $pagina;
        return $response->output('sobre_privacidade');
    }

    public function terms()
    {
        $pagina = Pagina::findByName(Pagina::NOME_TERMOS);
        $response = $this->getResponse();
        $response->setTitle('Termos de uso');
        $response->getEngine()->pagina = $pagina;
        return $response->output('sobre_termos');
    }

    public function contact()
    {
        $focusctrl = 'nome';
        if (is_login()) {
            if (is_null(logged_user()->getEmail())) {
                $focusctrl = 'email';
            } else {
                $focusctrl = 'assunto';
            }
        }
        $erro = [];
        if (is_post()) {
            $email = isset($_POST['email']) ? strip_tags(trim($_POST['email'])) : null;
            $nome = isset($_POST['nome']) ? strip_tags(trim($_POST['nome'])) : null;
            if (is_login()) {
                $email = $email ?: logged_user()->getEmail();
                $nome = $nome ?: logged_user()->getNome();
            }
            $assunto = isset($_POST['assunto']) ? strip_tags(trim($_POST['assunto'])) : null;
            $mensagem = isset($_POST['mensagem']) ? strip_tags(trim($_POST['mensagem'])) : null;
            if ($nome == '') {
                $erros['nome'] = 'O nome não pode ser vazio';
            }
            if (!check_email($email)) {
                $erros['email'] = 'O E-mail é inválido';
            }
            if ($assunto == '') {
                $erros['assunto'] = 'O assunto não foi informado';
            }
            if ($mensagem == '') {
                $erros['mensagem'] = 'A mensagem não foi informada';
            }
            try {
                if (!empty($erros)) {
                    throw new \MZ\Exception\ValidationException($erros);
                }
                if (!mail_contato($email, $nome, $assunto, $mensagem)) {
                    throw new \Exception("Não foi possível enviar o E-mail, por favor tente novamente mais tarde");
                }
                $response = $this->getResponse();
                return $response->output('contato_sucesso');
            } catch (\MZ\Exception\ValidationException $e) {
                $erro = $e->getErrors();
            } catch (\Exception $e) {
                $erro['unknow'] = $e->getMessage();
            }
            foreach ($erro as $key => $value) {
                $focusctrl = $key;
                break;
            }
            \Thunder::error($erro[$focusctrl]);
        }
        $response = $this->getResponse();
        $response->setTitle('Contato');
        return $response->output('contato_index');
    }

    public function find()
    {
        need_permission(Permissao::NOME_ALTERARPAGINAS, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $pagina = new Pagina($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Pagina::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $paginas = Pagina::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($paginas as $_pagina) {
                $items[] = $_pagina->publish();
            }
            json(['status' => 'ok', 'items' => $items]);
        }

        $nomes = Pagina::getNomeOptions();
        $linguagens = get_languages_info();
        return $this->view('gerenciar_pagina_index', get_defined_vars());
    }

    public function add()
    {
        need_permission(Permissao::NOME_ALTERARPAGINAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $pagina = Pagina::findByID($id);
        $pagina->setID(null);

        $focusctrl = 'nome';
        $errors = [];
        $old_pagina = $pagina;
        $nomes = Pagina::getNomeOptions();
        $linguagens = get_languages_info();
        if (is_post()) {
            $pagina = new Pagina($_POST);
            try {
                $pagina->filter($old_pagina);
                $pagina->insert();
                $old_pagina->clean($pagina);
                $msg = sprintf(
                    'Página "%s - %s" cadastrada com sucesso!',
                    $nomes[$pagina->getNome()],
                    $linguagens[$pagina->getLinguagemID()]
                );
                if (is_output('json')) {
                    json(null, ['item' => $pagina->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/pagina/');
            } catch (\Exception $e) {
                $pagina->clean($old_pagina);
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
        return $this->view('gerenciar_pagina_cadastrar', get_defined_vars());
    }

    public function update()
    {
        need_permission(Permissao::NOME_ALTERARPAGINAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $pagina = Pagina::findByID($id);
        if (!$pagina->exists()) {
            $msg = 'A página não foi informada ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/pagina/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_pagina = $pagina;
        $nomes = Pagina::getNomeOptions();
        $linguagens = get_languages_info();
        if (is_post()) {
            $pagina = new Pagina($_POST);
            try {
                $pagina->filter($old_pagina);
                $pagina->update();
                $old_pagina->clean($pagina);
                $msg = sprintf(
                    'Página "%s - %s" atualizada com sucesso!',
                    $nomes[$pagina->getNome()],
                    $linguagens[$pagina->getLinguagemID()]
                );
                if (is_output('json')) {
                    json(null, ['item' => $pagina->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/pagina/');
            } catch (\Exception $e) {
                $pagina->clean($old_pagina);
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
        return $this->view('gerenciar_pagina_editar', get_defined_vars());
    }

    public function delete()
    {
        need_permission(Permissao::NOME_ALTERARPAGINAS, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $pagina = Pagina::findByID($id);
        if (!$pagina->exists()) {
            $msg = 'A página não foi informada ou não existe';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/pagina/');
        }
        $nomes = Pagina::getNomeOptions();
        $linguagens = get_languages_info();
        try {
            $pagina->delete();
            $pagina->clean(new Pagina());
            $msg = sprintf(
                'Página "%s - %s" excluída com sucesso!',
                $nomes[$pagina->getNome()],
                $linguagens[$pagina->getLinguagemID()]
            );
            if (is_output('json')) {
                json('msg', $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a página "%s - %s"',
                $nomes[$pagina->getNome()],
                $linguagens[$pagina->getLinguagemID()]
            );
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::error($msg);
        }
        redirect('/gerenciar/pagina/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'pagina_index',
                'path' => '/',
                'method' => 'GET',
                'controller' => 'index',
            ],
            [
                'name' => 'pagina_about',
                'path' => '/sobre/',
                'method' => 'GET',
                'controller' => 'about',
            ],
            [
                'name' => 'pagina_privacy',
                'path' => '/sobre/privacidade',
                'method' => 'GET',
                'controller' => 'privacy',
            ],
            [
                'name' => 'pagina_terms',
                'path' => '/sobre/termos',
                'method' => 'GET',
                'controller' => 'terms',
            ],
            [
                'name' => 'pagina_contact',
                'path' => '/contato/',
                'method' => ['GET', 'POST'],
                'controller' => 'contact',
            ],
            [
                'name' => 'pagina_find',
                'path' => '/gerenciar/pagina/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'pagina_add',
                'path' => '/gerenciar/pagina/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'pagina_update',
                'path' => '/gerenciar/pagina/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'pagina_delete',
                'path' => '/gerenciar/pagina/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
