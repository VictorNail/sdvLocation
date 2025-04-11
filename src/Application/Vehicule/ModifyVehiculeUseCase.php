<?php

namespace App\Application\Vehicule;

use App\Entity\Vehicule;
use Doctrine\ORM\EntityManagerInterface;

class ModifyVehiculeUseCase
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    public function execute(String $id,String $modele,String $marque, Float $tarifDuJour) {
        $vehicule = $this->entityManager->getRepository(Vehicule::class)->find($id);

        if (!$vehicule) {
            throw new \Exception("Le véhicule n'existe pas");
        }

        try {
            $vehicule->modify($modele,$marque,$tarifDuJour);
            $this->entityManager->flush();
            return($vehicule);
        } catch (\Exception $exception) {
            throw new \Exception("Impossible de modifier le véhicule.");
        }
    }

}