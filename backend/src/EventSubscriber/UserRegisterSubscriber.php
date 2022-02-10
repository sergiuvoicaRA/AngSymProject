<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Email\ConfirmationMail;
use App\Entity\User;
use App\Security\TokenGenerator;
use App\Util\DatabaseHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class UserRegisterSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    private $tokenGenerator;
    private $mailer;
    private $logger;


    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        TokenGenerator               $tokenGenerator,
        ConfirmationMail             $mailer,
        LoggerInterface              $logger
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGenerator = $tokenGenerator;

        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['userRegistered', EventPriorities::PRE_WRITE],
        ];
    }

    public function userRegistered(ViewEvent $event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $parameters = json_decode($event->getRequest()->getContent(), true);

        if (!$user instanceof User || !in_array($method, [Request::METHOD_POST, Request::METHOD_PUT])) {
            return;
        }

        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $user->getPassword())
        );


        $user->setRoles([$parameters['roles']]);
        $user->setOrganization($parameters['organization']);
        $user->setFullyRegistered($parameters['fullyRegistered']);

        /*
         * Only when the user is not fully registered we set a secure token, otherwise we`ll overwrite it
         */

        if (!$user->getFullyRegistered()) {

            $user->setConfirmationToken(
                $this->tokenGenerator->getRandomSecureToken(30)
            );
        }

        if ($user->getFullyRegistered()) {
            $this->mailer->sendConfirmationEmail($user);
        }
    }
}