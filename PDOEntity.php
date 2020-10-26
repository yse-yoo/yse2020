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
    public $limit = '';

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

    public function buildSQL()
    {
        if ($this->conditions) $this->whereSQL();
        if ($this->limit) $this->limitSQL();
    }

    public function query()
    {
        $this->buildSQL();
        // $this->sql .= ';';
        try {
            $this->statement = $this->pdo->query($this->sql);
            if (!$this->statement) exit($this->sql);
        } catch (PDOException $e) {
            var_dump($e);
            exit;
        }
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
            foreach ($this->conditions as $column => $values) {
                $value = $values['value'];
                if (isset($values['start'])) $value = "{$values['start']}{$value}";
                if (isset($values['end'])) $value = "{$value}{$values['end']}";
                if (is_bool($values['value'])) {
                } else {
                    $value = "'{$value}'";
                }
                $sql = "{$column} {$values['eq']} {$value}";
            }
            $this->sql .= " WHERE {$sql}";
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
}
