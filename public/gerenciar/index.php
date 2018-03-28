<?php
require_once(__DIR__ . '/app.php');

if (is_owner()) {
    require_once('diversos/index.php');
} elseif ($login_funcionario->has(Permissao::NOME_PAGAMENTO)) {
    require_once('pedido/index.php');
} else {
    require_once('funcionario/index.php');
}
