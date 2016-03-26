<?php
namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Character;
use Nelmio\Alice\ProcessorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;


class AvatarProcessor implements ProcessorInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AvatarProcessor constructor.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * Processes an object before it is persisted to DB
     *
     * @param object $object instance to process
     */
    public function preProcess($object)
    {
        // global kernel is
        // bad practice dont repeat at home!!!
        global $kernel;

        if (!$object instanceof Character) {
            return;
        }

        if (!$object->getAvatarFilename()) {
            return;
        }
        

        $projectRoot = $kernel->getContainer()->getParameter('kernel.root_dir') . '/..';
        $targetFilename = 'fixtures_' . mt_rand(0, 100000) . '.jpg';
        $fs = new Filesystem();
        $fs->copy(
            $projectRoot . '/resources/' . $object->getAvatarFilename(),
            $projectRoot . '/web/uploads/avatars/' . $targetFilename,
            true // ovveride existing
        );

        $this->logger->debug(sprintf(
            'Character %s using filename %s from %s',
            $object->getName(),
            $targetFilename,
            $object->getAvatarFilename()
        ));
        $object->setAvatarFilename($targetFilename);
    }

    /**
     * Processes an object after it is persisted to DB
     *
     * @param object $object instance to process
     */
    public function postProcess($object)
    {
        // TODO: Implement postProcess() method.
    }
}