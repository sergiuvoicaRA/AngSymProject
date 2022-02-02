<?php

namespace App\Security;

use App\Repository\UserRepository;
use App\Util\DatabaseHandler;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserConfirmationService
{
    public $userRepository;
    public $entityManager;
    private $logger;
    private $container;
    private $db_handler;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        ContainerInterface $container,
        DatabaseHandler $db_handler
    )
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->container = $container;
        $this->db_handler = $db_handler;
    }

    public function confirmUser(string $confirmationToken)
    {
        $this->container->get('monolog.db_handler')->info('User confirmed', [
            'message' => 'message1',
            'level' => 'level1',
            'level_name' => 'slsdlsl',
            'extra' => 'extra',
            'context' => 'context',
        ]);die;

        $user = $this->userRepository->findOneBy(
            ['confirmationToken' => $confirmationToken]
        );

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $user->setEnabled(true);
        $user->setConfirmationToken(null);
        $this->entityManager->flush();


    }
}