<?php

namespace StaffBundle\Repository;


use AppBundle\Entity\ResourceEntityInterface;
use AppBundle\Repository\ResourceRepository;
use Doctrine\ORM\EntityRepository;

abstract class AbstractRepository extends EntityRepository implements ResourceRepository
{
    /**
     * @param array $options
     * @return ResourceEntityInterface[]
     */
    public function getSortedByName(array $options)
    {
        $builder = $this->createQueryBuilder('e');

        $builder
            ->orderBy('e.name', 'ASC')
            ->setFirstResult($options[self::OPTION_FROM])
            ->setMaxResults($options[self::OPTION_FROM] + $options[self::OPTION_SIZE]);

        return $builder->getQuery()->getResult();
    }
}