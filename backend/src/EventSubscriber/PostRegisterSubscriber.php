<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Email\ConfirmationMail;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PostRegisterSubscriber implements EventSubscriberInterface
{


    private $doctrine;
    private $mailer;

    public function __construct(ManagerRegistry $doctrine, ConfirmationMail $mailer)
    {

        $this->doctrine = $doctrine;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['postRegister', EventPriorities::POST_DESERIALIZE]
        ];
    }

    public function postRegister(ViewEvent $event)
    {
        $user = $event->getControllerResult();

        /*
         * When the user has an organization and is not fully registered we send a registration email
         */
        if ($user->getOrganization() !== null && !$user->getFullyRegistered()) {

            $this->mailer->sendRegistrationEmail($user);

            $_user = $this->doctrine->getRepository(User::class)->findOneBy(['id' => $user->getId()]);
            $_user->setFullyRegistered(true);
            $em = $this->doctrine->getManager();
            $em->persist($_user);
            $em->flush();
        }
    }
}