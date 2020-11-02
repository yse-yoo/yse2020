<?php
include('BookDeleteService.php');
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>商品削除</title>
    <link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>

<body>
    <!-- ヘッダ -->
    <div id="header">
        <h1>商品削除</h1>
    </div>

    <!-- メニュー -->
    <div id="menu">
        <nav>
            <ul>
                <li><a href="zaiko_ichiran.php?page=1">書籍一覧</a></li>
            </ul>
        </nav>
    </div>

    <form action="delete.php" method="post">
        <div id="pagebody">
            <div id="center">
                <table>
                    <thead>
                        <tr>
                            <th id="id">ID</th>
                            <th id="book_name">書籍名</th>
                            <th id="author">著者名</th>
                            <th id="salesDate">発売日</th>
                            <th id="itemPrice">金額(円)</th>
                            <th id="stock">在庫数</th>
                        </tr>
                    </thead>

                    <?php foreach ($book->values as $book->value) : ?>
                        <tr>
                            <td>
                                <?= $book->value['id'] ?>
                                <input type="hidden" name="books[]" value="<?= $book->value['id'] ?>">
                            </td>
                            <td><?= $book->value['title'] ?></td>
                            <td><?= $book->value['author'] ?></td>
                            <td><?= $book->value['salesDate'] ?></td>
                            <td><?= $book->value['price'] ?></td>
                            <td><?= $book->value['stock'] ?></td>
                        </tr>
                    <?php endforeach ?>
                </table>
                <button type="submit" id="kakutei" formmethod="POST" name="is_delete" value="1">確定</button>
            </div>
        </div>
    </form>
    <!-- フッター -->
    <div id="footer">
        <footer>株式会社アクロイト</footer>
    </div>
</body>

</html>