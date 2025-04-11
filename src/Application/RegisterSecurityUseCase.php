<?php

namespace App\Application;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RegisterSecurityUseCase
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    public function execute($email,$password,$nom,$prenom,$obtentionPermis) {

        try {
            $user = new User($email,$password,$nom,$prenom,$obtentionPermis);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

        } catch (\Exception $exception) {
            throw new \Exception("Impossible de créer l'utilisateur.");
        }
        return $user;
    }

}
