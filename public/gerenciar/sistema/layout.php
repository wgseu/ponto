<?php
require_once(dirname(__DIR__) . '/app.php');

use MZ\System\Permissao;

need_permission(Permissao::NOME_ALTERARCONFIGURACOES, is_output('json'));

$focusctrl = 'bemvindo';
$base_url = 'header';
$tab_layout = 'active';

$erro = [];
$images_info = [
    'header' => [
        'section' => 'Image.Header',
        'field' => 'header_url',
        'image' => 'image_header',
    ],
    'login' => [
        'section' => 'Image.Login',
        'field' => 'login_url',
        'image' => 'image_login',
    ],
    'cadastrar' => [
        'section' => 'Image.Cadastrar',
        'field' => 'cadastrar_url',
        'image' => 'image_cadastrar',
    ],
    'produtos' => [
        'section' => 'Image.Produtos',
        'field' => 'produtos_url',
        'image' => 'image_produtos',
    ],
    'sobre' => [
        'section' => 'Image.Sobre',
        'field' => 'sobre_url',
        'image' => 'image_sobre',
    ],
    'privacidade' => [
        'section' => 'Image.Privacidade',
        'field' => 'privacidade_url',
        'image' => 'image_privacidade',
    ],
    'termos' => [
        'section' => 'Image.Termos',
        'field' => 'termos_url',
        'image' => 'image_termos',
    ],
    'contato' => [
        'section' => 'Image.Contato',
        'field' => 'contato_url',
        'image' => 'image_contato',
    ],
];
foreach ($images_info as $key => &$value) {
    $value['url'] = get_string_config('Site', $value['section']);
}
$text_bemvindo = get_string_config('Site', 'Text.BemVindo', 'Bem-vindo ao nosso restaurante!');
$text_chamada = get_string_config('Site', 'Text.Chamada', 'Conheça nosso cardápio!');
if (is_post()) {
    foreach ($images_info as $key => &$value) {
        $value['save'] = $value['url'];
    }
    try {
        foreach ($images_info as $key => &$value) {
            $old_url = isset($_POST[$value['field']]) ? trim($_POST[$value['field']]) : null;
            $value['save'] = upload_image($value['image'], $base_url);
            if (!is_null($value['save'])) {
                set_string_config('Site', $value['section'], $value['save']);
            } elseif ($old_url == '') {
                set_string_config('Site', $value['section'], null);
            } else {
                $value['save'] = $value['url'];
            }
        }
        $text_bemvindo = isset($_POST['bemvindo']) ? trim($_POST['bemvindo']) : null;
        set_string_config('Site', 'Text.BemVindo', $text_bemvindo);
        $text_chamada = isset($_POST['chamada']) ? trim($_POST['chamada']) : null;
        set_string_config('Site', 'Text.Chamada', $text_chamada);
        $app->getSystem()->filter($app->getSystem());
        $app->getSystem()->update(['opcoes']);
        foreach ($images_info as $key => $value) {
            // exclui a imagem antiga, pois uma nova foi informada
            if (!is_null($value['url']) &&
                $value['save'] != $value['url']
            ) {
                unlink($app->getPath('public') . get_image_url($value['url'], $base_url));
            }
        }
        $msg = 'Layout atualizado com sucesso!';
        if (is_output('json')) {
            json(null, ['item' => $sistema->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/sistema/layout');
    } catch (\Exception $e) {
        $sistema->clean($old_sistema);
        if ($e instanceof \MZ\Exception\ValidationException) {
            $errors = $e->getErrors();
        }
        foreach ($images_info as $key => $value) {
            // remove imagem enviada
            if (!is_null($value['save']) &&
                $value['url'] != $value['save']
            ) {
                unlink($app->getPath('public') . get_image_url($value['save'], $base_url));
            }
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

$app->getResponse('html')->output('gerenciar_sistema_layout');
