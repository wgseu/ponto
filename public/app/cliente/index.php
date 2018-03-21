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
require_once(dirname(dirname(__DIR__)) . '/app.php');

if (!is_login()) {
    json('Usuário não autenticado!');
}
$_cliente = $_POST['cliente'];
try {
    if (!have_permission(PermissaoNome::PEDIDOMESA) && !have_permission(PermissaoNome::PEDIDOCOMANDA) &&
        !have_permission(PermissaoNome::PAGAMENTO) && !have_permission(PermissaoNome::CADASTROCLIENTES)) {
        throw new Exception('Você não tem permissão para cadastrar clientes');
    }
    $cliente = new ZCliente($_cliente);
    $cliente = ZCliente::cadastrar($cliente);
    $_cliente = $cliente->toArray();
    $_cliente['imagemurl'] = get_image_url($_cliente['imagem'], 'cliente', null);
    json(['status' => 'ok', 'cliente' => $_cliente]);
} catch (Exception $e) {
    json($e->getMessage());
}
