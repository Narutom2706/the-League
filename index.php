<?php

require_once 'config/autoload.php';
require_once 'config/db.php';

use Config\DB;

// Connexion à la base de données
$db = new DB();
$pdo = $db->getPdo();

// ============ TEAMS ============
$queryTeams = $pdo->prepare('
    SELECT t.*, m.url AS logo_url, m.alt AS logo_alt
    FROM teams t
    LEFT JOIN media m ON t.logo = m.id
');
$queryTeams->execute();
$teams = $queryTeams->fetchAll(PDO::FETCH_ASSOC);

// Team à la une (première team)
$featuredTeam = $teams[0] ?? null;

// ============ PLAYERS ============
$queryPlayers = $pdo->prepare('
    SELECT p.*, m.url AS portrait_url, m.alt AS portrait_alt, t.name AS team_name, tm.url AS team_logo
    FROM players p
    LEFT JOIN media m ON p.portrait = m.id
    LEFT JOIN teams t ON p.team = t.id
    LEFT JOIN media tm ON t.logo = tm.id
');
$queryPlayers->execute();
$players = $queryPlayers->fetchAll(PDO::FETCH_ASSOC);

// Players à la une (3 premiers)
$featuredPlayers = array_slice($players, 0, 3);

// ============ GAMES / MATCHS ============
$queryGames = $pdo->prepare('
    SELECT g.*,
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
    ORDER BY g.date DESC
');
$queryGames->execute();
$games = $queryGames->fetchAll(PDO::FETCH_ASSOC);

// Dernier match (le plus récent)
$lastGame = $games[0] ?? null;

// ============ PERFORMANCES ============
$queryPerformances = $pdo->prepare('
    SELECT pp.*, p.nickname, p.id AS player_id, g.name AS game_name, g.id AS game_id
    FROM player_performance pp
    LEFT JOIN players p ON pp.player = p.id
    LEFT JOIN games g ON pp.game = g.id
');
$queryPerformances->execute();
$performances = $queryPerformances->fetchAll(PDO::FETCH_ASSOC);

// ============ MEDIAS ============
$queryMedia = $pdo->prepare('SELECT * FROM media');
$queryMedia->execute();
$medias = $queryMedia->fetchAll(PDO::FETCH_ASSOC);

// ============ ROUTING ============
$page = $_GET['page'] ?? 'index';

switch ($page) {
    case 'teams':
        require 'templates/teams.phtml';
        break;
    case 'players':
        require 'templates/players.phtml';
        break;
    case 'matchs':
        require 'templates/matchs.phtml';
        break;
    case 'index':
    default:
        require 'templates/index.phtml';
        break;
}
