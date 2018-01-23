<?php
/*
	Copyright 2016 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
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
require_once(dirname(__DIR__) . '/app.php');

need_permission(PermissaoNome::PAGAMENTO);

$data_inicio = date_create_from_format('d/m/Y', $_GET['datahora_inicio']);
$data_inicio = $data_inicio===false?null:$data_inicio->getTimestamp();
$data_fim = date_create_from_format('d/m/Y', $_GET['datahora_fim']);
$data_fim = $data_fim===false?null:$data_fim->getTimestamp();
$count = ZProdutoPedido::getCount(
    $_GET['query'],
    $_GET['produto_id'],
    $_GET['funcionario_id'],
    null, // id da sessão
    null, // id da movimentação
    $_GET['tipo'],
    $_GET['estado'],
    $_GET['modulo'],
    $data_inicio,
    $data_fim
);
list($pagesize, $offset, $pagestring) = pagestring($count, 10);
$itens_do_pedido = ZProdutoPedido::getTodos(
    $_GET['query'],
    $_GET['produto_id'],
    $_GET['funcionario_id'],
    null, // id da sessão
    null, // id da movimentação
    $_GET['tipo'],
    $_GET['estado'],
    $_GET['modulo'],
    $data_inicio,
    $data_fim,
    false, // disable raw
    $offset,
    $pagesize
);

$_modulo_names = array(
    'Mesa' => 'Mesa',
    'Comanda' => 'Comanda',
    'Avulso' => 'Balcão',
    'Entrega' => 'Entrega',
);

$_estado_names = array(
    'Valido' => 'Válido',
    'Adicionado' => 'Adicionado',
    'Enviado' => 'Enviado',
    'Processado' => 'Processado',
    'Pronto' => 'Pronto',
    'Disponivel' => 'Disponível',
    'Entregue' => 'Entregue',
    'Cancelado' => 'Cancelado',
);

$_tipo_names = array(
    'Produtos' => 'Todos os produtos',
    'Produto' => 'Produto',
    'Composicao' => 'Composição',
    'Pacote' => 'Pacote',
    'Servico' => 'Todos os serviços',
    'Evento' => 'Evento',
    'Taxa' => 'Taxa',
    'Desconto' => 'Desconto',
);

$_pedido_icon = array(
    'Mesa' => 0,
    'Comanda' => 16,
    'Avulso' => 32,
    'Entrega' => 48,
);

$_produto = ZProduto::getPeloID($_GET['produto_id']);
$_funcionario = ZFuncionario::getPeloID($_GET['funcionario_id']);
include template('gerenciar_produto_pedido_index');
