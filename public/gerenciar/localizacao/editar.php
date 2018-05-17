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
require_once(dirname(__DIR__) . '/app.php');

use MZ\Location\Estado;
use MZ\Location\Cidade;
use MZ\Location\Bairro;
use MZ\System\Permissao;
use MZ\System\Synchronizer;
use MZ\Location\Localizacao;
use MZ\Database\DB;

need_permission(Permissao::NOME_CADASTROCLIENTES, true);

$id = isset($_GET['id']) ? $_GET['id'] : null;
$localizacao = Localizacao::findByID($id);
if (!$localizacao->exists()) {
    $msg = 'A localização não foi informada ou não existe';
    json($msg);
}
if ($localizacao->getClienteID() == $app->getSystem()->getCompany()->getID() &&
    !logged_employee()->has(Permissao::NOME_ALTERARCONFIGURACOES)
) {
    $msg = 'Você não tem permissão para alterar o endereço dessa empresa!';
    json($msg);
}
$old_localizacao = $localizacao;
if (is_post()) {
    $localizacao = new Localizacao($_POST);
    try {
        DB::beginTransaction();
        $localizacao->filter($old_localizacao);
        $estado_id = isset($_POST['estadoid']) ? $_POST['estadoid'] : null;
        $estado = Estado::findByID($estado_id);
        if (!$estado->exists()) {
            throw new \MZ\Exception\ValidationException(
                ['estadoid' => 'O estado não foi informado ou não existe!']
            );
        }
        $cidade_id = isset($_POST['cidade']) ? $_POST['cidade'] : null;
        $cidade = Cidade::findOrInsert($estado->getID(), $cidade_id);
        $bairro_id = isset($_POST['bairro']) ? $_POST['bairro'] : null;
        $bairro = Bairro::findOrInsert($cidade->getID(), $bairro_id);
        $localizacao->setBairroID($bairro->getID());
        $localizacao->save();
        $old_localizacao->clean($localizacao);
        DB::commit();
        try {
            if ($localizacao->getClienteID() == $app->getSystem()->getCompany()->getID()) {
                $appsync = new Synchronizer();
                $appsync->enterpriseChanged();
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        $msg = sprintf(
            'Localização "%s" atualizada com sucesso!',
            $localizacao->getLogradouro()
        );
        json(null, ['item' => $localizacao->publish(), 'msg' => $msg]);
    } catch (\Exception $e) {
        DB::rollBack();
        $localizacao->clean($old_localizacao);
        $errors = [];
        if ($e instanceof \MZ\Exception\ValidationException) {
            $errors = $e->getErrors();
        }
        json($e->getMessage(), null, ['errors' => $errors]);
    }
} else {
    json('Nenhum dado foi enviado');
}
