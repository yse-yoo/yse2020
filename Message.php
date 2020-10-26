<?php
require_once 'Session.php';

class Message
{

    function login()
    {
    }

    public static function show($key)
    {
        return Session::show($key);
    }
}
