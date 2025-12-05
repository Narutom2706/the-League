<?php
namespace Models;

class Player {
    private ?int $id;
    private ?string $firstName;
    private ?string $lastName;
    // Ajoute ici les autres champs de ta table (image, etc.)

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getFirstName(): ?string { return $this->firstName; }
    public function setFirstName(string $firstName): void { $this->firstName = $firstName; }
    
    // ... Fais pareil pour les autres champs
}