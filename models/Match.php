<?php
namespace Models;

class Game {
    private ?int $id = null;
    private ?string $name = null;
    private ?string $date = null;
    private ?string $team_1_name = null;
    private ?string $team_2_name = null;
    private ?string $team_1_logo = null;
    private ?string $team_2_logo = null;

    public function getName(): ?string { return $this->name; }
    public function getDate(): ?string { 
        return date("d/m/Y", strtotime($this->date)); 
    }
    public function getTeam1Name(): ?string { return $this->team_1_name; }
    public function getTeam2Name(): ?string { return $this->team_2_name; }
    public function getTeam1Logo(): ?string { return $this->team_1_logo; }
    public function getTeam2Logo(): ?string { return $this->team_2_logo; }
}