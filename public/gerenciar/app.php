<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_manager(is_output('json'));

/* not allow access app.php */
$script_filename = str_replace('\\', '/', __FILE__);
if ($_SERVER['SCRIPT_FILENAME'] == $script_filename) {
    redirect('/gerenciar/');
}
/* end */
