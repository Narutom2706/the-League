<?php
namespace Managers;

use Config\DB;
use Models\Player;
use PDO;

class PlayerManager {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = (new DB())->getPdo();
    }

    public function findAll(): array {
        $sql = 'SELECT p.*, m.url AS portrait_url, m.alt AS portrait_alt, 
                       t.name AS team_name, tm.url AS team_logo
                FROM players p
                LEFT JOIN media m ON p.portrait = m.id
                LEFT JOIN teams t ON p.team = t.id
                LEFT JOIN media tm ON t.logo = tm.id';
        
        $query = $this->pdo->query($sql);
        $query->setFetchMode(PDO::FETCH_CLASS, Player::class);
        return $query->fetchAll();
    }

    public function findByTeamId(int $teamId): array {
        $sql = 'SELECT p.*, m.url AS portrait_url 
                FROM players p
                LEFT JOIN media m ON p.portrait = m.id
                WHERE p.team = :teamId
                LIMIT 3';
        
        $query = $this->pdo->prepare($sql);
        $query->execute([':teamId' => $teamId]);
        $query->setFetchMode(PDO::FETCH_CLASS, Player::class);
        return $query->fetchAll();
    }
}