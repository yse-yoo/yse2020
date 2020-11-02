<?php
require_once 'PDOEntity.php';

class Book extends PDOEntity
{
    public $current_limit = 20;
    public $current_offset = 0;
    public $current_count = 0;
    public $table_name = 'books';

    public $columns = [
        'title' => ['type' => 'varchar'],
        'author' => ['type' => 'varchar'],
        'salesDate' => ['type' => 'date_string'],
        'isbn' => ['type' => 'varchar'],
        'price' => ['type' => 'varchar'],
        'stock' => ['type' => 'varchar'],
    ];

    public function nextId()
    {
        $this->sql = "SELECT max(id) + 1 as id FROM books;";
        $result = $this->getRow();
        $this->next_id = $result['id'];
        return $this;
    }

    public function search()
    {
        if (isset($_GET['search'])) {
            $_SESSION['zaiko']['search'] = $_GET['search'];
        }
        if (isset($_SESSION['zaiko']['search'])) {
            $this->like('title', $_SESSION['zaiko']['search']);
        }
        return $this;
    }

    public function getOne($id)
    {
        $this->sql = "SELECT * FROM books WHERE id = {$id};";
        $this->query();
        if (!$this->statement) exit($this->sql);
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getList($conditions = [])
    {
        $this->sql = "SELECT * FROM books";
        $this->query();
        if (!$this->statement) exit($this->sql);
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryList()
    {
        $this->sql = "SELECT * FROM books";
        $this->limit($this->current_limit)->offset($this->current_offset)->query();
        return $this;
    }

    public function getCount()
    {
        $this->sql = "SELECT count(id) AS count FROM books";
        $results = $this->getRow();
        if (isset($results['count'])) $this->current_count = $results['count'];
        return $this->current_count;
    }

    function getTotalPageCount()
    {
        $this->total_page_count = (int) ceil($this->current_count / $this->current_limit);
        return $this->total_page_count;
    }

    function getCurrentPage()
    {
        $this->current_page = (isset($_SESSION['zaiko']['page'])) ? $_SESSION['zaiko']['page'] : 1;
        if (isset($_GET['page']) && $_GET['page'] > 0) {
            $this->current_page = $_SESSION['zaiko']['page'] = $_GET['page'];
        }
        return $this->current_page;
    }

    function getOffset()
    {
        $this->current_offset = ($this->current_page - 1) * $this->current_limit;
        return $this->current_offset;
    }

    function paginate()
    {
        $this->search();
        $this->current_count = $this->getCount();
        $this->current_page = $this->getCurrentPage();
        $this->current_offset = $this->getOffset();
        $this->total_page_count = $this->getTotalPageCount();

        if ($this->total_page_count > 10) {
            $this->pages = range($this->current_page, $this->current_page + 10);
        } else {
            $max_page_count = $this->current_page + $this->total_page_count;
            if ($max_page_count > $this->current_count) {
                $max_page_count = $this->current_count;
            }
            $this->pages = range($this->current_page, $max_page_count);
        }

        $this->statement = null;
        $this->queryList();
        return $this;
    }

    function fetch($id)
    {
        $this->value = $this->getOne($id);
        return $this;
    }

    function updateStock($id, $stock)
    {
        if ($id > 0 && $stock > 0 && $stock <= 100) {
            $this->sql = "UPDATE books SET stock = {$stock} WHERE id = {$id}";
            $this->pdo->query($this->sql);
        }
        return $this;
    }
}
