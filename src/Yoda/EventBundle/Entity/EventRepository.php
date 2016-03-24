<?php

namespace Yoda\EventBundle\Entity;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends \Doctrine\ORM\EntityRepository
{
    public function getUpcomingEvents($max = null)
    {
        $qb = $this->createQueryBuilder('e')
            ->addOrderBy('e.time', 'ASC')
            ->where('e.time > :now')
            ->setParameter('now', new \DateTime());

        if ($max) {
            $qb->setMaxResults($max);
        }

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Event[]
     */
    public function getRecentlyUpdatedEvents()
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.createdAt > :since')
            ->setParameter('since', new \DateTime('24 hours ago'))
            ->getQuery()
            ->execute();
    }
}
