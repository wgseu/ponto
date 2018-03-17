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

use MZ\Location\Bairro;

need_permission(\PermissaoNome::CADASTROBAIRROS, is_output('json'));
$id = isset($_GET['id'])?$_GET['id']:null;
$bairro = Bairro::findByID($id);
$bairro->setID(null);

$focusctrl = 'nome';
$errors = array();
$old_bairro = $bairro;
if (is_post()) {
    $bairro = new Bairro($_POST);
    try {
        $bairro->filter($old_bairro);
        $bairro->save();
        $old_bairro->clean($bairro);
        $msg = sprintf(
            'Bairro "%s" atualizado com sucesso!',
            $bairro->getNome()
        );
        if (is_output('json')) {
            json(null, array('item' => $bairro->publish(), 'msg' => $msg));
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/bairro/');
    } catch (\Exception $e) {
        $bairro->clean($old_bairro);
        if ($e instanceof \MZ\Exception\ValidationException) {
            $errors = $e->getErrors();
        }
        if (is_output('json')) {
            json($e->getMessage(), null, array('errors' => $errors));
        }
        \Thunder::error($e->getMessage());
        foreach ($errors as $key => $value) {
            $focusctrl = $key;
            break;
        }
    }
} elseif (is_output('json')) {
    json('Nenhum dado foi enviado');
}
if (is_null($bairro->getCidadeID())) {
    $bairro->setCidadeID($__cidade__->getID());
}
$cidade = $bairro->findCidadeID();
$estado = $cidade->findEstadoID();
$_paises = \MZ\Location\Pais::findAll();
if ($estado->exists()) {
    $pais = $estado->findPaisID();
} elseif (count($_paises) > 0) {
    $pais = current($_paises);
} else {
    $pais = new \MZ\Location\Pais();
}
$_estados = \MZ\Location\Estado::findAll(array('paisid' => $pais->getID()));

include template('gerenciar_bairro_cadastrar');
