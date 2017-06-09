<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
	ou outros avisos ou restrições de propriedade do GrandChef.

	O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
	ou descompilação do GrandChef.

	PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

	GrandChef é a especialidade do desenvolvedor e seus
	licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
	de leis de propriedade.

	O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
	direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
*/

function numberval($str) {
	return preg_replace('/[^0-9-]/', '', $str);
}

function is_number($value) {
	return \MZ\Util\Filter::digits($value) == $value;
}

function is_equal($value, $compare, $delta = 0.005) {
	return $compare < ($value + $delta) && ($value - $delta) < $compare;
}

function is_greater($value, $compare, $delta = 0.005) {
	return $value > ($compare + $delta);
}

function is_less($value, $compare, $delta = 0.005) {
	return ($value + $delta) < $compare;
}

// Função que valida o CPF
function check_cpf($cpf) {
	// Verifiva se o número digitado contém todos os digitos
	$cpf = \MZ\Util\Filter::digits($cpf);
	$pattern = '/^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/';
	// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
	if (strlen($cpf) != 11 || preg_match($pattern, $cpf))
		return false;
	// Calcula os números para verificar se o CPF é verdadeiro
	for ($t = 9; $t < 11; $t++) {
		for ($d = 0, $c = 0; $c < $t; $c++) {
			$d += $cpf{$c} * (($t + 1) - $c);
		}
		$d = ((10 * $d) % 11) % 10;
		if ($cpf{$c} != $d)
			return false;
	}
	return true;
}

// Função que valida o CNPJ
function check_cnpj($cnpj) {
	// Verifiva se o número digitado contém todos os digitos
	$cnpj = \MZ\Util\Filter::digits($cnpj);
	$pattern = '/^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/';
	if(strlen($cnpj) != 14 || preg_match($pattern, $cnpj)) {
		return false;
	}
	$calcular = 0;
	$calcularDois = 0;
	for ($i = 0, $x = 5; $i <= 11; $i++, $x--) {
		$x = ($x < 2) ? 9 : $x;
		$number = substr($cnpj, $i, 1);
		$calcular += $number * $x;
	}
	for ($i = 0, $x = 6; $i <= 12; $i++, $x--) {
		$x = ($x < 2) ? 9 : $x;
		$numberDois = substr($cnpj, $i, 1);
		$calcularDois += $numberDois * $x;
	}
	$digitoUm = (($calcular % 11) < 2) ? 0 : 11 - ($calcular % 11);
	$digitoDois = (($calcularDois % 11) < 2) ? 0 : 11 - ($calcularDois % 11);
	if ($digitoUm != substr($cnpj, 12, 1) || $digitoDois != substr($cnpj, 13, 1)) {
		return false;
	}
	return true;
}

// Função que valida o Email
function check_email($email) {
	return Utility::ValidEmail($email);
}

// Função que valida o nome de usuário
function check_usuario($usuario) {
	return preg_match('/^[A-Za-z][A-Za-z0-9\._-]{2,44}$/', $usuario);
}

// Função que valida o telefone
function check_fone($fone, $ignore_ddd = false) {
	$fone = \MZ\Util\Filter::digits($fone);
	return strlen($fone) == 10 || strlen($fone) == 11 || ($ignore_ddd && (strlen($fone) == 8 || strlen($fone) == 9));
}

// Função que valida o CEP
function check_cep($cep) {
	$cep = \MZ\Util\Filter::digits($cep);
	return strlen($cep) == 8;
}

function human_size($size) {
	if($size < 1000)
		return $size . ($size == 1)? ' byte': ' bytes';
	else if($size < 1024 * 1000)
		return number_format($size/1024, 1) . ' kB';
	else if($size < 1024 * 1024 * 1000)
		return number_format($size/(1024 * 1024), 1) . ' MB';
	else
		return number_format($size/(1024 * 1024 * 1024), 1) . ' GB';
}

function human_filesize($filename) {
	return human_size(filesize($filename));
}

function moneyit($k, $sep = ',') {
	$k = round($k, 2);
	return number_format($k, 2, ',', '.');
}

