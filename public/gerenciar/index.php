<?php
require_once(__DIR__ . '/app.php');

if (is_owner()) {
    require_once('diversos/index.php');
} elseif (have_permission(PermissaoNome::PAGAMENTO)) {
    require_once('pedido/index.php');
} else {
    require_once('funcionario/index.php');
}
