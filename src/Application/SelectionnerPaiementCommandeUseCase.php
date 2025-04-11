<?php

namespace App\Application;

use App\Entity\Paiement;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;

class SelectionnerPaiementCommandeUseCase
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    public function execute(int $idCommande,string $modePaiement) {

        $commande = $this->entityManager->getRepository(Commande::class)->find($idCommande);

        if (!$commande) {
            throw new \Exception("La commande n'existe pas");
        }

        try{
            $paiement = new Paiement($modePaiement);
            $commande->setPaiement($paiement);
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }

        try {
            $this->entityManager->persist($paiement);
            $this->entityManager->flush();

        } catch (\Exception $exception) {
            throw new \Exception("Impossible de sélectionner la méthode de paiement.");
        }
    }

}
