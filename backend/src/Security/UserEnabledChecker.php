<?php

namespace App\Security;

use App\Entity\User;

use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserEnabledChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user)
    {

        if (!$user instanceof User) {
            return;
        }

        if (!$user->getEnabled()) {
            throw new CustomUserMessageAccountStatusException('Account is not enabled. Please verify your email or ask your administrator to enable it');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Implement checkPostAuth() method.
    }
}