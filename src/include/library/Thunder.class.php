<?php

/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo � parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef � um software propriet�rio; voc� n�o pode redistribu�-lo e/ou modific�-lo.
	DISPOSI��ES GERAIS
	O cliente n�o dever� remover qualquer identifica��o do produto, avisos de direitos autorais,
	ou outros avisos ou restri��es de propriedade do GrandChef.

	O cliente n�o dever� causar ou permitir a engenharia reversa, desmontagem,
	ou descompila��o do GrandChef.

	PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

	GrandChef � a especialidade do desenvolvedor e seus
	licenciadores e � protegido por direitos autorais, segredos comerciais e outros direitos
	de leis de propriedade.

	O Cliente adquire apenas o direito de usar o software e n�o adquire qualquer outros
	direitos, expressos ou impl�citos no GrandChef diferentes dos especificados nesta Licen�a.
*/
class Thunder {
	private static $refCount = 0;
	
	private static function ShowMessage($type, $msg, $auto_close, $execute) {
		if(self::$refCount == 0) {
			echo '<link rel="stylesheet" type="text/css" href="/static/css/jquery.thunder.css" />'."\r\n";
			echo '<script src="/static/js/jquery.thunder.js"></script>'."\r\n";
			self::$refCount++;
		}
		if(!$execute)
			return;
		echo '<script type="text/javascript">$(function () { $(".thunder-container").message("'.$type.'", "'.$msg.'"';
		if($auto_close)
			echo ', { autoClose: { enable: true } }';
		echo ');});</script>'."\r\n";
	}
	
	private static function message($type, $msg, $auto_close) {
		$messages = array();
		if(Session::Get('thunder') != null)
			$messages = unserialize(Session::Get('thunder', true));
		$msg = str_replace('\\', '\\\\', $msg);
		$msg = str_replace('"', '\"', $msg);
		$messages[] = array('type' => $type, 'data' => array('message' => $msg, 'auto_close' => $auto_close));
		Session::Set('thunder', serialize($messages));
	}
	
	public static function warning($msg, $auto_close = false) {
		self::message('attention', $msg, $auto_close);
	}
	
	public static function success($msg, $auto_close = false) {
		self::message('success', $msg, $auto_close);
	}
	
	public static function error($msg, $auto_close = false) {
		self::message('error', $msg, $auto_close);
	}
	
	public static function information($msg, $auto_close = false) {
		self::message('information', $msg, $auto_close);
	}

	public static function Execute() {
		$messages = array();
		if(Session::Get('thunder') != null)
			$messages = unserialize(Session::Get('thunder', true));
		foreach($messages as $type => $value) {
			self::ShowMessage($value['type'], $value['data']['message'], $value['data']['auto_close'], true);
		}
		if(count($messages) == 0)
			self::ShowMessage('information', null, false, false);
		self::$refCount = 0;
	}
}
