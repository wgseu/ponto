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

need_permission(PermissaoNome::CADASTROCLIENTES, $_GET['saida'] == 'json');
$focusctrl = 'logradouro';
$errors = array();
if ($_POST) {
    $localizacao = new ZLocalizacao($_POST);
    try {
        DB::BeginTransaction();
        if ($localizacao->getClienteID() == $__empresa__->getID() &&
            !have_permission(PermissaoNome::ALTERARCONFIGURACOES)) {
            throw new Exception('Você não tem permissão para atribuir um endereço a essa empresa!');
        }
        $localizacao->setID(null);
        $localizacao->setCEP(\MZ\Util\Filter::unmask($localizacao->getCEP(), _p('Mascara', 'CEP')));
        $estado = ZEstado::getPeloID($_POST['estadoid']);
        if (is_null($estado->getID())) {
            throw new ValidationException(array('estadoid' => 'O estado não foi informado ou não existe!'));
        }
        $cidade = ZCidade::procuraOuCadastra($estado->getID(), $_POST['cidade']);
        $bairro = ZBairro::procuraOuCadastra($cidade->getID(), $_POST['bairro']);
        $localizacao->setBairroID($bairro->getID());
        $localizacao = ZLocalizacao::cadastrar($localizacao);
        DB::Commit();
        $msg = 'Localização "'.$localizacao->getLogradouro().'" cadastrada com sucesso!';
        if ($_GET['saida'] == 'json') {
            json(array('status' => 'ok', 'item' => $localizacao->toArray(), 'msg' => $msg));
        }
        Thunder::success($msg, true);
        redirect('/gerenciar/localizacao/');
    } catch (ValidationException $e) {
        $errors = $e->getErrors();
    } catch (Exception $e) {
        $errors['unknow'] = $e->getMessage();
    }
    DB::RollBack();
    foreach ($errors as $key => $value) {
        $focusctrl = $key;
        if ($_GET['saida'] == 'json') {
            json($value, null, array('field' => $focusctrl));
        }
        Thunder::error($value);
        break;
    }
} else {
    $localizacao = new ZLocalizacao();
    $localizacao->setApelido('Minha casa');
    $localizacao->setMostrar('Y');
}
if ($_GET['saida'] == 'json') {
    json('Nenhum dado foi enviado');
}
include template('gerenciar_localizacao_cadastrar');
