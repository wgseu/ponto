<?php
use MZ\System\Permissao;

need_permission(Permissao::NOME_ALTERARCONFIGURACOES, is_output('json'));

$focusctrl = 'fiscal_timeout';
$tab = 'fiscal';

$erros = [];
$fiscal_timeout = get_int_config('Sistema', 'Fiscal.Timeout', 30);
if (is_post()) {
    try {
        $fiscal_timeout = \MZ\Util\Filter::number(isset($_POST['fiscal_timeout'])?$_POST['fiscal_timeout']:null);
        if (intval($fiscal_timeout) < 2) {
            throw new \MZ\Exception\ValidationException(
                ['fiscal_timeout' => 'O tempo limite não pode ser menor que 2 segundos']
            );
        }
        set_int_config('Sistema', 'Fiscal.Timeout', $fiscal_timeout);
        $app->getSystem()->filter($app->getSystem());
        $app->getSystem()->update(['opcoes']);
        try {
            $appsync = new \MZ\System\Synchronizer();
            $appsync->systemOptionsChanged();
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        \Thunder::success('Opções fiscais atualizadas com sucesso!', true);
        redirect('/gerenciar/sistema/fiscal');
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

return $app->getResponse()->output('gerenciar_sistema_fiscal');
