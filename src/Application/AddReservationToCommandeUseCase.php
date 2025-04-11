<?php

namespace App\Application;

use App\Entity\Reservation;
use App\Entity\Commande;
use App\Entity\Vehicule;
use Doctrine\ORM\EntityManagerInterface;

class AddReservationToCommandeUseCase
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    public function execute( $dateDebut, $dateFin, int $vehiculeId, int $clientId, int $commandeId) {
        //recherche véhicule
        $vehicule = $this->entityManager->getRepository(Vehicule::class)->find($vehiculeId);
        if (!$vehicule) {
            throw new \Exception("Le véhicule n'existe pas");
        }
        //Création réservation
        try {
            $resevation = new Reservation($dateDebut,$dateFin,$vehicule);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        // création / recherche de la commande
        if($commandeId == null){
            //créer la commande
            $user = $this->entityManager->getRepository(User::class)->find($clientId);
            if (!$user) {
                throw new \Exception("Le user n'existe pas");
            }
            $commande = new Commande($user);
        }else{
            $commande = $this->entityManager->getRepository(Commande::class)->find($commandeId);
        }

        if (!$commande) {
            throw new \Exception("La commande n'existe pas");
        }

        $commande->addReservation($resevation);
        try {
            $this->entityManager->persist($commande);
            $this->entityManager->flush();

        } catch (\Exception $exception) {
            throw new \Exception("Impossible de créer la réservation.");
        }
        return $commande;
    }

}
