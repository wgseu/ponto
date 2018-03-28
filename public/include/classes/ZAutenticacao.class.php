<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
	ou outros avisos ou restrições de propriedade do GrandChef.

	O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
	ou descompilação do GrandChef.

	PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

	GrandChef é a especialidade do desenvolvedor e seus
	licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
	de leis de propriedade.

	O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
	direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
*/
class Authentication
{
    public static $cookie_name = 'ru';

    public static function getCliente()
    {
        $cliente_id = abs(intval(Session::Get('cliente_id')));
        if (!$cliente_id) {
            $cliente = Cliente::findByCookie(self::$cookie_name);
            if (!is_null($cliente->getID())) {
                self::login($cliente->getID());
                return $cliente;
            }
            return new Cliente();
        }
        return Cliente::findByID($cliente_id);
    }

    public static function login($cliente_id)
    {
        Session::Set('cliente_id', $cliente_id);
        return true;
    }

    public static function lembrar($cliente)
    {
        $zone = $cliente->getID().'@'.$cliente->getSenha();
        cookieset(self::$cookie_name, base64_encode($zone), 30*86400);
    }

    public static function esquecer()
    {
        cookieset(self::$cookie_name, null, -1);
    }
    
    public static function logout()
    {
        Session::Get('cliente_id', true);
        self::esquecer();
    }
}
