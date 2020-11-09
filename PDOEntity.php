<?php
class PDOEntity
{
    public $db_name = 'zaiko2020_yse';
    public $host = 'localhost';
    public $user_name = 'zaiko2020_yse';
    public $password = '2020zaiko';
    public $pdo;
    public $id;
    public $value;
    public $statement;
    public $conditions;
    public $sort_orders;
    public $limit = '';
    public $is_hide_soft_delete = false;
    public $delete_column = '';

    function __construct()
    {
        return $this->connect();
    }

    static function db()
    {
        return new Book();
    }

    function connect()
    {
        $dsn = "mysql:dbname={$this->db_name};host={$this->host};charset=utf8";
        try {
            $this->pdo = new PDO($dsn, $this->user_name, $this->password);
        } catch (PDOException $e) {
            exit;
        }
        return $this;
    }

    public function loadRequest($key, $session_name = null)
    {
        if (isset($_POST[$key])) {
            $this->value = $_POST[$key];
            Session::add($key, $this->value, $session_name);
        }
        if ($session_name) $this->value = Session::load($key, $session_name);
    }

    public function loadSession($key, $session_key = null)
    {
        $this->value = Session::load($key, $session_key);
    }

    public function buildSQL()
    {
        if ($this->conditions) $this->whereSQL();
        if ($this->sort_orders) $this->orderSQL();
        if ($this->limit) $this->limitSQL();
    }

    public function validate($posts = null)
    {
        if (!$posts) $posts = $this->value;
        foreach ($this->columns as $column => $values) {
            if (isset($values['require']) && $values['require']) {
                if (!isset($posts[$column]) || empty($posts[$column])) {
                    return false;
                }
            }
        }
        return true;
    }

    public function query()
    {
        $this->buildSQL();
        try {
            $this->statement = $this->pdo->query($this->sql);
            if (!$this->statement) exit($this->sql);
        } catch (PDOException $e) {
            var_dump($e);
            exit;
        }
    }

    public function insert($posts = null)
    {
        if (!$posts) $posts = $this->value;
        $values = [];
        $prepare_values = [];
        foreach ($this->columns as $column => $option) {
            $prepare_values[] = '?';
            $_value = $posts[$column];
            if ($option['type'] == 'date_string') {
                $_value = date('Y年m月d日', strtotime($_value));
            }
            $_value = "'{$_value}'";
            $values[] = $_value;
        }

        $column = implode(',', array_keys($this->columns));
        $value = implode(',', $values);

        $this->sql = "INSERT INTO {$this->table_name} ({$column}) values ({$value});";
        $result = $this->pdo->query($this->sql);
        if (!$result) {
            $this->sql_error = true;
            exit('insert error');
        }
        return $this;
    }

    public function all()
    {
        $this->selectSQL();
        $this->values = $this->getRows();
        return $this;
    }

    public function count($column = '*')
    {
        if ($this->is_hide_soft_delete) $this->where($this->delete_column, false);

        $count = 0;
        $select = "COUNT($column) AS count";
        $this->selectSQL($select);
        $results =  $this->getRow();
        if (isset($results['count'])) {
            $count = $results['count'];
        }
        return $count;
    }

    public function orders($params)
    {
        foreach ($params as $column => $order) {
            $this->order($column, $order);
        }
    }

    public function order($column, $order = 'asc')
    {
        $this->sort_orders[$column] = $order;
    }

    public function delete($id)
    {
        if (isset($this->delete_column)) {
            $this->sql = "UPDATE {$this->table_name} SET {$this->delete_column} = true WHERE id = {$id}";
        } else {
            $this->sql = "DELETE FROM {$this->table_name} WHERE id = {$id}";
        }
        $this->query();
        return $this;
    }

    public function deletes($ids)
    {
        $id = implode(',', $ids);
        $where = "WHERE id IN ({$id})";
        if (isset($this->delete_column)) {
            $this->sql = "UPDATE {$this->table_name} SET {$this->delete_column} = true {$where};";
        } else {
            $this->sql = "DELETE FROM {$this->table_name} {$where};";
        }
        $this->query();
        return $this;
    }

    public function getRows()
    {
        $this->query();
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRow()
    {
        $this->query();
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getQueryRow()
    {
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function where($key, $value, $eq = '=', $option = null)
    {
        if ($key) {
            $values['value'] = $value;
            $values['eq'] = $eq;
            $this->conditions[$key] = $values;
        }
        return $this;
    }

    public function whereIn($key, $values)
    {
        if ($key) {
            //TODO CAST
            $values['value'] = implode(',', $values);
            $values['eq'] = 'IN';
            $this->conditions[$key] = $values;
        }
        return $this;
    }

    public function like($key, $value, $start = '%', $end = '%')
    {
        if ($key) {
            $values['value'] = $value;
            $values['eq'] = 'LIKE';
            $values['start'] = $start;
            $values['end'] = $end;
            $this->conditions[$key] = $values;
        }
        return $this;
    }

    public function whereSQL()
    {
        if ($this->conditions && is_array($this->conditions)) {
            $sqls = [];
            foreach ($this->conditions as $column => $values) {
                $value = $values['value'];
                $eq = strtolower($values['eq']);
                if (is_bool($value)) {
                    $value = ($value) ? 'true' : 'false';
                } elseif ($eq == 'like') {
                    if (isset($values['start'])) $value = "{$values['start']}{$value}";
                    if (isset($values['end'])) $value = "{$value}{$values['end']}";
                }
                if ($eq == 'in') {
                    $value = "($value)";
                } else {
                    $value = "'{$value}'";
                }
                $sqls[] = "{$column} {$values['eq']} {$value}";
            }
            $where = implode(' AND ', $sqls);
            $this->sql .= " WHERE {$where}";
        }
    }

    public function limitSQL()
    {
        if ($this->limit > 0) {
            $this->sql .= " LIMIT {$this->limit}";
        }
        if ($this->offset > 0) {
            $this->sql .= " OFFSET {$this->offset}";
        }
    }

    public function selectSQL($select = '*')
    {
        $this->sql = "SELECT {$select} FROM {$this->table_name}";
    }

    public function orderSQL()
    {
        if (!$this->sort_orders) return;
        foreach ($this->sort_orders as $column => $sort_order) {
            $sort_order = strtoupper($sort_order);
            $values[] = "{$column} {$sort_order}";
        }
        $sort_string = implode(',', $values);
        $this->sql .= " ORDER BY {$sort_string}";
    }
}
