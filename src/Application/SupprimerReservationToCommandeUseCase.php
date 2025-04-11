<?php

namespace App\Application;

use App\Entity\Reservation;
use App\Entity\Commande;
use App\Entity\Vehicule;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;

class SupprimerReservationToCommandeUseCase
{
    private $entityManager;
    private $commandeRepository;

    public function __construct(EntityManagerInterface $entityManager,CommandeRepository $commandeRepository) {
        $this->entityManager = $entityManager;
        $this->commandeRepository = $commandeRepository;

    }
    public function execute(int $idReservation, int $idCommande) {

        $commande = $this->entityManager->getRepository(Commande::class)->find($idCommande);

        if (!$commande) {
            throw new \Exception("La commande n'existe pas");
        }
        $commande->removeReservation($idReservation);

        try {
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw new \Exception("Impossible de supprimer la réservation.");
        }
        return $commande;

    }

}
