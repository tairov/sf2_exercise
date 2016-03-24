<?php
/**
 * Created by PhpStorm.
 * User: atairov
 * Date: 3/24/16
 * Time: 12:29 AM
 */

namespace Yoda\EventBundle\Reporting;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;


class EventReportManager
{

    private $em = null;

    /**
     * @var Router
     */
    private $router;

    public function __construct(EntityManager $em, Router $router)
    {
        $this->em = $em;

        $this->router = $router;
    }
    public function getRecentlyUpdatedReport()
    {
        $events = $this->em->getRepository('YodaEventBundle:Event')
            ->getRecentlyUpdatedEvents();
        $rows = [];

        foreach ($events as $event) {
            $data = array(
                $event->getId(),
                $event->getName(),
                $event->getTime()->format('Y-m-d H:i:s'),
                $this->router->generate(
                    'event_show',
                    array('slug' => $event->getSlug()),
                    true
                )
            );

            $rows[] = implode(', ', $data);
        }

        $content = implode(PHP_EOL, $rows);

        return $content;
    }

}