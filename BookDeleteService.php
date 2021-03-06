<?php
require_once 'setting.php';
require_once 'Session.php';
require_once 'Auth.php';
require_once 'Book.php';
require_once 'Message.php';

Session::start();
Auth::checkLogin();

$error_message = '';

if (!isset($_POST['books'])) {
    $_SESSION['success'] = '削除する商品をチェックしてください';
    header('Location: zaiko_ichiran.php');
} else {
    $book = new Book();
    if (isset($_POST['is_delete'])) {
        $book->deletes($_POST['books']);
        header('Location: zaiko_ichiran.php');
    } else {
        $book->whereIn('id', $_POST['books'])->all();
        $error_message = $book->checkStockForDelete();
    }
}

require_once 'debug.php';
