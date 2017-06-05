<?php

namespace AppBundle\Repository;


use AppBundle\Entity\AbstractResourceEntity;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractRepository extends EntityRepository implements ResourceRepository
{
    /**
     * @param QueryBuilder $builder
     * @param $options
     */
    protected function paginationWrapper(QueryBuilder &$builder, $options)
    {
        $builder
            ->setFirstResult($options[self::OPTION_FROM])
            ->setMaxResults($options[self::OPTION_FROM] + $options[self::OPTION_SIZE]);
    }
}