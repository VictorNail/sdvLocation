<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Application\AddReservationToCommandeUseCase;
use App\Application\RemoveReservationToCommandeUseCase;

#[Route('/commande')]
#[IsGranted('ROLE_USER')]
class CommandeController extends AbstractController
{
    private $addReservationUseCase;
    private $removeReservationUseCase;


    public function __construct(
        AddReservationToCommandeUseCase $addReservationUseCase,
        RemoveReservationToCommandeUseCase $removeReservationUseCase,

    ) {
        $this->addReservationUseCase = $addReservationUseCase;
        $this->removeReservationUseCase = $removeReservationUseCase;

    }

    #[Route('/reservation', name: 'add_reservation', methods: ['POST'])]
    public function addReservation(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['vehiculeId']) || empty($data['dateDebut']) || empty($data['dateFin'])) {
            return $this->json(['message' => 'Données manquantes'], 400);
        }
                try {
            $commande = $this->addReservationUseCase->execute($data['dateDebut'],$data['dateFin'],$data['vehiculeId'],$data['commandeId']);
            return $this->json(['message' => 'Réservation ajoutée ou commande créée','Commande'=> $commande]);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], 500);
        }
    }

    #[Route('/reservation', name: 'remove_reservation', methods: ['DELETE'])]
    public function removeReservation(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['$idCommande']) || empty($data['idReservation'])) {
            return $this->json(['message' => 'idCommande manquante'], 400);
        }

        try {
            $commande = $this->removeReservationUseCase->execute($data['idReservation'],$data['$idCommande']);
            return $this->json(['message' => 'Réservation supprimée','Commande'=> $commande]);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], 500);
        }
    }

    #[Route('/paiement', name: 'add_paiement', methods: ['PUT'])]
    public function addPaiement(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['$idCommande']) || empty($data['idReservation'])) {
            return $this->json(['message' => 'idCommande manquante'], 400);
        }

        try {
            $commande = $this->removeReservationUseCase->execute($data['idReservation'],$data['$idCommande']);
            return $this->json(['message' => 'Réservation supprimée','Commande'=> $commande]);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], 500);
        }
    }
}
