<?php

namespace Yoda\UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Yoda\UserBundle\Entity\User;
use Yoda\UserBundle\Form\RegisterFormType;


class RegisterController extends Controller
{

    /**
     * @Route(path="/register", name="user_register")
     * @Template()
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $user->setUsername('Leia');
        $form = $this->createForm(new RegisterFormType(), $user);

        $form->handleRequest($request);

        if ($form->isValid()) {

            /** @var User $user */
            $user = $form->getData();

            $user->setPassword($this->encodePassword($user, $user->getPlainPassword()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $url = $this->generateUrl('event');
            return $this->redirect($url);
        }

        return ['form' => $form->createView()];
    }

    public function encodePassword(User $user, $plainPassword)
    {
        $encoder = $this->container->get('security.encoder_factory')
            ->getEncoder($user);

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }
}