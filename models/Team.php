<?php
namespace Models;

class Team {
    private ?int $id = null; 
    private ?string $name = null;
    private ?string $description = null;
    private ?string $logo_url = null;
    private ?string $logo_alt = null;

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function getDescription(): ?string { return $this->description; }
    public function getLogoUrl(): ?string { return $this->logo_url; }
    public function getLogoAlt(): ?string { return $this->logo_alt; }
}