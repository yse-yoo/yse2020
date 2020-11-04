<?php
require_once 'setting.php';
require_once 'Session.php';
require_once 'Auth.php';
require_once 'Book.php';
require_once 'Message.php';

Session::start();
Auth::checkLogin();

$book = new Book();
$book->loadRequest('book', 'form');
if ($book->validate()) {
    if ($book->insert($posts)->sql_error) {
        $_SESSION['success'] = '更新に失敗しました';
    } else {
        unset($_SESSION['book']);
        $_SESSION['success'] = '商品を入力しました';
        header('Location: zaiko_ichiran.php');
    }
} else {
    $error_message = '項目を入力してください';
    include('add_book.php');
    exit;
}

require_once 'debug.php';
