<?php

namespace Yoda\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use \DateTime;
use Yoda\EventBundle\Entity\Event;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadEvents implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = $manager->getRepository('UserBundle:User')
            ->findOneByUsernameOrEmail('darth@localhost.local');
        $event = new Event();
        $event->setName('test event' . uniqid());
        $event->setLocation('Location test event' . uniqid());
        $event->setDetails('test event details ' . uniqid());
        $event->setTime(new DateTime());
        $manager->persist($event);

        $event2 = new Event();
        $event2->setName('test event' . uniqid());
        $event2->setLocation('Location test event' . uniqid());
        $event2->setDetails('test event details ' . uniqid());
        $event2->setTime(new DateTime('+20 days'));
        $manager->persist($event2);

        $event3 = new Event();
        $event3->setName('test event' . uniqid());
        $event3->setLocation('Location test event' . uniqid());
        $event3->setDetails('test event details ' . uniqid());
        $event3->setTime(new DateTime('+22 days'));
        $manager->persist($event2);

        $event->setOwner($user);
        $event2->setOwner($user);

        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }


}