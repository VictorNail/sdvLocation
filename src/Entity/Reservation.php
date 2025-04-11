<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Exception;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehicule $type = null;

    public function __construct(\DateTimeInterface $dateDebut,\DateTimeInterface $dateFin,Vehicule $type)
    {
        if($dateDebut < new Date()){
            throw new Exception(message:"La date de début doit être suppérieur à la date du jour");
        }
        if( $dateDebut > $dateFin){
            throw new Exception(message:"La date de fin doit être suppérieur à la date de début");
        }
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->prix = $this->calculerPrix($dateDebut, $dateFin, $type->getTarifDuJour());
    }

    // Méthode pour calculer le prix
    private function calculerPrix(\DateTimeInterface $dateDebut, \DateTimeInterface $dateFin, float $tarifDuJour): float
    {
        $interval = $dateDebut->diff($dateFin);
        $nombreDeJours = $interval->days;

        return $nombreDeJours * $tarifDuJour;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getType(): ?Vehicule
    {
        return $this->type;
    }

    public function setType(?Vehicule $type): static
    {
        $this->type = $type;

        return $this;
    }
}
