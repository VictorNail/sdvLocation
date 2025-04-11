<?php

namespace App\Controller;

use App\Application\RegisterSecurityUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SecurityController extends AbstractController
{
    private $registerSecurityUseCase;


    public function __construct(
        RegisterSecurityUseCase $registerSecurityUseCase,
    ) {
        $this->registerSecurityUseCase = $registerSecurityUseCase;
    }

    #[Route('/api/register', name: 'register_security', methods: ['POST'])]
    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try{
            $userCreated = $this->registerSecurityUseCase->execute($data['email'], $data['password'],$data['nom'], $data['prenom'],$data['obtentionPermis']);
            return $this->json(['message' => 'Utilisateur enregistré avec succès','user' => $userCreated], 201);
        }catch (\Exception $e){
            return $this->json(['message' => $e->getMessage()], 500);
        }
    }

    #[Route('/api/logout', name: 'logout_security', methods: ['POST'])]
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}
