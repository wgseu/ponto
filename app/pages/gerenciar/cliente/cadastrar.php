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
use MZ\Account\Cliente;
use MZ\System\Permissao;
use MZ\Database\DB;
use MZ\System\Synchronizer;

need_permission(Permissao::NOME_CADASTROCLIENTES, is_output('json'));
$focusctrl = 'tipo';
$errors = [];
$cliente = new Cliente();
$old_cliente = $cliente;
if (is_post()) {
    $cliente = new Cliente($_POST);
    try {
        DB::beginTransaction();
        if (isset($_GET['sistema']) &&
            intval($_GET['sistema']) == 1 &&
            $cliente->getTipo() != Cliente::TIPO_JURIDICA
        ) {
            throw new \MZ\Exception\ValidationException(['tipo' => 'O tipo da empresa deve ser jurídica']);
        }
        if (isset($_GET['sistema']) &&
            intval($_GET['sistema']) == 1 &&
            !is_null($app->getSystem()->getEmpresaID())
        ) {
            throw new \Exception(
                'Você deve alterar a empresa do sistema em vez de cadastrar uma nova'
            );
        }
        $cliente->filter($old_cliente);
        $cliente->insert();
        $old_cliente->clean($cliente);
        if (isset($_GET['sistema']) && intval($_GET['sistema']) == 1) {
            $app->getSystem()->setEmpresaID($cliente->getID());
            $app->getSystem()->update();

            try {
                $sync = new Synchronizer();
                $sync->systemOptionsChanged();
                $sync->enterpriseChanged();
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
        }
        DB::commit();
        $msg = sprintf(
            'Cliente "%s" cadastrado com sucesso!',
            $cliente->getNomeCompleto()
        );
        if (is_output('json')) {
            json(null, ['item' => $cliente->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/cliente/');
    } catch (\Exception $e) {
        DB::rollBack();
        $cliente->clean($old_cliente);
        if ($e instanceof \MZ\Exception\ValidationException) {
            $errors = $e->getErrors();
        }
        if (is_output('json')) {
            json($e->getMessage(), null, ['errors' => $errors]);
        }
        \Thunder::error($e->getMessage());
        foreach ($errors as $key => $value) {
            $focusctrl = $key;
            break;
        }
        if ($focusctrl == 'genero') {
            $focusctrl = $focusctrl . '-' . strtolower(Cliente::GENERO_MASCULINO);
        }
    }
} elseif (is_output('json')) {
    json('Nenhum dado foi enviado');
}
return $app->getResponse()->output('gerenciar_cliente_cadastrar');