function moneyval($k) {
	$k = preg_replace('/[^0-9,\-]/', '', $k);
	return floatval(str_replace(',', '.', $k));
}

function quantify($quantity, $unity = 'UN', $content = 1) {
	$total = $quantity * $content;
	$trunc = floor($total);
	$frac = $total - $trunc;
	$multiplier = 1;
	if($total < 0)
		$multiplier = -1;
	$total = $multiplier * $total;
	$result = $unity;
	if($unity == 'UN')
		$result = '';
	else if($unity == 'm' && $trunc == 0 && $frac > 0.01) {
		$result = 'c'.$result;
		$multiplier *= 100;
	} else if($total < 1) {
		$result = 'm'.$result;
		$multiplier *= 1000;
	} else if($total >= 1000 && $unity != 'L') {
		$result = 'k'.$result;
		$multiplier *= 0.001;
	}
	$number = number_format($total * $multiplier, 3, ',', '.');
	$split = preg_split('/[,]/', $number);
	return $split[0].rtrim(','.$split[1], ',0').$result;
}

function relative_day($window, $date = null)
{
	if(is_null($date))
		$date = time();
	$days = date('t', $date);
	$month_begin = strtotime(date('Y-m', $date).' '.$window.' month');
	$prev_days = date('t', $month_begin);
	$percent = date('j', $date) / $days;
	return max(1, round($prev_days * $percent));
}

