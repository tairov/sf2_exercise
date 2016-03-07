<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Yoda\EventBundle\Entity\Event;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
Debug::enable();

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();

$kernel->boot();

$container = $kernel->getContainer();

$container->enterScope('request');
$container->set('request', $request);

$templating = $container->get('templating');

$event = new Event();
$event->setName('The birthday of DW');
$event->setLocation('Deathstar');
$event->setTime(new DateTime('tomorrow noon + 5min'));
$event->setDetails('Ha! Darth HATES surprises!');

$em = $container->get('doctrine')->getManager();
$em->persist($event);
$em->flush();

//echo $templating->render('YodaEventBundle:Default:index.html.twig', ['name' => 'yoda', 'count' => 5]);