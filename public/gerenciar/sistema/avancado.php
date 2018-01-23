<?php
require_once(dirname(__DIR__) . '/app.php');

need_permission(PermissaoNome::ALTERARCONFIGURACOES);

$fieldfocus = 'mapskey';
$tab_avancado = 'active';

$erro = array();
$maps_api = get_string_config('Site', 'Maps.API');
$dropbox_token = get_string_config('Sistema', 'Dropbox.AccessKey');
if (is_post()) {
    try {
        $maps_api = trim($_POST['mapskey']);
        set_string_config('Site', 'Maps.API', $maps_api);
        $dropbox_token = trim($_POST['dropboxtoken']);
        set_string_config('Sistema', 'Dropbox.AccessKey', $dropbox_token);
        $__sistema__->salvarOpcoes($__options__);
        try {
            $appsync = new AppSync();
            $appsync->systemOptionsChanged();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        Thunder::success('Opções avançadas atualizadas com sucesso!', true);
        redirect('/gerenciar/sistema/avancado');
    } catch (ValidationException $e) {
        $erro = $e->getErrors();
    } catch (Exception $e) {
        $erro['unknow'] = $e->getMessage();
    }
    foreach ($erro as $key => $value) {
        $fieldfocus = $key;
        Thunder::error($erro[$fieldfocus]);
        break;
    }
}

include template('gerenciar_sistema_avancado');
