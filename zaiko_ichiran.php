<?php
require_once 'ZaikoService.php';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<title>書籍一覧</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
	<link rel="stylesheet" href="css/style.css" type="text/css" />
</head>

<body>
	<div id="header">
		<h1>書籍一覧</h1>
	</div>
	<div class="search form">
		<form action="zaiko_ichiran.php" method="get">
			<input type="text" name="search" value="<?= @$_SESSION['zaiko']['search'] ?>">
			<button class="btn info">検索</button>
			<a href="?reset=1" class="btn info">リセット</a>
		</form>
	</div>
	<form action="zaiko_ichiran.php" method="post" id="myform" name="myform">
		<div id="pagebody">
			<div id="error">
				<?= Message::show('success'); ?>
			</div>

			<div id="left">
				<p id="ninsyou_ippan">
					<?= Session::show('account_name') ?><br>
					<button type="button" id="logout" onclick="location.href='logout.php'">ログアウト</button>
				</p>
				<button type="submit" id="btn1" formmethod="POST" name="decision" value="3" formaction="nyuka.php">入荷</button>

				<button type="submit" id="btn1" formmethod="POST" name="decision" value="4" formaction="syukka.php">出荷</button>

				<?php include('manage_btn.php') ?>
			</div>
			<div id="center">
				<table>
					<thead>
						<tr>
							<th id="check"></th>
							<th id="id">ID</th>
							<th id="book_name">書籍名</th>
							<th id="author">著者名</th>
							<th id="salesDate">発売日</th>
							<th id="itemPrice">金額</th>
							<th id="stock">在庫数</th>
							<th id="is_delete">削除フラグ</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($extract = $book->getQueryRow()) : ?>
							<tr>
								<td><input type="checkbox" name="books[]" value="<?= $extract['id'] ?>"></td>
								<td><?= $extract['id'] ?></td>
								<td><?= $extract['title'] ?></td>
								<td><?= $extract['author'] ?></td>
								<td><?= $extract['salesDate'] ?></td>
								<td><?= $extract['price'] ?></td>
								<td><?= $extract['stock'] ?></td>
								<td><?= $extract['is_delete'] ?></td>
							</tr>
						<?php endwhile ?>
					</tbody>
				</table>

				<?php include('paginate.php'); ?>
			</div>
		</div>
	</form>
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>

</html>