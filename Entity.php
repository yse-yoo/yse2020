<?php
class Entity
{
    public $db_name = 'zaiko2020_yse';
    public $host = 'localhost';
    public $user_name = 'zaiko2020_yse';
    public $password = '2020zaiko';
    public $pdo;

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
}
