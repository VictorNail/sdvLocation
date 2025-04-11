<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $methodeDePaiement = null;

    private const ENUM_MODE_PAIEMENT = ['CB', 'PAYPAL'];

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePaiement = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    public function __construct($modePaiement)
    {
        if (!in_array($modePaiement,$this->ENUM_MODE_PAIEMENT)) {
            return new JsonResponse(['error' => 'Mode de paiement invalide'], 400);
        }
        $this->statut="CREATED";
        $this->methodeDePaiement= $modePaiement;
    }

    public function payer()
    {
        $this->statut="CREATED";
        $this->datePaiement= new Date();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMethodeDePaiement(): ?string
    {
        return $this->methodeDePaiement;
    }

    public function setMethodeDePaiement(string $methodeDePaiement): static
    {
        $this->methodeDePaiement = $methodeDePaiement;

        return $this;
    }

    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->datePaiement;
    }

    public function setDatePaiement(?\DateTimeInterface $datePaiement): static
    {
        $this->datePaiement = $datePaiement;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }
}
