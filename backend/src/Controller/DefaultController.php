<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\UserConfirmationService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */

class DefaultController extends AbstractController{
    /**
     * @Route("/", name="default_index")
     */
    public function index() {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/confirm-user/{token}", name="default_confirm_token")
     */
    public function confirmUser(
        string $token,
        UserConfirmationService $userConfirmationService
    )
    {
        $userConfirmationService->confirmUser($token);

        return $this->redirect('http://localhost:4200/login');
    }

    /**
     * @Route ("/user-details/{username}")
     */
    public function userDetails(ManagerRegistry $doctrine, $username)
    {
        $user = $doctrine->getRepository(User::class)->findOneBy(['username' => $username]);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found with the username: '.$username
            );
        }

        return $this->json([
            'user_data' => [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'role' => $user->getRoles(),
                'organization' => $user->getOrganization()
            ],
        ]);
    }
}