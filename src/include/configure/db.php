<?php

$content = file_get_contents(dirname(dirname(dirname(__DIR__))) . '/.env');
# remove comments
$content = preg_replace('/[ \t]*#[^\n]*\n/', '', $content);
$env  = parse_ini_string($content, true, INI_SCANNER_RAW);

$value = array (
	'host' => $env['MYSQL_HOST'],
	'port' => $env['MYSQL_PORT'],
	'user' => $env['MYSQL_USER'],
	'pass' => $env['MYSQL_PASSWORD'],
	'name' => $env['MYSQL_DATABASE']
);
