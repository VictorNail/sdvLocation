<?php

namespace App\Application;

use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateVehiculeUseCase
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    public function execute(String $modele,String $marque, Float $tarifDuJour) {

        try {
            $vehicule = new Vehicule($modele,$marque, $tarifDuJour);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        try {
            $this->entityManager->persist($vehicule);
            $this->entityManager->flush();

        } catch (\Exception $exception) {
            throw new \Exception("Impossible de créer le véhicule.");
        }
    }

}