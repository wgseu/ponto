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
use MZ\Util\Mask;

/**
 * Allow application to serve system resources
 */
class ClientePageController extends \MZ\Core\Controller
{
    public function register()
    {
        if (is_login()) {
            $msg = 'Você já está cadastrado e autenticado!';
            if (is_output('json')) {
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
        if (is_post()) {
            $cliente = new Cliente($_POST);
            $aceitar = isset($_POST['aceitar']) ? $_POST['aceitar'] : null;
            try {
                if ($aceitar != 'true') {
                    throw new \MZ\Exception\ValidationException(
                        ['aceitar' => 'Os termos não foram aceitos']
                    );
                }
                $senha = isset($_POST['confirmarsenha']) ? $_POST['confirmarsenha'] : '';
                $cliente->passwordMatch($senha);
                $cliente->filter($old_cliente, true);
                $cliente->insert();
                $old_cliente->clean($cliente);
                if (is_output('json')) {
                    return $this->json()->success(['item' => $cliente->publish()]);
                }
                $this->getApplication()->getAuthentication()->login($cliente);
                return $this->redirect(get_redirect_page());
            } catch (\Exception $e) {
                $cliente->clean($old_cliente);
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
                if ($focusctrl == 'genero') {
                    $focusctrl = $focusctrl . '-' . strtolower(Cliente::GENERO_MASCULINO);
                }
            }
        } elseif (is_output('json')) {
            return $this->json()->error('Nenhum dado foi enviado');
        }
        $response = $this->getApplication()->getResponse();
        $response->setTitle('Registrar');
        $response->getEngine()->cliente = $cliente;
        $response->getEngine()->aceitar = $aceitar;
        $response->getEngine()->focusctrl = $focusctrl;
        return $response->output('conta_cadastrar');
    }

    public function logout()
    {
        $this->getApplication()->getAuthentication()->logout();
        return $this->redirect('/conta/entrar');
    }

    public function login()
    {
        if (is_login()) {
            $url = (is_post() && isset($_POST['redirect'])) ? strval($_POST['redirect']) : null;
            return $this->redirect($url);
        }
        if (is_post()) {
            $usuario = isset($_POST['usuario']) ? strval($_POST['usuario']) : null;
            $senha = isset($_POST['senha']) ? strval($_POST['senha']) : null;
            $cliente = Cliente::findByLoginSenha($usuario, $senha);
            if ($cliente->exists()) {
                $this->getApplication()->getAuthentication()->login($cliente);
                $url = isset($_POST['redirect']) ? strval($_POST['redirect']) : get_redirect_page();
                return $this->redirect($url);
            }
            $msg = 'Usuário ou senha incorretos!';
            \Thunder::error($msg);
        }
        $response = $this->getApplication()->getResponse();
        $response->setTitle('Entrar');
        return $response->output('conta_entrar');
    }

    public function edit()
    {
        need_login(is_output('json'));
        $cliente = logged_user();
        
        $tab = 'dados';
        $gerenciando = false;
        $cadastrar_cliente = false;
        $aceitar = 'true';
        
        $focusctrl = 'nome';
        $errors = [];
        $old_cliente = $cliente;
        if (is_post()) {
            $cliente = new Cliente($_POST);
            try {
                // não deixa o usuário alterar os dados abaixo
                $cliente->setEmail($old_cliente->getEmail());
                $cliente->setTipo($old_cliente->getTipo());
                $cliente->setAcionistaID($old_cliente->getAcionistaID());
                $cliente->setSlogan($old_cliente->getSlogan());
        
                $senha = isset($_POST['confirmarsenha']) ? $_POST['confirmarsenha'] : '';
                $cliente->passwordMatch($senha);
        
                $cliente->filter($old_cliente, true);
                $cliente->update();
                $old_cliente->clean($cliente);
                $msg = 'Conta atualizada com sucesso!';
                if (is_output('json')) {
                    return $this->json()->success(['item' => $cliente->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/conta/editar');
            } catch (\Exception $e) {
                $cliente->clean($old_cliente);
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
                if ($focusctrl == 'genero') {
                    $focusctrl = $focusctrl . '-' . strtolower($cliente->getGenero());
                }
            }
        } elseif (is_output('json')) {
            return $this->json()->error('Nenhum dado foi enviado');
        }
        $response = $this->getApplication()->getResponse();
        $response->setTitle('Editar Conta');
        $response->getEngine()->old_cliente = $old_cliente;
        $response->getEngine()->cliente = $cliente;
        $response->getEngine()->aceitar = $aceitar;
        $response->getEngine()->focusctrl = $focusctrl;
        $response->getEngine()->cadastrar_cliente = $cadastrar_cliente;
        $response->getEngine()->gerenciando = $gerenciando;
        $response->getEngine()->tab = $tab;
        return $response->output('conta_editar');
    }

    public function find()
    {
        need_permission(Permissao::NOME_CADASTROCLIENTES, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $genero = isset($condition['genero']) ? $condition['genero'] : null;
        if ($genero == 'Empresa') {
            $condition['tipo'] = Cliente::TIPO_JURIDICA;
            unset($condition['genero']);
        }
        $cliente = new Cliente($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Cliente::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $clientes = Cliente::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
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
        need_permission(Permissao::NOME_CADASTROCLIENTES, is_output('json'));
        $focusctrl = 'tipo';
        $errors = [];
        $cliente = new Cliente();
        $old_cliente = $cliente;
        if (is_post()) {
            $cliente = new Cliente($_POST);
            try {
                DB::beginTransaction();
                if (isset($_GET['sistema']) &&
                    intval($_GET['sistema']) == 1 &&
                    $cliente->getTipo() != Cliente::TIPO_JURIDICA
                ) {
                    throw new \MZ\Exception\ValidationException(['tipo' => 'O tipo da empresa deve ser jurídica']);
                }
                if (isset($_GET['sistema']) &&
                    intval($_GET['sistema']) == 1 &&
                    !is_null($this->getApplication()->getSystem()->getBusiness()->getEmpresaID())
                ) {
                    throw new \Exception(
                        'Você deve alterar a empresa do sistema em vez de cadastrar uma nova'
                    );
                }
                $cliente->filter($old_cliente, true);
                $cliente->insert();
                $old_cliente->clean($cliente);
                if (isset($_GET['sistema']) && intval($_GET['sistema']) == 1) {
                    $this->getApplication()->getSystem()->getBusiness()->setEmpresaID($cliente->getID());
                    $this->getApplication()->getSystem()->getBusiness()->update();
                }
                DB::commit();
                $msg = sprintf(
                    'Cliente "%s" cadastrado com sucesso!',
                    $cliente->getNomeCompleto()
                );
                if (is_output('json')) {
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
                if (is_output('json')) {
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
        } elseif (is_output('json')) {
            return $this->json()->error('Nenhum dado foi enviado');
        }
        return $this->view('gerenciar_cliente_cadastrar', get_defined_vars());
    }

    public function update()
    {
        need_manager(is_output('json'));

        need_manager(is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $cliente = Cliente::findByID($id);
        if (!$cliente->exists()) {
            $msg = 'O cliente não foi informado ou não existe!';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cliente/');
        }
        if ($cliente->getID() != logged_user()->getID()) {
            need_permission(Permissao::NOME_CADASTROCLIENTES, is_output('json'));
        }
        if ($cliente->getID() == $this->getApplication()->getSystem()->getCompany()->getID() &&
            !logged_provider()->has(Permissao::NOME_ALTERARCONFIGURACOES)
        ) {
            $msg = 'Você não tem permissão para alterar essa empresa!';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cliente/');
        }
        $prestador = Prestador::findByClienteID($cliente->getID());
        if ($prestador->exists() &&
            (
                (!logged_provider()->has(Permissao::NOME_CADASTROFUNCIONARIOS) &&
                    logged_provider()->getID() != $prestador->getID()
                ) ||
                ($prestador->has(Permissao::NOME_CADASTROFUNCIONARIOS) &&
                    logged_provider()->getID() != $prestador->getID() &&
                    !is_owner()
                )
            )
        ) {
            $msg = 'Você não tem permissão para alterar as informações desse cliente!';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cliente/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_cliente = $cliente;
        if (is_post()) {
            $cliente = new Cliente($_POST);
            try {
                if ($cliente->getID() == $this->getApplication()->getSystem()->getCompany()->getID() &&
                    $cliente->getTipo() != Cliente::TIPO_JURIDICA
                ) {
                    throw new \MZ\Exception\ValidationException(['tipo' => 'O tipo da empresa deve ser jurídica']);
                }
                $senha = isset($_POST['confirmarsenha']) ? $_POST['confirmarsenha'] : '';
                $cliente->passwordMatch($senha);
                $cliente->filter($old_cliente, true);
                $cliente->update();
                $old_cliente->clean($cliente);
                $msg = sprintf(
                    'Cliente "%s" atualizado com sucesso!',
                    $cliente->getNomeCompleto()
                );
                if (is_output('json')) {
                    return $this->json()->success(['item' => $cliente->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/cliente/');
            } catch (\Exception $e) {
                $cliente->clean($old_cliente);
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
                if ($focusctrl == 'genero') {
                    $focusctrl = $focusctrl . '-' . strtolower($cliente->getGenero());
                }
            }
        } elseif (is_output('json')) {
            return $this->json()->error('Nenhum dado foi enviado');
        }
        return $this->view('gerenciar_cliente_editar', get_defined_vars());
    }

    public function delete()
    {
        need_permission(Permissao::NOME_CADASTROCLIENTES, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $cliente = Cliente::findByID($id);
        if (!$cliente->exists()) {
            $msg = 'O cliente não foi informado ou não existe!';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cliente/');
        }
        if ($cliente->getID() == $this->getApplication()->getSystem()->getCompany()->getID()) {
            $msg = 'Essa empresa não pode ser excluída';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cliente/');
        }
        $prestador = Prestador::findByClienteID($cliente->getID());
        if ($prestador->exists() &&
            (
                (
                    !logged_provider()->has(Permissao::NOME_CADASTROFUNCIONARIOS) &&
                    logged_provider()->getID() != $prestador->getID()
                ) ||
                (
                    $prestador->has(Permissao::NOME_CADASTROFUNCIONARIOS) &&
                    logged_provider()->getID() != $prestador->getID() &&
                    !is_owner()
                )
            )
        ) {
            $msg = 'Você não tem permissão para excluir esse cliente';
            if (is_output('json')) {
                return $this->json()->error($msg);
            }
            \Thunder::warning($msg);
            return $this->redirect('/gerenciar/cliente/');
        }
        try {
            $cliente->delete();
            $cliente->clean(new Cliente());
            $msg = sprintf('Cliente "%s" excluído com sucesso!', $cliente->getNomeCompleto());
            if (is_output('json')) {
                return $this->json()->success([], $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o cliente "%s"',
                $cliente->getNome()
            );
            if (is_output('json')) {
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