function human_date($date, $year = false) {
	$_date = date_create($date);
	$months = array(1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr', 
			5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago', 
			9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez');
	if($year) {
		if(date_format($_date, 'Y') == date('Y'))
			return $months[date_format($_date, 'n')];
		return $months[date_format($_date, 'n')] . ' ' . date_format($_date, 'Y');
	}
	return date_format($_date, 'j') . ' de ' . $months[date_format($_date, 'n')];
}

function same_date($date, $other) {
	return date('Y-m-d', $date) == date('Y-m-d', $other);
}

function human_range($inicio, $fim, $sep = '/') {
	if ($inicio === false && $fim === false) {
		return null;
	}
	$months = array(1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril', 
			5 => 'maio', 6 => 'junho', 7 => 'julho', 8 => 'agosto', 
			9 => 'setembro', 10 => 'outubro', 11 => 'novembro', 12 => 'dezembro');
	if ($inicio === false && $fim !== false) {
		return 'até dia '.date('j', $fim).' de '.$months[date('n', $fim)].' de '.date('Y', $fim);
	} elseif ($inicio !== false && $fim === false) {
		return 'a partir do dia '.date('j', $inicio).' de '.$months[date('n', $inicio)].' de '.date('Y', $inicio);
	} else {
		$start = strtotime('first day of this month', $inicio);
		$end = strtotime('last day of this month', $fim);
		$end_start = strtotime('first day of this month', $fim);
		if (same_date($inicio, $start) && same_date($fim, $end)) {
			if (same_date($inicio, $end_start)) {
				return $months[date('n', $inicio)].' de '.date('Y', $inicio);
			}
			if (date('Y', $inicio) == date('Y', $fim)) {
				return 'entre '.$months[date('n', $inicio)].' e '.$months[date('n', $fim)].' de '.date('Y', $inicio);
			}
		}
		return 'entre '.date('d'.$sep.'m'.$sep.'Y', $inicio).' e '.date('d'.$sep.'m'.$sep.'Y', $fim);
	}
}

/**
 *  Obtem da data de um arquivo no formato inteiro para ser usado como versão de um arquivo .css ou .js
 *  
 *  @param $file O caminho do arquivo.  deve ser um caminho absoluto ou seja iniciando com uma barra.
 */
function auto_version($file)
{
  if(strpos($file, '/') != 0 || !file_exists(WWW_ROOT . $file))
    return $file;
  return $file . '?' . filemtime(WWW_ROOT . $file);
}

function redirect($url = null) {
	if(is_null($url))
		$url = get_redirect_page();
	Utility::Redirect(WEB_ROOT . $url);
}

function get_redirect_page($default=null) {
	$redirect_page = Session::Get('redirect', true);
	if ($redirect_page)
		return $redirect_page;
	if ($default)
		return $default;
	return '/';
}

function set_redirect_page($url=null) {
	if(is_null($url))
		$url = $_SERVER['REQUEST_URI'];
	Session::Set('redirect', $url);
}

function template($tFile) {
	return __template($tFile);
}

function render($tFile, $vs=array()) {
	ob_start();
	foreach($GLOBALS AS $_k=>$_v) {
		${$_k} = $_v;
	}
	foreach($vs AS $_k=>$_v) {
		${$_k} = $_v;
	}
	include template($tFile);
	return render_hook(ob_get_clean());
}

function render_hook($c) {
	global $INI;

	$c = preg_replace('#href="/#i', 'href="'.$INI['system']['wwwprefix'].'/', $c);
	$c = preg_replace('#src="/#i', 'src="'.$INI['system']['wwwprefix'].'/', $c);
	$c = preg_replace('#action="/#i', 'action="'.$INI['system']['wwwprefix'].'/', $c);
	return $c;
}

function need_login($json = false) {
	if ( is_login() ) {
		if (is_post()) {
			unset($_SESSION['redirect']);
		}
		return $_SESSION['cliente_id'];
	}
	if($json) {
		json('Necessário autenticação');
	}
	Thunder::warning('Necessário autenticação, faça login para continuar');
	if ( is_get() ) {
		Session::Set('redirect', $_SERVER['REQUEST_URI']);
	} else {
		Session::Set('redirect', $_SERVER['HTTP_REFERER']);
	}
	redirect('/conta/entrar');
}

function need_manager($json = false) {
	need_login($json);
	if ( !is_manager() ) {
		if($json) {
			json('Necessário nível de funcionário');
		}
		Thunder::warning('Somente funcionários poderão acessar as páginas de gerenciamento');
		redirect('/');
	}
	return $_SESSION['cliente_id'];
}

function need_owner($json = false) {
	need_manager($json);
	if ( !is_owner() ) {
		if($json) {
			json('Necessário permissão de administrador');
		}
		Thunder::warning('Somente administradores poderão acessar essas páginas');
		redirect('/gerenciar/');
	}
	return $_SESSION['cliente_id'];
}

function need_permission($array, $json = false) {
	if ( !have_permission($array) ) {
		if($json) {
			json('Você não possui permissão para acessar essa função');
		}
		Thunder::warning('Você não possui permissão para acessar essa função');
		redirect('/gerenciar/');
	}
	return $_SESSION['cliente_id'];
}

function is_login() {
	global $login_cliente;
	return isset($_SESSION['cliente_id']) && !is_null($login_cliente->getID());
}

function is_manager($funcionario = null) {
	if(!is_null($funcionario))
		return !is_null($funcionario->getID());
	global $login_funcionario;
	return is_login() && !is_null($login_funcionario->getID());
}

function is_owner($funcionario = null) {
	if(!is_null($funcionario))
		return is_manager($funcionario) && $funcionario->getID() == 1;
	global $login_funcionario;
	return is_manager() && $login_funcionario->getID() == 1;
}

function is_self($funcionario) {
	global $login_funcionario;
	return is_manager() && !is_null($funcionario->getID()) && $funcionario->getID() == $login_funcionario->getID();
}

function is_get() {
	return !is_post();
}

function is_post() {
	return strtoupper($_SERVER['REQUEST_METHOD']) == 'POST';
}

function have_permission($array, $funcionario = null) {
	global $login_funcionario;
	global $__permissoes__;
	if(is_null($funcionario))
		$funcionario = $login_funcionario;
	if(!is_manager($funcionario))
		return false;
	if(is_owner($funcionario))
		return true;
	settype($array, 'array');
	$permissoes = $__permissoes__;
	if($funcionario->getID() != $login_funcionario->getID())
		$permissoes = ZAcesso::getPermissoes($funcionario->getID());
	$allow = true;
	$operator = '&&';
	foreach ($array as $value) {
		if(is_array($value)) {
			$operator = current($value);
			continue;
		}
		if($operator == '||') {
			$allow = $allow || in_array($value, $permissoes);
		} else {
			$allow = $allow && in_array($value, $permissoes);
		}
	}
	return ($allow && count($array) > 0);
}

function get_pages_info() {
	return array(
		'sobre' => 'Sobre a empresa',
		'privacidade' => 'Privacidade',
		'termos' => 'Termos de uso',
	);
}

function get_languages_info() {
	return array(
		'1046' => 'Português(Brasil)',
		'1033' => 'English(United States)',
		'1034' => 'Espanõl',
	);
}

function set_timezone_for($uf, $pais = 'Brasil')
{
	$timezones = array(
		'BR' => array(
			'AC' => 'America/Rio_branco',   'AL' => 'America/Maceio',
			'AP' => 'America/Belem',        'AM' => 'America/Manaus',
			'BA' => 'America/Bahia',        'CE' => 'America/Fortaleza',
			'DF' => 'America/Sao_Paulo',    'ES' => 'America/Sao_Paulo',
			'GO' => 'America/Sao_Paulo',    'MA' => 'America/Fortaleza',
			'MT' => 'America/Cuiaba',       'MS' => 'America/Campo_Grande',
			'MG' => 'America/Sao_Paulo',    'PR' => 'America/Sao_Paulo',
			'PB' => 'America/Fortaleza',    'PA' => 'America/Belem',
			'PE' => 'America/Recife',       'PI' => 'America/Fortaleza',
			'RJ' => 'America/Sao_Paulo',    'RN' => 'America/Fortaleza',
			'RS' => 'America/Sao_Paulo',    'RO' => 'America/Porto_Velho',
			'RR' => 'America/Boa_Vista',    'SC' => 'America/Sao_Paulo',
			'SE' => 'America/Maceio',       'SP' => 'America/Sao_Paulo',
			'TO' => 'America/Araguaia'
		)
	);
	$timezone = date_default_timezone_get();
	switch ($pais) {
		case 'BR':
		case 'BRA':
		case 'Brasil':
			if(isset($timezones['BR'][$uf]))
				$timezone = $timezones['BR'][$uf];
			break;
	}
	date_default_timezone_set($timezone);
}

function current_language_id() {
	global $login_funcionario;
	$lang_id = $login_funcionario->getLinguagemID();
	if(is_null($lang_id))
		$lang_id = 1046;
	return $lang_id;
}

function get_site_page($page_name) {
	$pagina = ZPagina::getPeloNomeLinguagemID($page_name, current_language_id());
	if(is_null($pagina->getID()))
		$pagina = ZPagina::getPeloNomeLinguagemID($page_name, 1046);
	return $pagina;
}


function is_boolean_config($section, $key, $default=false) {
	global $__options__;
	$value = $__options__[$section][$key];
	if(is_null($value))
		return $default;
	return intval($value) && 1;
}

function set_boolean_config($section, $key, $value) {
	global $__options__;
	if(is_null($value))
		unset($__options__[$section][$key]);
	else
		$__options__[$section][$key] = ($value?1:0);
}

function get_string_config($section, $key, $default=null) {
	global $__options__;
	$value = $__options__[$section][$key];
	if(is_null($value))
		return $default;
	return strval($value);
}

function set_string_config($section, $key, $value) {
	global $__options__;
	if(is_null($value))
		unset($__options__[$section][$key]);
	else
		$__options__[$section][$key] = $value;
}

function get_int_config($section, $key, $default=null) {
	global $__options__;
	$value = $__options__[$section][$key];
	if(is_null($value))
		return $default;
	return intval($value);
}

function set_int_config($section, $key, $value) {
	global $__options__;
	if(is_null($value))
		unset($__options__[$section][$key]);
	else
		$__options__[$section][$key] = intval($value);
}

function config_values_exists($array, $section, $key) {
	foreach ($array as $value) {
		if($value['section'] == $section && $value['key'] == $key)
			return true;
	}
	return false;
}

function register_device($device, $serial) {
	if(!isset($device) || trim(strval($device)) == '')
		throw new Exception("O nome do dispositivo não foi informado");
	if(!isset($serial) || trim(strval($serial)) == '')
		throw new Exception("O identificador do dispositivo não foi informado");
	$dispositivo = ZDispositivo::getPeloNome($device);
	if(is_null($dispositivo->getID()))
		$dispositivo = ZDispositivo::getPelaSerial($serial);
	global $__sistema__;
	if(is_null($__sistema__->getID()))
		throw new Exception("Não há dados na tabela do sistema");
	$tablet_count = ZDispositivo::getCountDoTablet();
	if(is_null($dispositivo->getID()) && $tablet_count >= $__sistema__->getTablets()) {
		// tenta sobrescrever um tablet não validado
		$dispositivo = ZDispositivo::getNaoValidado();
		if(is_null($dispositivo->getID()))
			throw new Exception("Limite de Tablets esgotado, verifique sua licença");
		// permite a atualização das informações para o novo dispositivo
		$dispositivo->setValidacao(null);
	}
	if(is_null($dispositivo->getID())) {
		$dispositivo->setTipo(DispositivoTipo::TABLET);
		$dispositivo->setDescricao('Tablet '.$device);
		$dispositivo->setNome($device);
		$dispositivo->setSerial($serial);
		$dispositivo->setOpcoes(0);
		$setor = ZSetor::getPeloNome('Vendas');
		if(is_null($setor->getID()))
			$setor = ZSetor::getPrimeiro();
		$dispositivo->setSetorID($setor->getID());
		$dispositivo = ZDispositivo::cadastrar($dispositivo);
		$appsync = new AppSync();
		$appsync->deviceAdded($dispositivo->getNome(), $dispositivo->getCaixaID());
	}
	if($dispositivo->getSerial() != $serial || $dispositivo->getNome() != $device) {
		// atualiza as informações do dispositivo
		$dispositivo->setNome($device);
		$dispositivo->setSerial($serial);
		$dispositivo = ZDispositivo::atualizar($dispositivo);
		$appsync = new AppSync();
		$appsync->deviceUpdated($dispositivo->getNome(), $dispositivo->getCaixaID());
	}
	return $dispositivo;
}

function get_aumento($anterior, $atual, $trunc) {
	if($anterior >= -0.005 && $anterior <= 0.005) {
		$value = 100.0;
	} else {
		$value = ($atual / $anterior - 1.0) * 100.0;
	}
	if($trunc)
		$value = (int)$value;
	return $value;
}

function get_percent($part, $total, $round) {
	if($total >= -0.005 && $total <= 0.005) {
		$value = 100.0;
	} else {
		$value = ($part / $total) * 100.0;
	}
	if($round)
		$value = round($value);
	return $value;
}

function get_tempo($seg) {
	$m = (int)($seg / 60);
	$min = $m % 60;
	$h = (int)($m / 60);
	return sprintf('%02d:%02dh', $h, $min);
}

function cookieset($k, $v, $expire=0) {
	$pre = substr(md5($_SERVER['HTTP_HOST']),0,4);
	$k = "{$pre}_{$k}";
	if ($expire==0) {
		$expire = time() + 365 * 86400;
	} else {
		$expire += time();
	}
	setCookie($k, $v, $expire, '/');
}

function cookieget($k, $default='') {
	$pre = substr(md5($_SERVER['HTTP_HOST']),0,4);
	$k = "{$pre}_{$k}";
	return isset($_COOKIE[$k]) ? strval($_COOKIE[$k]) : $default;
}

function json($tag, $data = null, $field = null) {
	header('Content-Type: application/json');
	if(is_null($tag))
		echo json_encode(array_merge(array('status'=> 'ok'), $data));
	else if(is_array($tag))
		echo json_encode($tag);
	else if(is_null($data) && !is_null($field))
		echo json_encode(array_merge(array('status'=> 'error', 'msg' => $tag), $field));
	else if(is_null($data))
		echo json_encode(array('status'=> 'error', 'msg' => $tag));
	else
		echo json_encode(array('status'=> 'ok', $tag => $data));
	exit;
}

function to_utf8($str) {
	return iconv('WINDOWS-1252', 'UTF-8', $str);
}

function split_camel_case($str) {
	$result = array();
	$len = strlen($str);
	$supr = strtoupper($str);
	$start = 0;
	$lc = 0;
	for ($i=0; $i < $len; $i++) { 
		if($str[$i] === $supr[$i] && $lc > 0) {
			$result[] = substr($str, $start, $i - $start);
			$start = $i;
			$lc = 0;
		} else if($str[$i] !== $supr[$i])
			$lc++;
	}
	if($start < $i - 1) {
		$result[] = substr($str, $start, $i - $start);
	}
	return $result;
}

function xchmod($path, $access = 0644) {
	$oldUmask = umask(0);
	chmod($path, $access);
	umask($oldUmask);
}

function xmkdir($dir, $access = 0711) {
	$oldUmask = umask(0);
	mkdir($dir, $access, true);
	chmod($dir, $access);
	umask($oldUmask);
}

// Gera um nome único para a imagem
function generate_file_name($dir, $ext, $name = null, $makedir = false) {
	if($makedir)
		xmkdir($dir, 0711);
	if(is_null($name) || file_exists($dir . $name)) {
		do {
			$name = md5(uniqid(time())) . $ext;
			$path = $dir . $name;
		} while(file_exists($path));
	}
	if(is_null($ext))
		return $dir . $name;
	return $name;
}

function upload_file($inputname, $dir, $name, $def_ext, $allow_ext, $force_ext = null, $error_msg = null) {
	$file = $_FILES[$inputname];
	if(!$file || $file['error'] === UPLOAD_ERR_NO_FILE)
		return null; // no file
	if($file['error'] !== UPLOAD_ERR_OK)
		throw new UploadException($file['error']);
	$ext = $def_ext;
	if(preg_match('/\.(' . $allow_ext . ')$/i', $file['name'], $matches)) {
		$ext = '.' . strtolower($matches[1]);
	}
	if(!is_null($force_ext))
		$ext = $force_ext;
	$name = generate_file_name($dir, $ext, $name, true);
	$path = $dir . $name;
	if(is_null($error_msg))
		$error_msg = 'Não foi possível salvar o arquivo no servidor';
	if(!move_uploaded_file($file['tmp_name'], $path))
		throw new Exception($error_msg . $path);
	xchmod($path, 0644);
	return $name;
}

function upload_image($inputname, $type, $name=null, $width=null, $height=null, $png=false) {
	$force_ext = null;
	if($png)
		$force_ext = '.png';
	$dir = IMG_ROOT . '/' . $type . '/';
	$name = upload_file($inputname, $dir, $name, '.jpg', 'gif|bmp|png|jpg|jpeg', $force_ext);
	if(is_null($name))
		return null;
	$file = $_FILES[$inputname];
	if (strpos($file['type'], 'image') !== 0)
		throw new Exception('O arquivo informado não é uma imagem');
	$path = $dir . $name;
	if(!Image::convert($path, $path, $width, $height)) {
		unlink($path);
		throw new Exception('Falha ao processar imagem');
	}
	xchmod($path, 0644);
	return $name;
}

function upload_document($inputname, $type, $name = null) {
	$dir = DOC_ROOT . '/' . $type . '/';
	return upload_file($inputname, $dir, $name, '.pdf', 
		'txt|doc|docx|xls|xlsx|csv|pptx|ppt|rtf|pps|ppsx|tex|ods|odt|odp|html|xml|pdf|gif|bmp|png|jpg|jpeg|pfx');
}

function create_zip($files = array(), $destination = '', $overwrite = false) {
	if(file_exists($destination) && !$overwrite)
		throw new Exception('O arquivo zip de destino já existe');
	$zip = new ZipArchive();
	if($zip->open($destination, $overwrite ? ZipArchive::OVERWRITE : ZipArchive::CREATE) !== true)
		throw new Exception('Não foi possível criar ou sobrescrever o arquivo zip');
	foreach($files as $name => $path) {
		$zip->addFile($path, $name);
	}
	$zip->close();
	xchmod($destination, 0644);
}

function get_file_url($root, $name, $namespace, $default = null) {
	if(is_null($name)) {
		if(is_null($default))
			return null;
		return $root . $default;
	}
	return $root . $namespace . '/' . $name;
}

function get_image_url($name, $namespace, $default = null) {
	return get_file_url('/static/img/', $name, $namespace, $default);
}

function get_document_url($name, $namespace, $default = null) {
	return get_file_url('/static/doc/', $name, $namespace, $default);
}

function is_local_path($name) {
	return strpos($name, '\\') !== false;
}

function get_site_image_url($key, $default = false) {
	switch ($key) {
		case 'Image.Header':
			$default_value = 'header-bg.jpg';
			break;
		case 'Image.Login':
			$default_value = 'map-image.png';
			break;
		case 'Image.Cadastrar':
			$default_value = 'map-image.png';
			break;
		case 'Image.Produtos':
			$default_value = 'product.jpg';
			break;
		case 'Image.Sobre':
			$default_value = 'about.jpg';
			break;
		case 'Image.Privacidade':
			$default_value = 'privacy.jpg';
			break;
		case 'Image.Termos':
			$default_value = 'agreement.jpg';
			break;
		case 'Image.Contato':
			$default_value = 'map-image.png';
			break;
		default:
			return null;
	}
	$value = get_string_config('Site', $key);
	if($default)
		$value = null;
	return get_image_url($value, 'header', $default_value);
}

function get_forma_pagto($type) {
	switch ($type) {
		case 'dinheiro':
			return 'Dinheiro';
		case 'cartao':
			return 'Cartão';
		case 'cheque':
			return 'Cheque';
		case 'conta':
			return 'Conta';
		case 'credito':
			return 'Crédito';
		case 'transferencia':
			return 'Transferência';
		default:
			return 'Dinheiro';
	}
}

function pagestring($count, $pagesize, $field = 'pagina') {
	$p = new Pager($count, $pagesize, $field);
	return array($pagesize, $p->offset, $p->genBasic());
}

function to_ini($array) {
	$res = array();
	foreach ($array as $key => $val) {
		if(is_array($val)) {
			$res[] = "[$key]";
			foreach ($val as $skey => $sval) {
				$res[] = "$skey=".$sval;
			}
		} else 
			$res[] = "$key=".$val;
	}
	return implode("\r\n", $res);
}

function mask($str, $mask) {
	if(empty($str))
		return null;
	$len = strlen($mask);
	$res = '';
	$j = 0;
	$opt = false;
	for ($i=0; $i < $len; $i++) {
		if($mask[$i] == '9' || $mask[$i] == '0')
			$res .= $str[$j++];
		else if($mask[$i] == '?')
			$opt = true;
		else
			$res .= $mask[$i];
	}
	return $res;
}

function unmask($str, $mask) {
	$len = strlen($mask);
	$res = '';
	$j = 0;
	$opt = false;
	for ($i=0; $i < $len; $i++) {
		if($mask[$i] == '9' || $mask[$i] == '0')
			$res .= $str[$j++];
		else if($mask[$i] == '?')
			$opt = true;
		else if($mask[$i] == $str[$j])
			$j++;
	}
	return $res;
}

function hexcolor($index, $css = false, $escape = false) {
	$colors = array(
		'FFA200', '00A03E', '24A8AC', '0087CB', '982395', /* Igaranti pallete */
		'28BE9B', '92DCE0', '609194', 'EF9950', 'D79C8C', /* Paw Studio */
		'B0A472', 'F5DF65', '2B9464', '59C8DF', 'D14D28', /* Mohiuddin Parekh */
	);
	$color = $colors[$index % count($colors)];
	if($css)
		$color = '#'.$color;
	if($escape)
		$color = '"'.$color.'"';
	return $color;
}

function single_quotes($string) {
	return str_replace('\'', '\\\'', $string);
}

function is_empty($var) {
	return empty($var);
}