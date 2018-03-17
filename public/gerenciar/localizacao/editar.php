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

use MZ\Location\Localizacao;

need_permission(\PermissaoNome::CADASTROCLIENTES, true);
$id = isset($_GET['id'])?$_GET['id']:null;
$localizacao = Localizacao::findByID($id);
if (!$localizacao->exists()) {
    $msg = 'Não existe Localização com o ID informado!';
    json($msg);
}
if ($localizacao->getClienteID() == $__empresa__->getID() &&
    !have_permission(PermissaoNome::ALTERARCONFIGURACOES)) {
    $msg = 'Você não tem permissão para alterar o endereço dessa empresa!';
    json($msg);
}
$old_localizacao = $localizacao;
if (is_post()) {
    $localizacao = new Localizacao($_POST);
    try {
        \DB::BeginTransaction();
        $localizacao->filter($old_localizacao);
        $estado = \MZ\Location\Estado::findByID(isset($_POST['estadoid'])?$_POST['estadoid']:null);
        if (!$estado->exists()) {
            throw new \MZ\Exception\ValidationException(
                array('estadoid' => 'O estado não foi informado ou não existe!')
            );
        }
        $cidade = \MZ\Location\Cidade::findOrInsert($estado->getID(), isset($_POST['cidade'])?$_POST['cidade']:null);
        $bairro = \MZ\Location\Bairro::findOrInsert($cidade->getID(), isset($_POST['bairro'])?$_POST['bairro']:null);
        $localizacao->setBairroID($bairro->getID());
        $localizacao->save();
        $old_localizacao->clean($localizacao);
        \DB::Commit();
        try {
            if ($localizacao->getClienteID() == $__empresa__->getID()) {
                $appsync = new AppSync();
                $appsync->enterpriseChanged();
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        $msg = sprintf(
            'Localização "%s" atualizada com sucesso!',
            $localizacao->getLogradouro()
        );
        json(null, array('item' => $localizacao->publish(), 'msg' => $msg));
    } catch (\Exception $e) {
        \DB::RollBack();
        $localizacao->clean($old_localizacao);
        $errors = array();
        if ($e instanceof \MZ\Exception\ValidationException) {
            $errors = $e->getErrors();
        }
        json($e->getMessage(), null, array('errors' => $errors));
    }
} else {
    json('Nenhum dado foi enviado');
}
