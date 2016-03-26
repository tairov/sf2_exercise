<?php
namespace AppBundle\Security;


use AppBundle\Entity\DeliciousCookie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Symfony\Component\Security\Core\User\UserInterface;


class CookieVoter extends AbstractVoter
{
    const ATTRIBUTE_NOM = 'NOM';
    const ATTRIBUTE_DONATE = 'DONATE';
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * CookieVoter constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Return an array of supported classes. This will be called by supportsClass
     *
     * @return array    an array of supported classes, i.e. array('Acme\DemoBundle\Model\Product')
     */
    protected function getSupportedClasses()
    {
        return [
            DeliciousCookie::class
        ];
    }

    /**
     * Return an array of supported attributes. This will be called by supportsAttribute
     *
     * @return array    an array of supported attributes, i.e. array('CREATE', 'READ')
     */
    protected function getSupportedAttributes()
    {
        return [
            self::ATTRIBUTE_NOM,
            self::ATTRIBUTE_DONATE
        ];
    }

    /**
     * Perform a single access check operation on a given attribute, object and (optionally) user
     * It is safe to assume that $attribute and $object's class pass supportsAttribute/supportsClass
     * $user can be one of the following:
     *   a UserInterface object (fully authenticated user)
     *   a string               (anonymously authenticated user)
     *
     * @param string               $attribute
     * @param DeliciousCookie      $object
     * @param UserInterface|string $user
     *
     * @return bool
     */
    protected function isGranted($attribute, $object, $user = null)
    {
        if (!is_object($user)) {
            return false;
        }

        $authChecker = $this->container->get('security.authorization_checker');

        switch ($attribute) {
            case self::ATTRIBUTE_NOM:
                if ($authChecker->isGranted('ROLE_COOKIE_MONSTER')) {
                    return true;
                }

                if ($object->getBakerUsername() == $user->getUsername()) {
                    return true;
                }
                return false;
            break;
            case self::ATTRIBUTE_DONATE:
                if (stripos($object->getFlavor(), 'chocolate') === false) {
                    return true;
                }
                return false;
                break;
        }
        throw new \LogicException('How did this happen? ');
    }
}