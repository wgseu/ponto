<?php
require_once(dirname(__DIR__) . '/app.php');

need_permission(PermissaoNome::ALTERARCONFIGURACOES);

$fieldfocus = 'fiscal_timeout';
$tab_fiscal = 'active';

$erros = array();
$fiscal_timeout = get_int_config('Sistema', 'Fiscal.Timeout', 30);
if (is_post()) {
    try {
        $fiscal_timeout = \MZ\Util\Filter::number(isset($_POST['fiscal_timeout'])?$_POST['fiscal_timeout']:null);
        if (intval($fiscal_timeout) < 2) {
            throw new \MZ\Exception\ValidationException(
                array('fiscal_timeout' => 'O tempo limite não pode ser menor que 2 segundos')
            );
        }
        set_int_config('Sistema', 'Fiscal.Timeout', $fiscal_timeout);
        $__sistema__->salvarOpcoes($__options__);
        try {
            $appsync = new AppSync();
            $appsync->systemOptionsChanged();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        Thunder::success('Opções fiscais atualizadas com sucesso!', true);
        redirect('/gerenciar/sistema/fiscal');
    } catch (ValidationException $e) {
        $erros = $e->getErrors();
    } catch (Exception $e) {
        $erros['unknow'] = $e->getMessage();
    }
    foreach ($erros as $key => $value) {
        $fieldfocus = $key;
        Thunder::error($erros[$fieldfocus]);
        break;
    }
}

include template('gerenciar_sistema_fiscal');
