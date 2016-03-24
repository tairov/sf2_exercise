<?php
namespace Yoda\EventBundle\Twig;


class EventExtension extends \Twig_Extension
{

    public function getName()
    {
        return 'event';
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('ago', [$this, 'calculateAgo'])
        );
    }

    public function calculateAgo(\DateTime $dt)
    {
        $now = new \DateTime();
        return $now->diff($dt)->days . ' days ago';
        
    }


}