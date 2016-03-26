<?php


namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Nelmio\Alice\Fixtures;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadFixtureData implements FixtureInterface, ContainerAwareInterface
{
    /** @var Container */
    protected $container;

    public function load(ObjectManager $manager)
    {
        // pass $this as an additional faker provider to make the "groupName"
        // method available as a data provider
        $ymls = [
            __DIR__.'/universe.yml',
            __DIR__.'/characters.yml',
        ];
        $processors = $this->getProcessors();
        Fixtures::load(
            $ymls,
            $manager,
            ['providers' => [$this]],
            $processors);
    }

    public function characterName()
    {
        $names = array(
            'Mario',
            'Luigi',
            'Sonic',
            'Pikachu',
            'Link',
            'Lara Croft',
            'Trogdor',
            'Pac-Man',
        );

        return $names[array_rand($names)];
    }

    public function avatar()
    {
        $names = array(
            'kitten1.jpg',
            'kitten2.jpg',
            'kitten3.jpg',
            'kitten4.jpg',
        );

        return $names[array_rand($names)];
    }

    private function getProcessors()
    {
        return [
            new AvatarProcessor($this->container->get('logger'))
        ];
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}