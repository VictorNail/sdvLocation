<?php

namespace App\Application;

use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;

class ListVehiculeUseCase
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    public function execute() {
        try {
            return $this->entityManager->getRepository(Vehicule::class)->findAll();
        } catch (\Exception $exception) {
            throw new \Exception("Impossible d'accéder à la list vehicule.");
        }
    }

}