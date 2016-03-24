<?php
/**
 * Created by PhpStorm.
 * User: atairov
 * Date: 3/24/16
 * Time: 2:13 AM
 */

namespace Yoda\UserBundle\Doctrine;


use Yoda\UserBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class UserListener
{
    /**
     * @var EncoderFactory
     */
    private $encoderFactory;

    public function __construct(EncoderFactory $encoderFactory)
    {

        $this->encoderFactory = $encoderFactory;
    }
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof User) {
            $this->handleEvent($entity);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof User) {
            $this->handleEvent($entity);
        }
    }

    public function handleEvent(User $user)
    {
        $plainPassword = $user->getPlainPassword();
        if (!$plainPassword) {
            return;
        }
        $encoder = $this->encoderFactory
            ->getEncoder($user);

        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password);
    }

}