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
require_once(dirname(dirname(__DIR__)) . '/app.php');

if (!is_login()) {
    json('Usuário não autenticado!');
}
if (!is_null($_GET['comanda']) && !logged_employee()->has(Permissao::NOME_PEDIDOCOMANDA)) {
    json('Você não tem permissão para acessar os produtos das comandas');
} elseif (!logged_employee()->has(Permissao::NOME_PEDIDOMESA)) {
    json('Você não tem permissão para acessar os produtos das mesas');
}

$tipo = Pedido::TIPO_MESA;
if (!is_null($_GET['comanda'])) {
    $tipo = Pedido::TIPO_COMANDA;
}
$pedidos = ProdutoPedido::getTodosDoLocal($tipo, $_GET['mesa'], $_GET['comanda']);
$response = ['status' => 'ok'];
$campos = [
            'id',
            'produtopedidoid',
            'tipo',
            'mesaid',
            'comandaid',
            'produtoid',
            'produtotipo',
            'produtodescricao',
            'produtoconteudo',
            'unidadesigla',
            'preco',
            'quantidade',
            'precovenda',
            'porcentagem',
            'detalhes',
            'descricao',
            'imagemurl',
            'produtodataatualizacao',
            'datahora',
        ];
$_pedidos = [];
foreach ($pedidos as $pedido) {
    $_pedido = array_intersect_key($pedido, array_flip($campos));
    $_pedido['imagemurl'] = get_image_url($_pedido['imagemurl'], 'produto', null);
    $_pedidos[] = $_pedido;
}
$response['total'] = Pedido::getTotalDoLocal($tipo, $_GET['mesa'], $_GET['comanda']);
$response['pedidos'] = $_pedidos;
json($response);
