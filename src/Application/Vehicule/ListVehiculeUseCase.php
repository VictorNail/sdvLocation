<?php

namespace App\Application\Vehicule;

use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;

class ListVehiculeUseCase
{
    private $entityManager;
    private $vehiculeRepo;

    public function __construct(EntityManagerInterface $entityManager, VehiculeRepository $vehiculeRepo) {
        $this->entityManager = $entityManager;
        $this->vehiculeRepo = $vehiculeRepo;
    }
    public function execute() {
        try {
            return $this->vehiculeRepo->findAll();
        } catch (\Exception $exception) {
            throw new \Exception("Impossible d'accéder à la list vehicule.");
        }
    }

}