<?php
require_once 'Entity.php';

class Book extends Entity
{
    public $limit = 20;
    public $offset = 0;

    function getBookQuery()
    {
        $sql = "SELECT * FROM books LIMIT {$this->limit} OFFSET {$this->offset};";
        $query = $this->pdo->query($sql);
        if (!$query) exit($sql);
        return $query;
    }

    function getCount()
    {
        $sql = "SELECT count(id) AS count FROM books;";
        $query = $this->pdo->query($sql);
        if (!$query) exit($sql);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $this->count = (int) $result['count'];
        return $this->count;
    }

    function getTotalPageCount()
    {
        $this->total_page_count = (int) ceil($this->count / $this->limit);
    }

    function getCurrentPage()
    {
        if (isset($_GET['reset'])) unset($_SESSION['zaiko']['page']);
        $this->current_page = (isset($_SESSION['zaiko']['page'])) ? $_SESSION['zaiko']['page'] : 1;
        if (isset($_GET['page']) && $_GET['page'] > 0) {
            $this->current_page = $_SESSION['zaiko']['page'] = $_GET['page'];
        }
        return $this->current_page;
    }

    function getOffset()
    {
        $this->offset = ($this->current_page - 1) * $this->limit;
        return $this->offset;
    }

    function Paginate()
    {
        $this->count = $this->getCount();
        $this->current_page = $this->getCurrentPage();
        $this->offset = $this->getOffset();
        $this->total_page_count = $this->getTotalPageCount();
        $this->pages = range($this->current_page, $this->current_page + 10);
        return $this;
    }
}