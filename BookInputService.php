<?php
require_once 'setting.php';
require_once 'Session.php';
require_once 'Auth.php';
require_once 'Book.php';
require_once 'Message.php';

Session::start();
Auth::checkLogin();

$book = new Book();
$book->loadSession('book', 'form');
$book->nextId();

require_once 'debug.php';
