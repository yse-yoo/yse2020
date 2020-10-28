<?php if (isset($book->pages)) : ?>
    <div id=" paginate">
        [<a href="?page=1">最初</a>]
        <?php foreach ($book->pages as $page) : ?>
            [<a href="?page=<?= $page ?>"><?= $page ?></a>]
        <?php endforeach ?>
        [<a href="?page=<?= $book->current_page + 1 ?>">次へ</a>]
        <?= $book->total_page_count ?>
        <? if (($page + 1) < count($book->total_page_count)): ?>
        <? endif ?>
        [<a href="?page=<?= $book->total_page_count ?>">最後</a>]
        [<a href="?reset=1">リセット</a>]
    </div>
<?php endif ?>