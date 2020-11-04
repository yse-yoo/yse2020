<?php
require_once 'PDOEntity.php';

class Book extends PDOEntity
{
    public $current_limit = 20;
    public $current_offset = 0;
    public $current_count = 0;
    public $table_name = 'books';
    public $is_hide_soft_delete = true;
    public $delete_column = 'is_delete';

    public $columns = [
        'title' => ['type' => 'varchar', 'require' => true],
        'author' => ['type' => 'varchar', 'require' => true],
        'salesDate' => ['type' => 'date_string', 'require' => true],
        'isbn' => ['type' => 'varchar', 'require' => true],
        'price' => ['type' => 'varchar', 'require' => true],
        'stock' => ['type' => 'varchar'],
    ];

    public function checkStockForDelete()
    {
        if (!$this->values) return;
        foreach ($this->values as $value) {
            if ($value['stock'] > 0) {
                return '在庫がある商品があります。削除しますか？';
            }
        }
    }
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
        if (isset($_SESSION['zaiko']['search']) && $_SESSION['zaiko']['search']) {
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
        if (isset($this->delete_column) && $this->is_hide_soft_delete) {
            $this->where($this->delete_column, 0);
        }
        $this->limit($this->current_limit)->offset($this->current_offset)->query();
        return $this;
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
        $this->current_count = $this->count();
        $this->current_page = $this->getCurrentPage();
        $this->current_offset = $this->getOffset();
        $this->total_page_count = $this->getTotalPageCount();

        $start = 1;
        $end = $this->total_page_count;
        if ($this->total_page_count > 10) {
            if ($this->current_page + 10 > $this->total_page_count) {
                $end = $this->total_page_count;
                $start = $end - 10;
            } else {
                $start = $this->current_page;
                $end = $start + 10;
            }
        }
        $this->pages = range($start, $end);

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
