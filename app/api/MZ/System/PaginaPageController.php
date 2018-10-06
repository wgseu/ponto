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
use MZ\Util\Validator;
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class PaginaPageController extends PageController
{
    public function index()
    {
        return $this->view('index', get_defined_vars());
    }

    public function contact()
    {
        $focusctrl = 'nome';
        if (app()->getAuthentication()->isLogin()) {
            if (is_null(app()->auth->user->getEmail())) {
                $focusctrl = 'email';
            } else {
                $focusctrl = 'assunto';
            }
        }
        $erro = [];
        if ($this->getRequest()->isMethod('POST')) {
            $email = trim(strip_tags($this->getRequest()->request->get('email')));
            $nome = trim(strip_tags($this->getRequest()->request->get('nome')));
            if (app()->getAuthentication()->isLogin()) {
                $email = $email ?: app()->auth->user->getEmail();
                $nome = $nome ?: app()->auth->user->getNome();
            }
            $assunto = trim(strip_tags($this->getRequest()->request->get('assunto')));
            $mensagem = trim(strip_tags($this->getRequest()->request->get('mensagem')));
            if ($nome == '') {
                $erros['nome'] = 'O nome não pode ser vazio';
            }
            if (!Validator::checkEmail($email)) {
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
        $pagetitle = 'Contato';
        return $this->view('contato_index', get_defined_vars());
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
                'name' => 'pagina_contact',
                'path' => '/contato/',
                'method' => ['GET', 'POST'],
                'controller' => 'contact',
            ],
        ];
    }
}
