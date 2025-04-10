<?php

namespace App\Application;

use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteVehiculeUseCase
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    public function execute(String $id) {
        $vehicule = $this->entityManager->getRepository(Vehicule::class)->find($id);

        if (!$vehicule) {
            throw new \Exception("Le véhicule n'existe pas");
        }

        try {
            $this->entityManager->remove($vehicule);
            $this->entityManager->flush();

        } catch (\Exception $exception) {
            throw new \Exception("Impossible de supprimer le véhicule.");
        }
    }

}