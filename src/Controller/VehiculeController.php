<?php

namespace App\Controller;

use App\Application\DeleteVehiculeUseCase;
use App\Application\ListVehiculeUseCase;
use App\Application\ModifyVehiculeUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\CreateVehiculeUseCase;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/vehicule')]
class VehiculeController extends AbstractController
{
    private $listVehiculeUseCase;
    private $createVehiculeUseCase;
    private $modifyVehiculeUseCase;
    private $deleteVehiculeUseCase;



    public function __construct(CreateVehiculeUseCase $createVehiculeUseCase,ListVehiculeUseCase $listVehiculeUseCase,ModifyVehiculeUseCase $modifyVehiculeUseCase,DeleteVehiculeUseCase $deleteVehiculeUseCase) {
        $this->createVehiculeUseCase = $createVehiculeUseCase;
        $this->listVehiculeUseCase = $listVehiculeUseCase;
        $this->modifyVehiculeUseCase = $modifyVehiculeUseCase;
        $this->deleteVehiculeUseCase = $deleteVehiculeUseCase;


    }

    #[Route('/', name: 'vehicule_list', methods: ['GET'])]
    public function listAll(): JsonResponse
    {
        try{
            $data = $this->listVehiculeUseCase->execute();
        }catch(\Exception $e){
            return $this->json(['message' => $e->getMessage()], 500);
        }
        return $this->json($data);
    }

    #[Route('/', name: 'create_vehicule', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['modele']) || empty($data['tarifDuJour']) || empty($data['marque'])) {
            return $this->json(['message' => "Données manquante"], 500);
        }

        try {
            $this->createVehiculeUseCase->execute($data['modele'], $data['marque'], $data['tarifDuJour']);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], 500);
        }

        return $this->json(['message' => 'Véhicule ajouté'], 201);
    }

    #[Route('/{id}', name: 'update_vehicule', methods: ['PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $vehicule = $this->modifyVehiculeUseCase->execute($id,$data['modele'],$data['marque'],$data['tarifDuJour']);
            return  $this->json([
                'message' => 'Modification réussie.',
                'véhicule' => $vehicule
            ]);

        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], 500);
        }
    }

    #[Route('/{id}', name: 'delete_vehicule', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->deleteVehiculeUseCase->execute($id);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], 500);
        }
        return $this->json(['message' => 'Véhicule supprimé']);
    }

}
