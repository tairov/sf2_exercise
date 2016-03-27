<?php
namespace AppBundle\EventListener;


use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;


class UserAgentSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
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
        $this->logger->info('RRAAWWWWR');
        $request = $event->getRequest();
        $userAgent = $request->headers->get('User-Agent');

        $this->logger->info('The user-agent is: ' . $userAgent);
//
//        $request->attributes->set('_controller', function($id) {
//            return new Response('Hello world' . $id);
//        });

        $isMac = true;

        if ($request->query->get('noMac')) {
            $isMac = false;
        }
        $request->attributes->set('isMac', $isMac);

        if (mt_rand(1,100) > 50) {
//            $response = new Response('Coma back later!');
//            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            // kernel.request
            KernelEvents::REQUEST => 'onKernelRequest'
        ];
    }
}