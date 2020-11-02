<?php
require_once 'setting.php';
require_once 'Session.php';
require_once 'Auth.php';
require_once 'Book.php';
require_once 'Message.php';

Session::start();
Auth::checkLogin();

$book = new Book();

//TODO model
if (isset($_SESSION['book'])) $book->value = $_SESSION['book'];
if (isset($_POST['add'])) {
    $posts = $_SESSION['book'] = $_POST['book'];
    if ($book->validate($posts)) {
        $book->insert($posts);
        if ($book->sql_error) {
            $message = '更新に失敗しました';
        } else {
            unset($_SESSION['book']);
            $_SESSION['success'] = '商品を入力しました';
            header('Location: zaiko_ichiran.php');
        }
    } else {
        $error_message = '項目を入力してください';
        include('add.php');
        exit;
    }
} else {
    $book->nextId();
}

require_once 'debug.php';
