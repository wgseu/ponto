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

use Firebase\JWT\JWT;

class Authentication
{
    private $user;
    private $employee;
    private $permissions;

    /**
     * Constructor for a new empty instance of Authentication
     */
    public function __construct()
    {
        $this->user = new Cliente();
        $this->employee = new \MZ\Employee\Funcionario();
        $this->permissions = [];
    }

    /**
     * Get authenticated user
     * @return Cliente authenticated user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get authenticated employee
     * @return \MZ\Employee\Funcionario authenticated user
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Get permission list for logged employee
     * @return array permission list
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Load authentication info from session or cookie
     */
    public function initialize()
    {
        $this->getUser()->setID(\Session::Get('cliente_id'));
        return $this;
    }

    /**
     * Initialize logged user and update authentication
     */
    public function load()
    {
        $this->getUser()->loadByID($this->getUser()->getID());
        if (!$this->getUser()->exists()) {
            $this->findAuthorization();
            if ($this->getUser()->exists()) {
                $this->login($this->getUser());
            }
        } else {
            $this->refresh();
        }
        return $this;
    }

    public function refresh()
    {
        $this->getEmployee()->loadByClienteID($this->getUser()->getID());
        $this->permissions = \MZ\Employee\Acesso::getPermissoes($this->getEmployee()->getFuncaoID());
        return $this;
    }

    public function login($cliente)
    {
        $this->user = $cliente;
        \Session::Set('cliente_id', $this->getUser()->getID());
        $this->refresh();
        return $this;
    }

    public function getAuthorization()
    {
        $data = [
            'iat' => time(),
            'exp' => time() + 30 * 86400, // expira em 30 dias
            'id' => $this->getUser()->getID()
        ];
        $key = getenv('JWT_KEY');
        return JWT::encode($data, $key);
    }

    public function logout()
    {
        \Session::Get('cliente_id', true);
        $this->getUser()->fromArray([]);
        $this->getEmployee()->fromArray([]);
        $this->permissions = [];
        return $this;
    }

    public function updateAuthorization()
    {
        $token = $this->getBearerToken();
        if ($token) {
            $key = getenv('JWT_KEY');
            try {
                $tolerance = 15;
                $decoded = JWT::decode($token, $key, ['HS256']);
                if ($decoded->exp - $tolerance < time()) {
                    throw new \Exception('Refresh the token before expires', 301);
                }
            } catch(\Exception $e) {
                $token = $this->getAuthorization();
            }
        }
        return $token;
    }

    private function findAuthorization()
    {
        $token = $this->getBearerToken();
        if ($token) {
            $key = getenv('JWT_KEY');
            try {
                $decoded = JWT::decode($token, $key, ['HS256']);
                $this->getUser()->loadByID($decoded->id);
            } catch(\Exception $e) {
                $this->getUser()->fromArray([]);
            }
        } else {
            $this->getUser()->fromArray([]);
        }
        return $this;
    }

    /** 
     * Get hearder Authorization
     * */
    private function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions
            // (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(
                array_map('ucwords', array_keys($requestHeaders)),
                array_values($requestHeaders)
            );
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
    /**
    * get access token from header
    * */
    private function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}
