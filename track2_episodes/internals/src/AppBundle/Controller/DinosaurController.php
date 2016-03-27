<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Dinosaur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class DinosaurController extends Controller
{
    /**
     * @Route("/", name="dinosaur_list")
     */
    public function indexAction($isMac)
    {
        $dinos = $this->getDoctrine()
            ->getRepository('AppBundle:Dinosaur')
            ->findAll();

        /*
        // exact things occurs when running render(controller())
        $request = new Request();
        $request->attributes->set(
            '_controller',
            'AppBundle:Dinosaur:_latestTweets'
        );

        $httpKernel = $this->container->get('http_kernel');

        $response = $httpKernel->handle(
            $request,
            HttpKernelInterface::SUB_REQUEST
        );
        */

        return $this->render('dinosaurs/index.html.twig', [
            'dinos' => $dinos,
            'isMac' => $isMac
        ]);
    }

    /**
     * @Route("/dinosaurs/{id}", name="dinosaur_show")
     */
    public function showAction($id, Request $request)
    {
        $dino = $this->getDoctrine()
            ->getRepository('AppBundle:Dinosaur')
            ->find($id);

        if (!$dino) {
            throw $this->createNotFoundException('That dino is extinct!');
        }

        return $this->render('dinosaurs/show.html.twig', [
            'dino' => $dino,
        ]);
    }

    public function _latestTweetsAction($userOnMac)
    {
        $tweets = [
            'tweet number one',
            'tweet number two',
            'tweet number three',
        ];

        return $this->render('dinosaurs/_latestTweets.html.twig', [
            'tweets' => $tweets,
            'isMac' => $userOnMac
        ]);
    }
} 