<?php
namespace Managers;

use Config\DB;
use Models\Game; // On garde le Model "Game" car la table SQL est "games"
use PDO;

class MatchManager {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = (new DB())->getPdo();
    }

    public function findAll(): array {
        $sql = 'SELECT g.*,
                       t1.name AS team_1_name,
                       t2.name AS team_2_name,
                       tw.name AS winner_name,
                       m1.url AS team_1_logo,
                       m1.alt AS team_1_logo_alt,
                       m2.url AS team_2_logo,
                       m2.alt AS team_2_logo_alt
                FROM games g
                LEFT JOIN teams t1 ON g.team_1 = t1.id
                LEFT JOIN teams t2 ON g.team_2 = t2.id
                LEFT JOIN teams tw ON g.winner = tw.id
                LEFT JOIN media m1 ON t1.logo = m1.id
                LEFT JOIN media m2 ON t2.logo = m2.id
                ORDER BY g.date DESC';

        $query = $this->pdo->query($sql);
        // On mappe les résultats sur la classe Models\Game
        $query->setFetchMode(PDO::FETCH_CLASS, Game::class);
        return $query->fetchAll();
    }
}