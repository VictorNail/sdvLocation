<?php

namespace App\Application;

use App\Entity\Reservation;
use App\Entity\Commande;
use App\Entity\Vehicule;
use App\Repository\CommandeRepository;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;

class AjouterReservationToCommandeUseCase
{
    private $entityManager;
    private $vehiculeRepo;
    private $commandeRepo;

    public function __construct(EntityManagerInterface $entityManager, VehiculeRepository $vehiculeRepo,  CommandeRepository $commandeRepo) {
        $this->entityManager = $entityManager;
        $this->vehiculeRepo = $vehiculeRepo;
        $this->commandeRepo = $commandeRepo;

    }
    public function execute( $dateDebut, $dateFin, int $vehiculeId, int $commandeId) {
        //recherche véhicule
        $vehicule = $this->vehiculeRepo->find($vehiculeId);
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
            $commande = new Commande($this->getUser());
        }else{
            $commande = $this->commandeRepo->find($commandeId);
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
