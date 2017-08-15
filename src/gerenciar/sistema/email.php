<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_permission(PermissaoNome::ALTERARCONFIGURACOES);

$tab_email = 'active';
$fieldfocus = 'destinatario';
$errors = array();

$destinatario = get_string_config('Email', 'Remetente');
$servidor = get_string_config('Email', 'Servidor');
$porta = get_int_config('Email', 'Porta', 587);
$encriptacao = get_int_config('Email', 'Criptografia', 2);
$usuario = get_string_config('Email', 'Usuario');
if ($_POST) {
    try {
        $destinatario = trim($_POST['destinatario']);
        set_string_config('Email', 'Remetente', $destinatario);
        $servidor = trim($_POST['servidor']);
        set_string_config('Email', 'Servidor', $servidor);
        $porta = intval($_POST['porta']);
        if ($porta < 0 || $porta > 65535) {
            throw new Exception('A porta é inválida, informe um número entre 0 e 65535');
        }
        set_int_config('Email', 'Porta', $porta);
        $encriptacao = intval($_POST['encriptacao']);
        set_int_config('Email', 'Criptografia', $encriptacao);
        $usuario = trim($_POST['usuario']);
        set_string_config('Email', 'Usuario', $usuario);
        $senha = strval($_POST['senha']);
        if (strlen($senha) > 0) {
            set_string_config('Email', 'Senha', $senha);
        }
        $__sistema__->salvarOpcoes($__options__);
        try {
            $appsync = new AppSync();
            $appsync->systemOptionsChanged();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        Thunder::success('E-mail atualizado com sucesso!', true);
        redirect('/gerenciar/sistema/email');
    } catch (ValidationException $e) {
        $errors = $e->getErrors();
    } catch (Exception $e) {
        $errors['unknow'] = $e->getMessage();
    }
}
foreach ($errors as $key => $value) {
    $fieldfocus = $key;
    break;
}
if (array_key_exists($fieldfocus, $errors)) {
    Thunder::error($errors[$fieldfocus]);
}
include template('gerenciar_sistema_email');
