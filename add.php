<?php
require_once 'BookAddService.php';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>入荷</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>

<body>
	<div id="header">
		<h1>商品追加</h1>
	</div>

	<div id="menu">
		<nav>
			<ul>
				<li><a href="zaiko_ichiran.php?page=1">書籍一覧</a></li>
			</ul>
		</nav>
	</div>

	<form action="add.php" method="post">
		<div id="pagebody">
			<div id="error">
				<?= @$error_message ?>
			</div>
			<div id="center">
				<table>
					<thead>
						<tr>
							<th id="id">ID</th>
							<th id="isbn">ISBN</th>
							<th id="book_name">書籍名</th>
							<th id="author">著者名</th>
							<th id="salesDate">発売日</th>
							<th id="itemPrice">金額(円)</th>
							<th id="stock">在庫数</th>
							<th id="">入荷数</th>
						</tr>
					</thead>
					<tr>
						<td><?= @$book->next_id ?></td>
						<td><input type="text" name="book[isbn]" value="<?= @$book->value['isbn'] ?>"></td>
						<td><input type="text" name="book[title]" value="<?= @$book->value['title'] ?>"></td>
						<td><input type="text" name="book[author]" value="<?= @$book->value['author'] ?>"></td>
						<td><input type="date" name="book[salesDate]" value="<?= @$book->value['salesDate'] ?>"></td>
						<td><input type="number" min="0" name="book[price]" value="<?= @$book->value['price'] ?>"></td>
						<td>0</td>
						<td><input type="number" min="0" name="book[stock]" value="<?= @$book->value['stock'] ?>"></td>
					</tr>
				</table>
				<button id="kakutei" name="add" value="1">確定</button>
			</div>
		</div>
	</form>
	<!-- フッター -->
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>

</html>