<?php
require_once(__DIR__ . '/app.php');

use MZ\System\Permissao;

if (is_owner()) {
    require_once('diversos/index.php');
} elseif (logged_employee()->has(Permissao::NOME_PAGAMENTO)) {
    require_once('pedido/index.php');
} else {
    require_once('funcionario/index.php');
}
