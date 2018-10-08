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
namespace MZ\Account;

use MZ\Database\DB;
use MZ\System\Permissao;
use MZ\Provider\Prestador;
use MZ\Location\Localizacao;
use MZ\Util\Filter;
use MZ\Core\PageController;
use MZ\Util\Mask;

/**
 * Allow application to serve system resources
 */
class ClientePageController extends PageController
{
    public function register()
    {
        if (app()->getAuthentication()->isLogin()) {
            $msg = 'Você já está cadastrado e autenticado!';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::information($msg, true);
            return $this->redirect('/');
        }
        $cliente = new Cliente();
        $errors = [];
        $focusctrl = 'nome';
        $old_cliente = $cliente;
        $aceitar = null;
        if ($this->getRequest()->isMethod('POST')) {
            $cliente = new Cliente($this->getData());
            $aceitar = $this->getRequest()->request->get('aceitar');
            try {
                if ($aceitar != 'true') {
                    throw new \MZ\Exception\ValidationException(
                        ['aceitar' => 'Os termos não foram aceitos']
                    );
                }
                $senha = $this->getRequest()->request->get('confirmarsenha', '');
                $cliente->passwordMatch($senha);
                $cliente->filter($old_cliente, true);
                $cliente->getTelefone()->setPaisID(app()->getSystem()->getCountry()->getID());
                $cliente->getTelefone()->filter($old_cliente->getTelefone(), true);
                $cliente->insert();
                $old_cliente->clean($cliente);
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $cliente->publish()]);
                }
                app()->getAuthentication()->login($cliente);
                return $this->loginSuccessfull();
            } catch (\Exception $e) {
                $cliente->clean($old_cliente);
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
                if ($focusctrl == 'genero') {
                    $focusctrl = $focusctrl . '-' . strtolower(Cliente::GENERO_MASCULINO);
                }
            }
        } elseif ($this->isJson()) {
            return $this->json()->error('Nenhum dado foi enviado');
        }
        $pagetitle = 'Registrar';
        return $this->view('conta_cadastrar', get_defined_vars());
    }

    public function logout()
    {
        app()->getAuthentication()->logout();
        return $this->redirect('/conta/entrar');
    }

    private function loginSuccessfull()
    {
        $url = app()->getSession()->get('redirect', '/');
        $url = $this->getRequest()->request->get('redirect', $url);
        app()->getSession()->remove('redirect');
        return $this->redirect($url);
    }

    public function login()
    {
        if (app()->getAuthentication()->isLogin()) {
            $url = $this->getRequest()->request->get('redirect');
            return $this->redirect($url);
        }
        if ($this->getRequest()->isMethod('POST')) {
            $usuario = $this->getRequest()->request->get('usuario');
            $senha = $this->getRequest()->request->get('senha');
            $cliente = Cliente::findByLoginSenha($usuario, $senha);
            if ($cliente->exists()) {
                app()->getAuthentication()->login($cliente);
                return $this->loginSuccessfull();
            }
            $msg = 'Usuário ou senha incorretos!';
            \Thunder::error($msg);
        }
        $pagetitle = 'Entrar';
        return $this->view('conta_entrar', get_defined_vars());
    }

    public function edit()
    {
        app()->needLogin();
        $cliente = app()->auth->user;
        
        $tab = 'dados';
        $gerenciando = false;
        $cadastrar_cliente = false;
        $aceitar = 'true';
        
        $focusctrl = 'nome';
        $errors = [];
        $old_cliente = $cliente;
        if ($this->getRequest()->isMethod('POST')) {
            $cliente = new Cliente($this->getData());
            try {
                // não deixa o usuário alterar os dados abaixo
                $cliente->setEmail($old_cliente->getEmail());
                $cliente->setTipo($old_cliente->getTipo());
                $cliente->setEmpresaID($old_cliente->getEmpresaID());
                $cliente->setSlogan($old_cliente->getSlogan());

                $senha = $this->getRequest()->request->get('confirmarsenha', '');
                $cliente->passwordMatch($senha);

                $cliente->filter($old_cliente, true);
                $cliente->getTelefone()->setPaisID(app()->getSystem()->getCountry()->getID());
                $cliente->getTelefone()->filter($old_cliente->getTelefone(), true);
                $cliente->update();

                $old_cliente->clean($cliente);
                $old_cliente->getTelefone()->clean($cliente->getTelefone());
                $msg = 'Conta atualizada com sucesso!';
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $cliente->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/conta/editar');
            } catch (\Exception $e) {
                $cliente->clean($old_cliente);
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
                if ($focusctrl == 'genero') {
                    $focusctrl = $focusctrl . '-' . strtolower($cliente->getGenero());
                }
            }
        } elseif ($this->isJson()) {
            return $this->json()->error('Nenhum dado foi enviado');
        }
        $pagetitle = 'Editar Conta';
        return $this->view('conta_editar', get_defined_vars());
    }

    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $genero = isset($condition['genero']) ? $condition['genero'] : null;
        if ($genero == 'Empresa') {
            $condition['tipo'] = Cliente::TIPO_JURIDICA;
            unset($condition['genero']);
        }
        $cliente = new Cliente($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Cliente::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genPages();
        $clientes = Cliente::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($clientes as $_cliente) {
                $items[] = $_cliente->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $tipos = Cliente::getGeneroOptions();
        $tipos = ['Empresa' => 'Empresa'] + $tipos;

        return $this->view('gerenciar_cliente_index', get_defined_vars());
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);
        $focusctrl = 'tipo';
        $errors = [];
        $cliente = new Cliente();
        $old_cliente = $cliente;
        if ($this->getRequest()->isMethod('POST')) {
            $cliente = new Cliente($this->getData());
            try {
                DB::beginTransaction();
                if ($this->getRequest()->query->getInt('sistema') == 1 &&
                    $cliente->getTipo() != Cliente::TIPO_JURIDICA
                ) {
                    throw new \MZ\Exception\ValidationException(['tipo' => 'O tipo da empresa deve ser jurídica']);
                }
                if ($this->getRequest()->query->getInt('sistema') == 1 &&
                    !is_null(app()->getSystem()->getBusiness()->getEmpresaID())
                ) {
                    throw new \Exception(
                        'Você deve alterar a empresa do sistema em vez de cadastrar uma nova'
                    );
                }
                $cliente->filter($old_cliente, true);
                $cliente->getTelefone()->setPaisID(app()->getSystem()->getCountry()->getID());
                $cliente->getTelefone()->filter($old_cliente->getTelefone(), true);
                $cliente->insert();
                $old_cliente->clean($cliente);
                if ($this->getRequest()->query->getInt('sistema') == 1) {
                    // evita de alterar a senha
                    app()->auth->user->setSenha(null);
                    // o primeiro a cadastrar a empresa será o dono
                    app()->auth->user->setEmpresaID($cliente->getID());
                    app()->auth->user->update();
                    app()->getSystem()->getBusiness()->setEmpresaID($cliente->getID());
                    app()->getSystem()->getBusiness()->update();
                }
                DB::commit();
                $msg = sprintf(
                    'Cliente "%s" cadastrado com sucesso!',
                    $cliente->getNomeCompleto()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $cliente->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/cliente/');
            } catch (\Exception $e) {
                DB::rollBack();
                $cliente->clean($old_cliente);
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
                if ($focusctrl == 'genero') {
                    $focusctrl = $focusctrl . '-' . strtolower(Cliente::GENERO_MASCULINO);
                }
            }
        } elseif ($this->isJson()) {
            return $this->json()->error('Nenhum dado foi enviado');
        }
        return $this->view('gerenciar_cliente_cadastrar', get_defined_vars());
    }

    public function update()
    {
        app()->needManager();
        $id = $this->getRequest()->query->getInt('id', null);
        $cliente = Cliente::findByID($id);
        if (!$cliente->exists()) {
            $msg = 'O cliente não foi informado ou não existe!';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cliente/');
        }
        if ($cliente->getID() != app()->auth->user->getID()) {
            $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);
        }
        if ($cliente->getID() == app()->getSystem()->getCompany()->getID() &&
            !app()->auth->has([Permissao::NOME_ALTERARCONFIGURACOES])
        ) {
            $msg = 'Você não tem permissão para alterar essa empresa!';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cliente/');
        }
        $prestador = Prestador::findByClienteID($cliente->getID());
        if ($prestador->exists() &&
            (
                (!app()->auth->has([Permissao::NOME_CADASTROFUNCIONARIOS]) &&
                    app()->auth->provider->getID() != $prestador->getID()
                ) ||
                ($prestador->has([Permissao::NOME_CADASTROFUNCIONARIOS]) &&
                    app()->auth->provider->getID() != $prestador->getID() &&
                    !app()->auth->isOwner()
                )
            )
        ) {
            $msg = 'Você não tem permissão para alterar as informações desse cliente!';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cliente/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_cliente = $cliente;
        if ($this->getRequest()->isMethod('POST')) {
            $cliente = new Cliente($this->getData());
            try {
                if ($cliente->getID() == app()->getSystem()->getCompany()->getID() &&
                    $cliente->getTipo() != Cliente::TIPO_JURIDICA
                ) {
                    throw new \MZ\Exception\ValidationException(['tipo' => 'O tipo da empresa deve ser jurídica']);
                }
                $senha = $this->getRequest()->request->get('confirmarsenha', '');
                $cliente->passwordMatch($senha);
                $cliente->filter($old_cliente, true);
                $cliente->getTelefone()->setPaisID(app()->getSystem()->getCountry()->getID());
                $cliente->getTelefone()->filter($old_cliente->getTelefone(), true);
                $cliente->update();
                $old_cliente->clean($cliente);
                $msg = sprintf(
                    'Cliente "%s" atualizado com sucesso!',
                    $cliente->getNomeCompleto()
                );
                if ($this->isJson()) {
                    return $this->json()->success(['item' => $cliente->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/cliente/');
            } catch (\Exception $e) {
                $cliente->clean($old_cliente);
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
                if ($focusctrl == 'genero') {
                    $focusctrl = $focusctrl . '-' . strtolower($cliente->getGenero());
                }
            }
        } elseif ($this->isJson()) {
            return $this->json()->error('Nenhum dado foi enviado');
        }
        return $this->view('gerenciar_cliente_editar', get_defined_vars());
    }

    public function delete()
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);
        $id = $this->getRequest()->query->getInt('id', null);
        $cliente = Cliente::findByID($id);
        if (!$cliente->exists()) {
            $msg = 'O cliente não foi informado ou não existe!';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cliente/');
        }
        if ($cliente->getID() == app()->getSystem()->getCompany()->getID()) {
            $msg = 'Essa empresa não pode ser excluída';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cliente/');
        }
        $prestador = Prestador::findByClienteID($cliente->getID());
        if ($prestador->exists() &&
            (
                (
                    !app()->auth->has([Permissao::NOME_CADASTROFUNCIONARIOS]) &&
                    app()->auth->provider->getID() != $prestador->getID()
                ) ||
                (
                    $prestador->has([Permissao::NOME_CADASTROFUNCIONARIOS]) &&
                    app()->auth->provider->getID() != $prestador->getID() &&
                    !app()->auth->isOwner()
                )
            )
        ) {
            $msg = 'Você não tem permissão para excluir esse cliente';
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cliente/');
        }
        try {
            $cliente->delete();
            $cliente->clean(new Cliente());
            $msg = sprintf('Cliente "%s" excluído com sucesso!', $cliente->getNomeCompleto());
            if ($this->isJson()) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o cliente "%s"',
                $cliente->getNome()
            );
            if ($this->isJson()) {
                return $this->json()->error($msg);
            }
            \Thunder::error($msg);
        }
        return $this->redirect('/gerenciar/cliente/');
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'cliente_view',
                'path' => '/conta/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'register',
            ],
            [
                'name' => 'cliente_edit',
                'path' => '/conta/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'edit',
            ],
            [
                'name' => 'cliente_logout',
                'path' => '/conta/sair',
                'method' => 'GET',
                'controller' => 'logout',
            ],
            [
                'name' => 'cliente_login',
                'path' => '/conta/entrar',
                'method' => ['GET', 'POST'],
                'controller' => 'login',
            ],
            [
                'name' => 'cliente_find',
                'path' => '/gerenciar/cliente/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'cliente_add',
                'path' => '/gerenciar/cliente/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'cliente_update',
                'path' => '/gerenciar/cliente/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'cliente_delete',
                'path' => '/gerenciar/cliente/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
