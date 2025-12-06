<?php
namespace Models;

class Player {
    private ?int $id = null;
    private ?string $nickname = null;
    private ?string $bio = null;
    private ?string $portrait_url = null;
    private ?string $team_name = null;

    public function getNickname(): ?string { return $this->nickname; }
    public function getPortraitUrl(): ?string { return $this->portrait_url; }
    public function getTeamName(): ?string { return $this->team_name; }
}