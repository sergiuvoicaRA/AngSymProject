<?php

namespace App\Email;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use function Symfony\Component\String\u;

class ConfirmationMail
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
}