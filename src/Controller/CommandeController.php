<?php

namespace App\Controller;

use App\Application\PayerCommandeUseCase;
use App\Application\SelectionnerPaiementCommandeUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Application\AddReservationToCommandeUseCase;
use App\Application\SupprimerReservationToCommandeUseCase;
use App\Application\AjouterAssuranceCommandeUseCase ;
use App\Application\SupprimerAssuranceCommandeUseCase ;

#[Route('/commande')]
#[IsGranted('ROLE_USER')]
class CommandeController extends AbstractController
{
    private $addReservationUseCase;
    private $supprimerReservationToCommandeUseCase;
    private $selectionnerPaiementCommandeUseCase;
    private $payerCommandeUseCase;
    private $ajouterAssuranceCommandeUseCase;
    private $supprimerAssuranceCommandeUseCase;

    public function __construct(
        AddReservationToCommandeUseCase $addReservationUseCase,
        SupprimerReservationToCommandeUseCase $supprimerReservationToCommandeUseCase,
        SelectionnerPaiementCommandeUseCase $selectionnerPaiementCommandeUseCase,
        PayerCommandeUseCase $payerCommandeUseCase,
        AjouterAssuranceCommandeUseCase $ajouterAssuranceCommandeUseCase,
        SupprimerAssuranceCommandeUseCase $supprimerAssuranceCommandeUseCase

    ) {
        $this->addReservationUseCase = $addReservationUseCase;
        $this->supprimerReservationToCommandeUseCase = $supprimerReservationToCommandeUseCase;
        $this->selectionnerPaiementCommandeUseCase = $selectionnerPaiementCommandeUseCase;
        $this->payerCommandeUseCase = $payerCommandeUseCase;
        $this->ajouterAssuranceCommandeUseCase= $ajouterAssuranceCommandeUseCase;
        $this->supprimerAssuranceCommandeUseCase= $supprimerAssuranceCommandeUseCase;

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

    #[Route('/{idCommande}/reservation/{idReservation}', name: 'remove_reservation', methods: ['DELETE'])]
    public function supprimerReservation(int $idReservation, int $idCommande): JsonResponse
    {
        try {
            $commande = $this->supprimerReservationToCommandeUseCase->execute($idReservation,$idCommande);
            return $this->json(['message' => 'Réservation supprimée','Commande'=> $commande]);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], 500);
        }
    }

    #[Route('/{$idCommande}/selectionner-paiement', name: 'commande_set_paiement', methods: ['POST'])]
    public function selectionnerPaiement(Request $request, int $idCommande): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['$methodeDePaiement'])) {
            return $this->json(['message' => 'methodeDePaiement manquante'], 400);
        }

        try {
            $commande = $this->selectionnerPaiementCommandeUseCase->execute($idCommande,$data['$methodeDePaiement']);
            return new JsonResponse(['success' => 'Mode de paiement sélectionné',"Commande"=>$commande]);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], 500);
        }
    }

    #[Route('/{idCommande}/payer', name: 'commande_payer', methods: ['POST'])]
    public function payer(int $idCommande): JsonResponse
    {
        try {
            $commande = $this->payerCommandeUseCase->execute($idCommande);
            return new JsonResponse(['success' => 'Commande payer',"Commande"=>$commande]);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], 500);
        }
    }

    #[Route('/{idCommande}/assurance', name: 'add_assurance', methods: ['POST'])]
    public function ajouterAssurance(int $idCommande): JsonResponse
    {
        try {
            $commande = $this->ajouterAssuranceCommandeUseCase->execute($idCommande);
            return new JsonResponse(['success' => 'Assurance ajoutée',"Commande"=>$commande]);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], 500);
        }
    }

    #[Route('/{idCommande}/assurance', name: 'remove_assurance', methods: ['DELETE'])]
    public function supprimerAssurance(int $idCommande): JsonResponse
    {
        try {
            $commande = $this->supprimerAssuranceCommandeUseCase->execute($idCommande);
            return $this->json(['message' => 'Assurance supprimée','Commande'=> $commande]);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], 500);
        }
    }
}
