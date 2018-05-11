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

class Authentication
{
    const COOKIE_NAME = 'ru';

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
            $this->findByCookie(self::COOKIE_NAME);
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

    public function remember()
    {
        $zone = $this->getUser()->getID().'@'.$this->getUser()->getSenha();
        cookieset(self::COOKIE_NAME, base64_encode($zone), 30*86400);
        return $this;
    }

    public function forget()
    {
        cookieset(self::COOKIE_NAME, null, -1);
        return $this;
    }
    
    public function logout()
    {
        $this->forget();
        \Session::Get('cliente_id', true);
        $this->getUser()->fromArray([]);
        $this->getEmployee()->fromArray([]);
        $this->permissions = [];
        return $this;
    }

    public static function findByToken($token)
    {
        $cliente = new Cliente();
        $token = base64_decode($token);
        $len = strlen($token);
        if ($len <= 62 || $len > 100) {
            return $cliente;
        }
        $plen = $len - 48;
        $hlen = 40;
        $offset = min(max($plen * 2, 4 + $plen), 8);
        $id = '';
        $hash = '';
        $crc = '';
        for ($i = 0; $i < $len; $i++) {
            if ($i % 2 == 1 && $plen > 0) {
                $id .= $token[$i];
                $plen--;
            } elseif ($i >= $offset && $hlen > 0) {
                $hash .= $token[$i];
                $hlen--;
            } else {
                $crc .= $token[$i];
            }
        }
        $ccrc = dechex(crc32($id.$hash));
        $m = time();
        $sm = strtotime(preg_replace(
            "/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/",
            "$1-$2-$3 $4:$5:$6",
            substr($id, -14)
        ));
        $i = round(abs($m - $sm) / 60);
        if ($i > 5 || $sm === false || strcasecmp($crc, $ccrc) != 0) {
            return $cliente;
        }
        $id = substr($id, 0, -14);
        $cliente->loadByID(intval($id));
        if ($hash != $cliente->getSenha()) {
            return new Cliente();
        }
        return $cliente;
    }

    private function findByCookie($cname = 'ru')
    {
        $cv = cookieget($cname);
        if (!$cv) {
            $this->getUser()->fromArray([]);
            return $this;
        }
        $zone = base64_decode($cv);
        $p = explode('@', $zone, 2);
        $this->getUser()->load([
            'id' => $p[0],
            'senha' => $p[1],
        ]);
        return $this;
    }
}
