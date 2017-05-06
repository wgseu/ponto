<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_permission(PermissaoNome::ALTERARCONFIGURACOES);

$fieldfocus = 'bemvindo';
$base_url = 'header';
$tab_layout = 'active';

$erro = array();
$images_info = array(
	'header' => array(
		'section' => 'Image.Header',
		'field' => 'header_url',
		'image' => 'image_header',
	),
	'login' => array(
		'section' => 'Image.Login',
		'field' => 'login_url',
		'image' => 'image_login',
	),
	'cadastrar' => array(
		'section' => 'Image.Cadastrar',
		'field' => 'cadastrar_url',
		'image' => 'image_cadastrar',
	),
	'produtos' => array(
		'section' => 'Image.Produtos',
		'field' => 'produtos_url',
		'image' => 'image_produtos',
	),
	'sobre' => array(
		'section' => 'Image.Sobre',
		'field' => 'sobre_url',
		'image' => 'image_sobre',
	),
	'privacidade' => array(
		'section' => 'Image.Privacidade',
		'field' => 'privacidade_url',
		'image' => 'image_privacidade',
	),
	'termos' => array(
		'section' => 'Image.Termos',
		'field' => 'termos_url',
		'image' => 'image_termos',
	),
	'contato' => array(
		'section' => 'Image.Contato',
		'field' => 'contato_url',
		'image' => 'image_contato',
	),
);
foreach ($images_info as $key => &$value) {
	$value['url'] = get_string_config('Site', $value['section']);
}
$text_bemvindo = get_string_config('Site', 'Text.BemVindo', 'Bem-vindo ao nosso restaurante!');
$text_chamada = get_string_config('Site', 'Text.Chamada', 'Conheça nosso cardápio!');
if($_POST) {
	foreach ($images_info as $key => &$value) {
		$value['save'] = $value['url'];
	}
	try {
		foreach ($images_info as $key => &$value) {
			$old_url = trim($_POST[$value['field']]);
			$value['save'] = upload_image($value['image'], $base_url);
			if(!is_null($value['save']))
				set_string_config('Site', $value['section'], $value['save']);
			else if($old_url == '')
				set_string_config('Site', $value['section'], null);
			else
				$value['save'] = $value['url'];
		}
		$text_bemvindo = trim($_POST['bemvindo']);
		set_string_config('Site', 'Text.BemVindo', $text_bemvindo);
		$text_chamada = trim($_POST['chamada']);
		set_string_config('Site', 'Text.Chamada', $text_chamada);
		$__sistema__->salvarOpcoes($__options__);
		foreach ($images_info as $key => &$value) {
			// exclui a imagem antiga, pois uma nova foi informada
			if(!is_null($value['url']) && 
				$value['save'] != $value['url']) {
				unlink(WWW_ROOT . get_image_url($value['url'], $base_url));
			}
		}
		Thunder::success('Layout atualizado com sucesso!', true);
		redirect('/gerenciar/sistema/layout');
	} catch (ValidationException $e) {
		$erro = $e->getErrors();
	} catch (Exception $e) {
		$erro['unknow'] = $e->getMessage();
	}
	foreach ($images_info as $key => &$value) {
		// remove imagem enviada
		if(!is_null($value['save']) && 
			$value['url'] != $value['save']) {
			unlink(WWW_ROOT . get_image_url($value['save'], $base_url));
		}
	}
}
foreach($erro as $key => $value) {
	$fieldfocus = $key;
	break;
}
if(array_key_exists($fieldfocus, $erro))
	Thunder::error($erro[$fieldfocus]);

include template('gerenciar_sistema_layout');