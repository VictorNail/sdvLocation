<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Paiement $paiement = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Assurance $assurance = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: Reservation::class, cascade: ['persist', 'remove'])]
    private Collection $reservations;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct(User $user)
    {
        $this->reservations = new ArrayCollection();
        $this->statut = "CART";
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function getPaiement(): ?Paiement
    {
        return $this->paiement;
    }

    public function setPaiement(?Paiement $paiement): static
    {
        if ($this->getStatut() !== 'CART') {
            throw new \Exception("Impossible de rajouter un paiement");
        }

        $this->paiement = $paiement;

        return $this;
    }

    public function getAssurance(): ?Assurance
    {
        return $this->assurance;
    }


    public function getReservation(): ?Collection
    {
        return $this->reservation;
    }

    public function addReservation(Reservation $reservation){
        if($this->getStatut() != "CART"){
            throw new \Exception("Impossible de rajouter une livraison à cette commande");
        }

        $this->reservations->add($reservation);
    }

    public function removeReservation(int $idReservation){
        if($this->getStatut() != "CART"){
            throw new \Exception("Impossible de retirer une livraison à cette commande");
        }

        foreach ($this->reservations as $reservation) {
            if ($reservation->getId() === $idReservation) {
                $this->reservations->removeElement($reservation);
                return;
            }
        }

        throw new \Exception("La réservation à supprimer n'a pas été trouvée.");
    }

    public function payer(){
        if($this->getStatut() != "CART"){
            throw new \Exception("Impossible de payer cette commande");
        }
        $this->paiement->payer();
        $this->statut = "PAYD";
    }

    public function ajouterAssurance(){
        if( $this->assurance != null){
            throw new \Exception("Impossible d'ajouter une assurance a cette commande");
        }
        $this->assurance = new Assurance();
    }

    public function enleverAssurance(){
        if( $this->assurance == null){
            throw new \Exception("Impossible d'enlever l'assurance de cette commande");
        }
        $this->assurance = null;
    }

}
