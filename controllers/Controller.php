<?php
require_once 'config/db.php'; 

use Config\DB;

try {
    $dbInstance = new DB();
    $pdo = $dbInstance->getPdo();
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$page = $_GET['page'] ?? 'home';

// ----------------------------------------------------------------
// PAGE : LISTE DES TEAMS
// ----------------------------------------------------------------
if ($page === 'teams') {
    
    // Récupère toutes les équipes
    $stmt = $pdo->query("SELECT * FROM teams");
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    include 'templates/teams.phtml';

// ----------------------------------------------------------------
// PAGE : DÉTAIL D'UNE TEAM + SES JOUEURS
// ----------------------------------------------------------------
} elseif ($page === 'team') {
    
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        
        // 1. Récupère l'équipe par son ID
        $stmt = $pdo->prepare("SELECT * FROM teams WHERE id = ?");
        $stmt->execute([$id]);
        $team = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // 2. Récupère les joueurs (colonne 'team' dans la table players)
        $stmtPlayers = $pdo->prepare("SELECT * FROM players WHERE team = ?");
        $stmtPlayers->execute([$id]);
        $players = $stmtPlayers->fetchAll(PDO::FETCH_ASSOC);
        
        include 'templates/team.phtml';
    } else {
        header('Location: index.php?page=teams');
        exit();
    }

// ----------------------------------------------------------------
// PAGE : LISTE DES JOUEURS
// ----------------------------------------------------------------
} elseif ($page === 'players') {
    
    $stmt = $pdo->query("SELECT * FROM players");
    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    include 'templates/players.phtml';

// ----------------------------------------------------------------
// PAGE : DÉTAIL D'UN JOUEUR
// ----------------------------------------------------------------
} elseif ($page === 'player') {
    
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        
        // 1. Récupère le joueur
        $stmt = $pdo->prepare("SELECT * FROM players WHERE id = ?");
        $stmt->execute([$id]);
        $player = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $teammates = [];
        // Si le joueur existe, on cherche ses coéquipiers (même team, id différent)
        if ($player && isset($player['team'])) {
            $stmtMates = $pdo->prepare("SELECT * FROM players WHERE team = ? AND id != ? LIMIT 2");
            $stmtMates->execute([$player['team'], $id]);
            $teammates = $stmtMates->fetchAll(PDO::FETCH_ASSOC);
        }

        include 'templates/player.phtml';
    } else {
        header('Location: index.php?page=players');
        exit();
    }

// ----------------------------------------------------------------
// PAGE : LISTE DES MATCHS 
// ----------------------------------------------------------------
} elseif ($page === 'matchs') {
    
    // CORRECTION : Table 'games' et colonne 'date'
    $stmt = $pdo->query("SELECT * FROM games ORDER BY date DESC");
    $matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    include 'templates/matchs.phtml';

// ----------------------------------------------------------------
// PAGE : DÉTAIL D'UN MATCH 
// ----------------------------------------------------------------
} elseif ($page === 'match') {
    
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        
        // CORRECTION : Table 'games'
        $stmt = $pdo->prepare("SELECT * FROM games WHERE id = ?");
        $stmt->execute([$id]);
        $match = $stmt->fetch(PDO::FETCH_ASSOC);
        
        include 'templates/match.phtml';
    } else {
        header('Location: index.php?page=matchs');
        exit();
    }

// ----------------------------------------------------------------
// PAGE : ACCUEIL (HOME)
// ----------------------------------------------------------------
} else {
    
    $stmtTeam = $pdo->query("SELECT * FROM teams ORDER BY RAND() LIMIT 1");
    $featuredTeam = $stmtTeam->fetch(PDO::FETCH_ASSOC);
    
    $featuredTeamPlayers = [];
    if ($featuredTeam) {
        $stmtTeamPlayers = $pdo->prepare("SELECT * FROM players WHERE team = ? LIMIT 3");
        $stmtTeamPlayers->execute([$featuredTeam['id']]);
        $featuredTeamPlayers = $stmtTeamPlayers->fetchAll(PDO::FETCH_ASSOC);
    }
    
    $stmtPlayers = $pdo->query("SELECT * FROM players ORDER BY RAND() LIMIT 3");
    $featuredPlayers = $stmtPlayers->fetchAll(PDO::FETCH_ASSOC);
    
    $stmtMatch = $pdo->query("SELECT * FROM games ORDER BY date DESC LIMIT 1");
    $lastMatch = $stmtMatch->fetch(PDO::FETCH_ASSOC);
    
    include 'templates/home.phtml';
}
?>