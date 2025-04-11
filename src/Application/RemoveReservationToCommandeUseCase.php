<?php

namespace App\Application;

use App\Entity\Reservation;
use App\Entity\Commande;
use App\Entity\Vehicule;
use Doctrine\ORM\EntityManagerInterface;

class RemoveReservationToCommandeUseCase
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    public function execute(int $idReservation, int $idCommande) {

        $commande = $this->entityManager->getRepository(Commande::class)->find($idCommande);

        if (!$commande) {
            throw new \Exception("La commande n'existe pas");
        }
        $commande->removeReservation($idReservation);
    }

}
