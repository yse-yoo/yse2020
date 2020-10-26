<?php
require_once 'PDOEntity.php';

class Auth extends PDOEntity
{

    public static function checkLogin()
    {
        if (!$_SESSION['login']) {
            $_SESSION['error2'] = 'ログインしてください';
            header('location: login.php');
            exit;
        }
    }
}
