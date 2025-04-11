<?php

namespace App\Application\Vehicule;

use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteVehiculeUseCase
{
    private $entityManager;
    private $vehiculeRepo;


    public function __construct(EntityManagerInterface $entityManager, VehiculeRepository $vehiculeRepo) {
        $this->entityManager = $entityManager;
        $this->vehiculeRepo =$vehiculeRepo;
    }
    public function execute(String $id) {
        $vehicule = $this->vehiculeRepo->find($id);

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