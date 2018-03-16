<?php
require_once(__DIR__. '/include/application.php');

Session::Init();
$INI = ZSistema::getINI();
$__instance__ = DB::Instance();
$__sistema__  = ZSistema::getPeloID('1');
$__empresa__  = ZCliente::getPeloID($__sistema__->getEmpresaID());
$__localizacao__ = ZLocalizacao::getPeloClienteID($__empresa__->getID());
$__bairro__ = ZBairro::getPeloID($__localizacao__->getBairroID());
$__cidade__ = ZCidade::getPeloID($__bairro__->getCidadeID());
$__estado__ = ZEstado::getPeloID($__cidade__->getEstadoID());
$__pais__ = \MZ\Location\Pais::findByID($__sistema__->getPaisID());
$__moeda__ = $__pais__->findMoedaID();
$__options__  = parse_ini_string(base64_decode($__sistema__->getOpcoes()), true, INI_SCANNER_RAW);
$__entries__  = parse_ini_string(base64_decode($__pais__->getEntradas()), true, INI_SCANNER_RAW);
set_timezone_for($__estado__->getUF(), $__pais__->getSigla());

$login_cliente = ZAutenticacao::getCliente();
$login_cliente_id = $login_cliente->getID();
$login_funcionario = ZFuncionario::getPeloClienteID($login_cliente_id);
$login_funcionario_id = $login_funcionario->getID();
$__permissoes__ = ZAcesso::getPermissoes($login_funcionario->getID());

/* not allow access app.php */
$script_filename = str_replace('\\','/', __FILE__);
if($_SERVER['SCRIPT_FILENAME'] == $script_filename){
	json('Acesso indevido!');
}
/* end */
$AJAX = ('XMLHttpRequest' == @$_SERVER['HTTP_X_REQUESTED_WITH']);
if ( $AJAX == false )
	header('Content-Type: text/html; charset=UTF-8');
