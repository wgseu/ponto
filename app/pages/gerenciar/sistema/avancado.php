<?php
use MZ\System\Sistema;
use MZ\System\Permissao;

need_permission(Permissao::NOME_ALTERARCONFIGURACOES, is_output('json'));

$focusctrl = 'mapskey';
$tab = 'avancado';

$erro = [];
$maps_api = get_string_config('Site', 'Maps.API');
$dropbox_token = get_string_config('Sistema', 'Dropbox.AccessKey');
if (is_post()) {
    try {
        $maps_api = isset($_POST['mapskey']) ? trim($_POST['mapskey']) : null;
        set_string_config('Site', 'Maps.API', $maps_api);
        $dropbox_token = isset($_POST['dropboxtoken']) ? trim($_POST['dropboxtoken']) : null;
        set_string_config('Sistema', 'Dropbox.AccessKey', $dropbox_token);
        $app->getSystem()->filter($app->getSystem());
        $app->getSystem()->update(['opcoes']);
        try {
            $appsync = new \MZ\System\Synchronizer();
            $appsync->systemOptionsChanged();
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        \Thunder::success('Opções avançadas atualizadas com sucesso!', true);
        redirect('/gerenciar/sistema/avancado');
    } catch (\ValidationException $e) {
        $erro = $e->getErrors();
    } catch (\Exception $e) {
        $erro['unknow'] = $e->getMessage();
    }
    foreach ($erro as $key => $value) {
        $focusctrl = $key;
        \Thunder::error($erro[$focusctrl]);
        break;
    }
}

return $app->getResponse()->output('gerenciar_sistema_avancado');
