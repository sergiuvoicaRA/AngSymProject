<?php

namespace App\Security;

use App\Email\ConfirmationMail;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddPartnerService extends AbstractController
{
    private $tokenGenerator;
    private $em;
    private $mailer;

    public function __construct(
        TokenGenerator $tokenGenerator,
        EntityManagerInterface $em,
        ConfirmationMail $mailer
    )
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function addPartner(User $user) {
        $user->setPassword('password123');
        $user->setConfirmationToken(
            $this->tokenGenerator->getRandomSecureToken()
        );
        $user->setUsername('randomUsername'.rand(1,1000));

        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();

        $this->mailer->sendRegistrationEmail($user);
    }
}