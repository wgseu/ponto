<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');

echo date('Y-m-d H:i:s') . ': '. $__estado__->getUF() .', '.$__pais__->getSigla().' - ' . date_default_timezone_get();
