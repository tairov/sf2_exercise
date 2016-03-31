<?php

namespace AppBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class UserAgentSubscriber implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        $userAgent = $request->headers->get('User-Agent');

        $this->logger->info('Hello there browser: '.$userAgent);

        if (rand(0, 100) > 50) {
            $response = new Response('Come back later');
            //$event->setResponse($response);
        }

        $isMac = stripos($userAgent, 'Mac') !== false;
        if ($request->query->get('notMac')) {
            $isMac = false;
        }
        $request->attributes->set('isMac', $isMac);

        /*
        $request->attributes->set('_controller', function($id) {
            return new Response('Hello '.$id);
        });
        */
    }

    public static function getSubscribedEvents()
    {
        return array(
            // constant that means kernel.request
            KernelEvents::REQUEST => 'onKernelRequest'
        );
    }
}
