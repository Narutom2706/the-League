<?php
require_once 'config/db.php'; 

use Config\DB;

try {
    $dbInstance = new DB();
    $pdo = $dbInstance->getPdo();
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération de la page par défaut
$page = $_GET['page'] ?? 'home';

// --- PAGE : LISTE DES TEAMS -------------------------------------
if ($page === 'teams') {
    
    $stmt = $pdo->query("
        SELECT teams.*, media.url AS logo_url 
        FROM teams 
        LEFT JOIN media ON teams.logo = media.id
    ");
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    include 'templates/teams.phtml';

// --- PAGE : DÉTAIL D'UNE TEAM -----------------------------------
} elseif ($page === 'team') {
    
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        
        $stmt = $pdo->prepare("
            SELECT teams.*, media.url AS logo_url 
            FROM teams 
            LEFT JOIN media ON teams.logo = media.id 
            WHERE teams.id = ?
        ");
        $stmt->execute([$id]);
        $team = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmtPlayers = $pdo->prepare("
            SELECT players.*, media.url AS portrait_url 
            FROM players 
            LEFT JOIN media ON players.portrait = media.id 
            WHERE team = ?
        ");
        $stmtPlayers->execute([$id]);
        $players = $stmtPlayers->fetchAll(PDO::FETCH_ASSOC);
        
        include 'templates/team.phtml';
    } else {
        header('Location: index.php?page=teams');
        exit();
    }

// --- PAGE : LISTE DES JOUEURS -----------------------------------
} elseif ($page === 'players') {
    
    $stmt = $pdo->query("
        SELECT players.*, media.url AS portrait_url, teams.name AS team_name 
        FROM players 
        LEFT JOIN media ON players.portrait = media.id 
        LEFT JOIN teams ON players.team = teams.id
    ");
    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    include 'templates/players.phtml';

// --- PAGE : DÉTAIL D'UN JOUEUR ----------------------------------
} elseif ($page === 'player') {
    
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        
        $stmt = $pdo->prepare("
            SELECT players.*, media.url AS portrait_url 
            FROM players 
            LEFT JOIN media ON players.portrait = media.id 
            WHERE players.id = ?
        ");
        $stmt->execute([$id]);
        $player = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $teammates = [];
        if ($player && isset($player['team'])) {
            $stmtMates = $pdo->prepare("
                SELECT players.*, media.url AS portrait_url 
                FROM players 
                LEFT JOIN media ON players.portrait = media.id 
                WHERE team = ? AND players.id != ? 
                LIMIT 2
            ");
            $stmtMates->execute([$player['team'], $id]);
            $teammates = $stmtMates->fetchAll(PDO::FETCH_ASSOC);
        }
        
        // Stats pour la page joueur
        $stmtPerf = $pdo->prepare("
            SELECT 
                pp.points, 
                pp.assists, 
                g.winner,
                t1.id as t1_id, t1.name as t1_name,
                t2.id as t2_id, t2.name as t2_name
            FROM player_performance pp
            JOIN games g ON pp.game = g.id
            LEFT JOIN teams t1 ON g.team_1 = t1.id
            LEFT JOIN teams t2 ON g.team_2 = t2.id
            WHERE pp.player = ?
            ORDER BY g.date DESC
        ");
        $stmtPerf->execute([$id]);
        $performances = $stmtPerf->fetchAll(PDO::FETCH_ASSOC);
        
        include 'templates/player.phtml';
    } else {
        header('Location: index.php?page=players');
        exit();
    }

// --- PAGE : LISTE DES MATCHS ------------------------------------
} elseif ($page === 'matchs') {
    
    $stmt = $pdo->query("
        SELECT games.*, 
               t1.name AS team1_name, m1.url AS team1_logo, 
               t2.name AS team2_name, m2.url AS team2_logo 
        FROM games 
        LEFT JOIN teams t1 ON games.team_1 = t1.id 
        LEFT JOIN media m1 ON t1.logo = m1.id 
        LEFT JOIN teams t2 ON games.team_2 = t2.id 
        LEFT JOIN media m2 ON t2.logo = m2.id 
        ORDER BY date DESC
    ");
    $matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    include 'templates/matchs.phtml';

// --- PAGE : DÉTAIL D'UN MATCH -------------------------
} elseif ($page === 'match') {
    
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        
        // 1. Infos du match
        $stmt = $pdo->prepare("
            SELECT games.*, 
                   t1.name AS team1_name, m1.url AS team1_logo, 
                   t2.name AS team2_name, m2.url AS team2_logo 
            FROM games 
            LEFT JOIN teams t1 ON games.team_1 = t1.id 
            LEFT JOIN media m1 ON t1.logo = m1.id 
            LEFT JOIN teams t2 ON games.team_2 = t2.id 
            LEFT JOIN media m2 ON t2.logo = m2.id 
            WHERE games.id = ?
        ");
        $stmt->execute([$id]);
        $match = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Performances des joueurs pour ce match (Récupération des 6 joueurs)
        $stmtPerf = $pdo->prepare("
            SELECT 
                pp.points, 
                pp.assists, 
                p.nickname AS player_name, 
                t.name AS team_name
            FROM player_performance pp
            LEFT JOIN players p ON pp.player = p.id
            LEFT JOIN teams t ON p.team = t.id
            WHERE pp.game = ?
            ORDER BY t.name ASC, pp.points DESC
        ");
        $stmtPerf->execute([$id]);
        $matchPerformances = $stmtPerf->fetchAll(PDO::FETCH_ASSOC);
        
        include 'templates/match.phtml';
    } else {
        header('Location: index.php?page=matchs');
        exit();
    }

// --- PAGE : ACCUEIL (HOME) --------------------------------------
} else {
    
    // 1. TEAM À LA UNE : Angry Owls (ID 1)
    $stmtTeam = $pdo->query("
        SELECT teams.*, media.url AS logo_url 
        FROM teams 
        LEFT JOIN media ON teams.logo = media.id 
        WHERE teams.id = 1
    ");
    $featuredTeam = $stmtTeam->fetch(PDO::FETCH_ASSOC);
    
    // Joueurs de Angry Owls
    $featuredTeamPlayers = [];
    if ($featuredTeam) {
        $stmtTeamPlayers = $pdo->prepare("
            SELECT players.*, media.url AS portrait_url 
            FROM players 
            LEFT JOIN media ON players.portrait = media.id 
            WHERE team = ? 
            LIMIT 3
        ");
        $stmtTeamPlayers->execute([$featuredTeam['id']]);
        $featuredTeamPlayers = $stmtTeamPlayers->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 2. PLAYERS À LA UNE : Dundy(3), Wolfin(14), Stonk(12)
    $stmtPlayers = $pdo->query("
        SELECT players.*, media.url AS portrait_url, teams.name AS team_name 
        FROM players 
        LEFT JOIN media ON players.portrait = media.id 
        LEFT JOIN teams ON players.team = teams.id 
        WHERE players.id IN (3, 14, 12)
        ORDER BY FIELD(players.id, 3, 14, 12)
    ");
    $featuredPlayers = $stmtPlayers->fetchAll(PDO::FETCH_ASSOC);
    
    // 3. LE DERNIER MATCH : Vendetta vs Owls (ID 3)
    $stmtMatch = $pdo->query("
        SELECT games.*, 
               t1.name AS team1_name, m1.url AS team1_logo, 
               t2.name AS team2_name, m2.url AS team2_logo 
        FROM games 
        LEFT JOIN teams t1 ON games.team_1 = t1.id 
        LEFT JOIN media m1 ON t1.logo = m1.id 
        LEFT JOIN teams t2 ON games.team_2 = t2.id 
        LEFT JOIN media m2 ON t2.logo = m2.id 
        WHERE games.id = 3
    ");
    $lastMatch = $stmtMatch->fetch(PDO::FETCH_ASSOC);
    
    include 'templates/home.phtml';
}
?>