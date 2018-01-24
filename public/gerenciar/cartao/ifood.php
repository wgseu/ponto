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
require_once(dirname(dirname(__DIR__)) . '/app.php');

use MZ\System\Integracao;

need_permission(PermissaoNome::CADASTROCARTOES);

$integracao = Integracao::findByAcessoURL('ifood');
$dados = $integracao->read();
$cartoes = isset($dados['cartoes'])?$dados['cartoes']:array();
$codigos = array(
    'RAM' => array('name' => 'AMERICAN EXPRESS (Crédito)'),
    'DNREST' => array('name' => 'DINERS (Crédito)'),
    'REC' => array('name' => 'ELO (Crédito)'),
    'RHIP' => array('name' => 'HIPERCARD (Crédito)'),
    'RDREST' => array('name' => 'MASTERCARD (Crédito)'),
    'VSREST' => array('name' => 'VISA (Crédito)'),
    'RED' => array('name' => 'ELO (Débito)'),
    'MEREST' => array('name' => 'MASTERCARD (Débito)'),
    'VIREST' => array('name' => 'VISA (Débito)'),
    'VVREST' => array('name' => 'ALELO REFEICAO (Vale)'),
    'RSODEX' => array('name' => 'SODEXO (Vale)'),
    'TRE' => array('name' => 'TICKET RESTAURANTE (Vale)'),
    'VALECA' => array('name' => 'VALE CARD (Vale)'),
    'VR_SMA' => array('name' => 'VR SMART (Vale)'),
    'AM' => array('name' => 'AMEX (Online)'),
    'DNR' => array('name' => 'DINERS (Online)'),
    'ELO' => array('name' => 'ELO (Online)'),
    'MC' => array('name' => 'MASTERCARD (Online)'),
    'VIS' => array('name' => 'VISA (Online)')
);

if (isset($_GET['action']) && is_post() && $_GET['action'] == 'update') {
    need_permission(PermissaoNome::CADASTROCARTOES, true);
    try {
        if (!isset($_POST['codigo']) || !array_key_exists('id', $_POST)) {
            throw new \Exception('Código ou cartão não informado', 401);
        }
        $codigo = $_POST['codigo'];
        if (!array_key_exists($codigo, $codigos)) {
            throw new \Exception('O cartão informado não existe no iFood', 404);
        }
        $cartao = \ZCartao::getPeloID($_POST['id']);
        if (is_null($cartao->getID()) && is_numeric($_POST['id'])) {
            throw new \Exception('Cartão não encontrado', 401);
        }
        $cartoes[$codigo] = $cartao->getID();
        $dados = isset($dados)?$dados:array();
        $dados['cartoes'] = $cartoes;
        $integracao->write($dados);
        $appsync = new AppSync();
        $appsync->integratorChanged();
        json(null, array('cartao' => $cartao->toArray()));
    } catch (\Exception $e) {
        json($e->getMessage());
    }
}
foreach ($codigos as $index => $value) {
    $codigos[$index]['cartao'] = new \ZCartao();
    $codigos[$index]['status'] = 'empty';
    $codigos[$index]['icon'] = 'save';
}
foreach ($cartoes as $index => $id) {
    $cartao = \ZCartao::getPeloID($id);
    $status = '';
    if (is_null($cartao->getID())) {
        $status = 'empty';
    }
    $codigos[$index]['cartao'] = $cartao;
    $codigos[$index]['status'] = $status;
    $codigos[$index]['icon'] = 'save';
}
$_imagens = \ZCartao::getImages();
include template('gerenciar_cartao_ifood');
