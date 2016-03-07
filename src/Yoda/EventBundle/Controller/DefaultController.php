<?php

namespace Yoda\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction($name, $count)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('YodaEventBundle:Event');

        $event = $repo->findOneBy(
            ['name' => 'The birthday of DW']
        );

        return $this->render('YodaEventBundle:Default:index.html.twig',
                             ['name' => $name, 'count' => $count, 'event' => $event]);
    }
}
