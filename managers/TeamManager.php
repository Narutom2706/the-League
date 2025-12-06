<?php
namespace Managers;

use Config\DB;
use Models\Team;
use PDO;

class TeamManager {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = (new DB())->getPdo();
    }

    public function findAll(): array {
        $sql = 'SELECT t.*, m.url AS logo_url, m.alt AS logo_alt
                FROM teams t
                LEFT JOIN media m ON t.logo = m.id';
        
        $query = $this->pdo->query($sql);
        $query->setFetchMode(PDO::FETCH_CLASS, Team::class);
        return $query->fetchAll();
    }
}