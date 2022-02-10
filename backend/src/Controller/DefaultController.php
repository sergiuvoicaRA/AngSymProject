<?php

namespace App\Controller;

use App\Email\ConfirmationMail;
use App\Entity\User;
use App\Security\TokenGenerator;
use App\Security\UserConfirmationService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */

class DefaultController extends AbstractController{
    private $passwordEncoder;
    private $tokenGenerator;
    private $mailer;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        TokenGenerator $tokenGenerator,
        ConfirmationMail $mailer
    )
    {

        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
    }

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
                'organization' => $user->getOrganization(),
            ],
        ]);
    }

    /**
     * @Route("/register-partner", name="register_partner")
     */
    public function registerPartner(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $user->getPassword())
        );

        $user->setConfirmationToken(
            $this->tokenGenerator->getRandomSecureToken()
        );

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->mailer->sendConfirmationEmail($user);

        return $this->json([
            'user_data' => [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'role' => $user->getRoles(),
                'organization' => $user->getOrganization(),
            ],
        ]);
////        if (!$user instanceof User || !in_array($method, [Request::METHOD_POST, Request::METHOD_PUT])) {
////            return;
////        }
//
//        $user->setPassword(
//            $this->passwordEncoder->encodePassword($user, $user->getPassword())
//        );
//
//        $user->setConfirmationToken(
//            $this->tokenGenerator->getRandomSecureToken()
//        );
//
//        $user->setRoles([$parameters['roles']]);
//
//        $this->mailer->sendConfirmationEmail($user);
//
//        $serializer = $this->get('serializer');
//
//        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
//
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($user);
//        $em->flush();
    }
}