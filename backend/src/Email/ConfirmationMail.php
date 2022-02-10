<?php

namespace App\Email;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class ConfirmationMail extends AbstractController
{
    public $mailer;

    public function __construct(
        MailerInterface $mailer
    )
    {
        $this->mailer = $mailer;
    }

    public function sendConfirmationEmail(User $user)
    {
        $email = (new TemplatedEmail())
            ->from('ginstormer@gmail.com')
            ->to($user->getEmail())
            ->subject('Please confirm your account!')
            ->htmlTemplate('Email/confirmation.html.twig')
            ->context([
                'name' => $user->getName(),
                'confirmationToken' => $user->getConfirmationToken(),
            ]);

        $this->mailer->send($email);
    }

    public function sendRegistrationEmail(User $user)
    {
        $queryParams = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'user_email' => $user->getEmail(),
            'organization' => $user->getOrganization(),
            'roles' => $user->getRoles()[0],
            'confirmationToken' => $user->getConfirmationToken(),
        ];


        $email = (new TemplatedEmail())
            ->from('ginstormer@gmail.com')
            ->to($user->getEmail())
            ->subject('Please register your account!')
            ->htmlTemplate('Email/registration.html.twig')
            ->context([
                'params' => $queryParams,
                'name' => $user->getName(),
                'organization' => $user->getOrganization(),
            ]);


        $this->mailer->send($email);
    }
}