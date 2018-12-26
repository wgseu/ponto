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
use MZ\System\Permissao;

class Authentication
{
    /**
     * Authenticated user
     * @var Cliente
     */
    public $user;

    /**
     * Authenticated provider
     * @var \MZ\Provider\Prestador
     */
    public $provider;

    /**
     * Provider allowed permissions
     * @var array
     */
    private $permissions;

    /**
     * Constructor for a new empty instance of Authentication
     */
    public function __construct()
    {
        $this->user = new Cliente();
        $this->provider = new \MZ\Provider\Prestador();
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
     * @return \MZ\Provider\Prestador authenticated user
     */
    public function getEmployee()
    {
        return $this->provider;
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
        $this->getUser()->setID(app()->getSession()->get('cliente_id'));
        return $this;
    }

    /**
     * Initialize logged user and update authentication
     */
    public function load()
    {
        $this->getUser()->loadByID();
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
        $this->provider = \MZ\Provider\Prestador::find([
            'clienteid' => $this->getUser()->getID(),
            'ativo' => 'Y',
        ]);
        $this->permissions = \MZ\System\Acesso::getPermissoes($this->getEmployee()->getFuncaoID());
        if (!$this->has([Permissao::NOME_SISTEMA])) {
            $this->getEmployee()->fromArray([]);
            $this->permissions = [];
        }
        return $this;
    }

    public function login($cliente)
    {
        $this->user = $cliente;
        app()->getSession()->set('cliente_id', $this->getUser()->getID());
        $this->refresh();
        return $this;
    }

    public function logout()
    {
        app()->getSession()->remove('cliente_id');
        $this->getUser()->fromArray([]);
        $this->getEmployee()->fromArray([]);
        $this->permissions = [];
        return $this;
    }

    /**
     * Check if has a user logged
     * @return boolean true if has a user logged, false otherwise
     */
    public function isLogin()
    {
        return $this->getUser()->exists();
    }

    /**
     * Check if logged user is a provider
     * @return boolean true if logged user is a provider, false otherwise
     */
    public function isManager()
    {
        return $this->getEmployee()->exists();
    }

    /**
     * Check if logged provider is owner of company
     * @return boolean true if logged provider is owner of company, false otherwise
     */
    public function isOwner()
    {
        return $this->isManager() && $this->getEmployee()->isOwner();
    }

    /**
     * Check if logged provider have permissions informed
     * @param array $permissions permission need
     * @return boolean True if have all permissions informed
     * @throws \MZ\Exception\AuthorizationException
     */
    public function has($permissions)
    {
        return $this->isManager() && $this->getEmployee()->has($permissions, $this->getPermissions());
    }

    /**
     * Check if logged provider is the same as provider informed
     * @param \MZ\Provider\Prestador $prestador service provider
     * @return boolean true if provider is the same as logged provider
     */
    public function isSelf($prestador)
    {
        return $this->isManager() && $prestador->getID() == $this->getEmployee()->getID();
    }

    public function makeToken()
    {
        $data = [
            'iat' => time(),
            'exp' => time() + 7 * 86400, // expira em 7 dias
            'id' => $this->getUser()->getID()
        ];
        $key = getenv('JWT_KEY');
        return JWT::encode($data, $key);
    }

    public function updateToken()
    {
        $token = $this->getBearerToken();
        if ($token) {
            $key = getenv('JWT_KEY');
            try {
                $tolerance = 6 * 86400; // renova uma vez por dia
                $decoded = JWT::decode($token, $key, ['HS256']);
                if ($decoded->exp - $tolerance < time()) {
                    throw new \Exception('Refresh the token before expires', 301);
                }
            } catch (\Exception $e) {
                $token = $this->makeToken();
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
                $this->getUser()->setID($decoded->id);
                $this->getUser()->loadByID();
            } catch (\Exception $e) {
                $this->getUser()->fromArray([]);
            }
        } else {
            $this->getUser()->fromArray([]);
        }
        return $this;
    }

    /**
     * Get hearder Authorization
     */
    private function getAuthorizationHeader()
    {
        $headers = null;
        if (app()->getRequest()->server->get('Authorization')) {
            $headers = trim(app()->getRequest()->server->get('Authorization'));
        } elseif (app()->getRequest()->server->get('HTTP_AUTHORIZATION')) {
            // Nginx or fast CGI
            $headers = trim(app()->getRequest()->server->get('HTTP_AUTHORIZATION'));
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
     */
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
