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

$integracao = Integracao::findByAcessoURL(\MZ\Integrator\IFood::NAME);
$codigos = [
    'RAM' => ['name' => 'AMERICAN EXPRESS (Crédito)'],
    'DNREST' => ['name' => 'DINERS (Crédito)'],
    'REC' => ['name' => 'ELO (Crédito)'],
    'RHIP' => ['name' => 'HIPERCARD (Crédito)'],
    'RDREST' => ['name' => 'MASTERCARD (Crédito)'],
    'VSREST' => ['name' => 'VISA (Crédito)'],
    'RED' => ['name' => 'ELO (Débito)'],
    'MEREST' => ['name' => 'MASTERCARD (Débito)'],
    'VIREST' => ['name' => 'VISA (Débito)'],
    'VVREST' => ['name' => 'ALELO REFEICAO (Vale)'],
    'RSODEX' => ['name' => 'SODEXO (Vale)'],
    'TRE' => ['name' => 'TICKET RESTAURANTE (Vale)'],
    'VALECA' => ['name' => 'VALE CARD (Vale)'],
    'VR_SMA' => ['name' => 'VR SMART (Vale)'],
    'AM' => ['name' => 'AMEX (Online)'],
    'DNR' => ['name' => 'DINERS (Online)'],
    'ELO' => ['name' => 'ELO (Online)'],
    'MC' => ['name' => 'MASTERCARD (Online)'],
    'VIS' => ['name' => 'VISA (Online)']
];
$association = new \MZ\Association\Card($integracao, $codigos);

if (isset($_GET['action']) && is_post() && $_GET['action'] == 'update') {
    need_permission(PermissaoNome::CADASTROCARTOES, true);
    try {
        $codigo = isset($_POST['codigo'])?$_POST['codigo']:null;
        $id = array_key_exists('id', $_POST)?$_POST['id']:null;
        $cartao = $association->update($codigo, $id);
        json(null, ['cartao' => $cartao->toArray()]);
    } catch (\Exception $e) {
        json($e->getMessage());
    }
}
$codigos = $association->findAll();
$_imagens = \ZCartao::getImages();
include template('gerenciar_cartao_associar');
