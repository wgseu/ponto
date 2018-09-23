<?php
class Session
{
    public static function Set($name, $v)
    {
        app()->getSession()->set($name, $v);
    }

    public static function Get($name, $once = false)
    {
        $value = app()->getSession()->get($name);
        if ($once && app()->getSession()->has($name)) {
            app()->getSession()->remove($name);
        }
        return $value;
    }
}
