<?php

function configure_keys()
{
    return array(
        'db',
        'system',
    );
}

function configure_save($key = null)
{
    global $INI;
    if ($key && isset($INN[$key])) {
        return _configure_save($key, $INI[$key]);
    }
    $keys = configure_keys();
    foreach ($keys as $one) {
        if (isset($INI[$one])) {
            _configure_save($one, $INI[$one]);
        }
    }
}

function _configure_save($key, $value)
{
    if (!key) {
        return;
    }
    $php = DIR_CONFIGURE . '/' . $key . '.php';
    $v = "<?php\r\n\$value = ";
    $v .= var_export($value, true);
    $v .=";\r\n?>";
    return file_put_contents($php, $v);
}

function configure_load()
{
    global $INI;
    $keys = configure_keys();
    foreach ($keys as $one) {
        $INI[$one] = _configure_load($one);
    }
    return $INI;
}

function _configure_load($key = null)
{
    if (!$key) {
        return null;
    }
    $php = DIR_CONFIGURE . '/' . $key . '.php';
    if (file_exists($php)) {
        require_once($php);
    }
    return $value;
}