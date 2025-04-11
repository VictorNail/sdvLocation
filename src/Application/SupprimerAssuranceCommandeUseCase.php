<?php

namespace App\Application;

use App\Entity\Assurance;
use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;

class SupprimerAssuranceCommandeUseCase
{
    private $entityManager;
    private $commandeRepository;

    public function __construct(EntityManagerInterface $entityManager, CommandeRepository $commandeRepository) {
        $this->entityManager = $entityManager;
        $this->commandeRepository = $commandeRepository;
    }
    public function execute(int $idCommande) {

        $commande = $this->entityManager->getRepository(Commande::class)->find($idCommande);

        if (!$commande) {
            throw new \Exception("La commande n'existe pas");
        }

        try {
            $commande->enleverAssurance();
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

        try {
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw new \Exception("Impossible de supprimer l'assurance.");
        }
        return $commande;

    }

}
