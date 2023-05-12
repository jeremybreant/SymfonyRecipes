<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class KernelSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        return [RequestEvent::class => 'onKernelRequest'];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        //dd($event->getRequest()->getPathInfo());
    }
}