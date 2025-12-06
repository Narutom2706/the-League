<?php
namespace Controllers;

use Managers\TeamManager;
use Managers\PlayerManager;
use Managers\GameManager;

class Controller {
    
    public function displayHome() {
        $teamManager = new TeamManager();
        $playerManager = new PlayerManager();
        $gameManager = new GameManager();

        $teams = $teamManager->findAll();
        $featuredTeam = $teams[0] ?? null;
        
        $featuredTeamPlayers = [];
        if($featuredTeam) {
            $featuredTeamPlayers = $playerManager->findByTeamId($featuredTeam->getId());
        }

        $allPlayers = $playerManager->findAll();
        $featuredPlayers = array_slice($allPlayers, 0, 3);

        $games = $gameManager->findAll();
        $lastGame = $games[0] ?? null;

        require_once "templates/partials/header.phtml";
        require_once "templates/home.phtml";
        require_once "templates/partials/footer.phtml";
    }
}