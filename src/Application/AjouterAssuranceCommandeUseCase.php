<?php

namespace App\Application;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;

class AjouterAssuranceCommandeUseCase
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    public function execute(int $idCommande) {

        $commande = $this->entityManager->getRepository(Commande::class)->find($idCommande);

        if (!$commande) {
            throw new \Exception("La commande n'existe pas");
        }

        try {
            $commande->ajouterAssurance();
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
