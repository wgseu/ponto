<?php

$value = [];
$value['public'] = dirname(dirname(dirname(__DIR__))) . '/public';
$value['static'] = $value['public'] . '/static';
$value['image']  = $value['static'] . '/img';
$value['docs']  = $value['static'] . '/doc';
$value['template'] = dirname(__DIR__) . '/template';
$value['compiled'] = dirname(__DIR__) . '/compiled';
$value['logs'] = dirname(__DIR__) . '/logs';
$value['certs'] = dirname(__DIR__) . '/certs';
