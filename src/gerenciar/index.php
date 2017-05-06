<?php
require_once(dirname(__FILE__) . '/app.php');

if(is_owner())
	require_once('diversos/index.php');
else if(have_permission(PermissaoNome::PAGAMENTO))
	require_once('pedido/index.php');
else
	require_once('funcionario/index.php');