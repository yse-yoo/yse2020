<?php
require_once 'setting.php';
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
if (isset($_GET['order'])) {
    $book->orders($_GET['order']);
}
$book->paginate();

require_once 'debug.php';
