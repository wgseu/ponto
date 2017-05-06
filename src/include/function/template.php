<?php
/**
 * @author: admin@wroupon.com
 */
if(!defined('DIR_COMPILED'))
	define('DIR_COMPILED','/tmp');
if(!defined('DIR_TEMPLATE'))
	define('DIR_TEMPLATE','/tmp');

function __parseecho($matches) {
	return __replace("<?php echo $matches[1]; ?>");
}

function __parsestmt($matches) {
	return __replace("<?php $matches[1]; ?>");
}

function __parseelseif($matches) {
	return __replace("<?php } else if($matches[1]) { ?>");
}

function __parseloop($matches) {
	return __replace("<?php if(is_array($matches[1])){foreach($matches[1] AS $matches[2]) { ?>$matches[3]<?php }} ?>");
}

function __parseloopkeyval($matches) {
	return __replace("<?php if(is_array($matches[1])){foreach($matches[1] AS $matches[2]=>$matches[3]) { ?>$matches[4]<?php }} ?>");
}

function __parseif($matches) {
	return __replace("<?php if($matches[1]) { ?>$matches[2]<?php } ?>");
}

function __parse($tFile,$cFile) {
	$fileContent = false;
	if(($fileContent = file_get_contents($tFile)) === false) {
		die("Can't get file contents [$tFile]!");
		return false;
	}
	$fileContent = preg_replace('/^(\xef\xbb\xbf)/', '', $fileContent ); //EFBBBF
	$fileContent = preg_replace_callback("/\<\!\-\-\s*\\\$\{(.+?)\}\s*\-\-\>/is", '__parsestmt', $fileContent);
	$fileContent = preg_replace("/\{(\\\$[a-zA-Z0-9_\[\]\\\ \-\'\,\%\*\/\.\(\)\>\'\"\$\x7f-\xff]+)\}/s", "<?php echo \\1; ?>", $fileContent);
	$fileContent = preg_replace_callback("/\\\$\{(.+?)\}/is", '__parseecho', $fileContent);
	$fileContent = preg_replace_callback("/\<\!\-\-\s*\{else\s*if\s+(.+?)\}\s*\-\-\>/is", '__parseelseif', $fileContent);
	$fileContent = preg_replace_callback("/\<\!\-\-\s*\{elif\s+(.+?)\}\s*\-\-\>/is", '__parseelseif', $fileContent);
	$fileContent = preg_replace("/\<\!\-\-\s*\{else\}\s*\-\-\>/is", '<?php } else { ?>', $fileContent);
	for($i = 0; $i < 5; ++$i) {
		$fileContent = preg_replace_callback("/\<\!\-\-\s*\{loop\s+(\S+)\s+(\S+)\s+(\S+)\s*\}\s*\-\-\>(.+?)\<\!\-\-\s*\{\/loop\}\s*\-\-\>/is", '__parseloopkeyval', $fileContent);
		$fileContent = preg_replace_callback("/\<\!\-\-\s*\{loop\s+(\S+)\s+(\S+)\s*\}\s*\-\-\>(.+?)\<\!\-\-\s*\{\/loop\}\s*\-\-\>/is", '__parseloop', $fileContent);
		$fileContent = preg_replace_callback("/\<\!\-\-\s*\{if\s+(.+?)\}\s*\-\-\>(.+?)\<\!\-\-\s*\{\/if\}\s*\-\-\>/is", '__parseif', $fileContent);
	}
	//Add for call <!--{include othertpl}-->
	$fileContent = preg_replace("#<!--\s*{\s*include\s+([^\{\}]+)\s*\}\s*-->#i", '<?php include template("\\1"); ?>', $fileContent);
	//Add value namespace
	if(file_put_contents($cFile,$fileContent) === false) {
		die("Can't write file [$cFile]!");
		return false;
	}
	return true;
}

function __replace($string) {
	return str_replace('\"', '"', $string);
}

function __template($tFile) {
	$tFileN = preg_replace( '/\.html$/', '', $tFile);
	$tFile = DIR_TEMPLATE . '/' . $tFileN . '.html';
	$cFile = DIR_COMPILED . '/' . str_replace('/','_',$tFileN) . '.php';
	if(false === file_exists($tFile)){
		die("Template file [$tFile] not found!");
	}
	if(false === file_exists($cFile) || @filemtime($tFile) > @filemtime($cFile)) {
		__parse($tFile,$cFile);
	}
	return $cFile;
}
