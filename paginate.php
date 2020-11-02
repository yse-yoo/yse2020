<?php if (isset($book->pages)) : ?>
    <div id=" paginate">
        <?= $book->current_count ?>件
        <?= $book->total_page_count ?>ページ
        [<a href="?page=1">最初</a>]
        <?php if ($book->current_page > 1) : ?>
            [<a href="?page=<?= $book->current_page - 1 ?>">前へ</a>]
        <?php endif ?>
        <?php foreach ($book->pages as $page) : ?>
            [<a href="?page=<?= $page ?>"><?= $page ?></a>]
        <?php endforeach ?>
        <?php if ($book->current_page < $book->total_page_count) : ?>
            [<a href="?page=<?= $book->current_page + 1 ?>">次へ</a>]
        <?php endif ?>
        [<a href="?page=<?= $book->total_page_count ?>">最後</a>]
        [<a href="?reset=1">リセット</a>]
    </div>
<?php endif ?>