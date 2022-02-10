<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Security\AddPartnerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class AddPartnerSubscriber implements EventSubscriberInterface
{
    public function addPartner()
    {
        dd('addpartner event triggered');
    }

    public static function getSubscribedEvents()
    {
        return [
            AddPartnerService::class => ['addPartner', EventPriorities::POST_VALIDATE]
        ];
    }
}