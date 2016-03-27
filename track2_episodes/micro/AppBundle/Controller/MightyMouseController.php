<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;


class MightyMouseController implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    
    /**
     * @Route("/")
     */
    public function rescueAction()
    {
        $html = $this->container->get('twig')->render(
            'mighty_mouse/rescue.html.twig',
            ['quote' => 'Here I come to save the day!']
        );
        dump($this->container);
        return new Response($html);
    }
}