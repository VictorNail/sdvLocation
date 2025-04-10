<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $modele = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column]
    private ?float $tarifDuJour = null;

    public function __construct($modele, $marque, $tarifDuJour)
    {
        if($tarifDuJour < 0){
            throw new Exception(message:"Le tarifDuJour doit être suppérieur à 0");
        }
        $this->modele = $modele;
        $this->marque = $marque;
        $this->tarifDuJour = $tarifDuJour;
    }

    public function modify($modele, $marque, $tarifDuJour)
    {
        if($tarifDuJour != null && $tarifDuJour > 0 ){
            $this->tarifDuJour = $tarifDuJour;
        }
        if($marque != null ){
            $this->marque = $marque;
        }
        if($modele != null ){
            $this->modele = $modele;
        }
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getTarifDuJour(): ?float
    {
        return $this->tarifDuJour;
    }

    public function setTarifDuJour(float $tarifDuJour): static
    {
        $this->tarifDuJour = $tarifDuJour;

        return $this;
    }
}
