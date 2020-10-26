<?php
require_once 'Session.php';
require_once 'Auth.php';
require_once 'Book.php';
require_once 'Message.php';

Session::start();
Auth::checkLogin();

//TODO
if (isset($_GET['reset'])) {
    unset($_SESSION['zaiko']['page']);
    unset($_SESSION['zaiko']['search']);
}

$book = new Book();
$book->paginate();
