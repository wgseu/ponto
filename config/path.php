<?php

$value = [];
$value['public'] = dirname(__DIR__) . '/public';
$value['static'] = $value['public'] . '/static';
$value['image']  = $value['static'] . '/img';
$value['docs']  = $value['static'] . '/doc';
$value['template'] = dirname(__DIR__) . '/resources/views';
$value['compiled'] = dirname(__DIR__) . '/storage/app/compiled';
$value['logs'] = dirname(__DIR__) . '/storage/logs';
$value['certs'] = dirname(__DIR__) . '/storage/certs';
$value['cache'] = dirname(__DIR__) . '/storage/app/cache';
$value['routes'] = dirname(__DIR__) . '/routes';
$value['pages'] = dirname(__DIR__) . '/app/pages';
