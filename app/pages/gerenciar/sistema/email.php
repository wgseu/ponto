<?php
use MZ\System\Permissao;

need_permission(Permissao::NOME_ALTERARCONFIGURACOES, is_output('json'));

$tab = 'email';
$focusctrl = 'destinatario';
$errors = [];

$destinatario = get_string_config('Email', 'Remetente');
$servidor = get_string_config('Email', 'Servidor');
$porta = get_int_config('Email', 'Porta', 587);
$encriptacao = get_int_config('Email', 'Criptografia', 2);
$usuario = get_string_config('Email', 'Usuario');
if (is_post()) {
    try {
        $destinatario = isset($_POST['destinatario']) ? trim($_POST['destinatario']) : null;
        set_string_config('Email', 'Remetente', $destinatario);
        $servidor = isset($_POST['servidor']) ? trim($_POST['servidor']) : null;
        set_string_config('Email', 'Servidor', $servidor);
        $porta = isset($_POST['porta']) ? intval($_POST['porta']) : null;
        if ($porta < 0 || $porta > 65535) {
            throw new \Exception('A porta é inválida, informe um número entre 0 e 65535');
        }
        set_int_config('Email', 'Porta', $porta);
        $encriptacao = isset($_POST['encriptacao']) ? intval($_POST['encriptacao']) : null;
        set_int_config('Email', 'Criptografia', $encriptacao);
        $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : null;
        set_string_config('Email', 'Usuario', $usuario);
        $senha = isset($_POST['senha']) ? strval($_POST['senha']) : null;
        if (strlen($senha) > 0) {
            set_string_config('Email', 'Senha', $senha);
        }
        $app->getSystem()->filter($app->getSystem());
        $app->getSystem()->update(['opcoes']);
        try {
            $appsync = new \MZ\System\Synchronizer();
            $appsync->systemOptionsChanged();
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        $msg = 'E-mail atualizado com sucesso!';
        if (is_output('json')) {
            json(null, ['msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/sistema/email');
    } catch (\Exception $e) {
        $sistema->clean($old_sistema);
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
    }
} elseif (is_output('json')) {
    json('Nenhum dado foi enviado');
}

return $app->getResponse()->output('gerenciar_sistema_email');
