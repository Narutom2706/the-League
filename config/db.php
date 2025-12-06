<?php
namespace Config;

use PDO;

class DB {
    private string $host = "localhost";
    private string $port = "3306";
    private string $dbname = "the-league";
    private string $user = "root";
    private string $password = "";
    private $pdo;

    public function __construct() {
        $connexionString = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8";
        $this->pdo = new PDO($connexionString, $this->user, $this->password);
    }

    public function getPdo(): PDO {
        return $this->pdo;
    }
}