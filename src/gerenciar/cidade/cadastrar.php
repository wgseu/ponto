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
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_permission(PermissaoNome::CADASTROCIDADES);
$focusctrl = 'nome';
$errors = array();
if ($_POST) {
    $cidade = new ZCidade($_POST);
    try {
        $cidade->setID(null);
        $cidade->setCEP(\MZ\Util\Filter::unmask($cidade->getCEP(), _p('Mascara', 'CEP')));
        $cidade = ZCidade::cadastrar($cidade);
        Thunder::success('Cidade "'.$cidade->getNome().'" cadastrada com sucesso!', true);
        redirect('/gerenciar/cidade/');
    } catch (ValidationException $e) {
        $errors = $e->getErrors();
    } catch (Exception $e) {
        $errors['unknow'] = $e->getMessage();
    }
    foreach ($errors as $key => $value) {
        $focusctrl = $key;
        Thunder::error($value);
        break;
    }
} else {
    $cidade = new ZCidade();
}
$_estado = ZEstado::getPeloID($cidade->getEstadoID());
$_paises = ZPais::getTodas();
if (!is_null($_estado->getID())) {
    $pais = ZPais::getPeloID($_estado->getPaisID());
} elseif (count($_paises) > 0) {
    $pais = current($_paises);
} else {
    $pais = new ZPais();
}
$_estados = ZEstado::getTodosDaPaisID($pais->getID());
include template('gerenciar_cidade_cadastrar');
