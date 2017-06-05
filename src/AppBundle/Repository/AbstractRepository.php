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

    protected function sortingWrapper(QueryBuilder &$builder, $options, $alias = '')
    {
        if (array_key_exists(self::OPTION_SORT, $options)) {
            if (!$alias) {
                $alias = $builder->getRootAliases()[0];
            }
            $field = key($options[self::OPTION_SORT]);
            $sort = current($options[self::OPTION_SORT]);
            $builder
                ->orderBy($alias . '.' .$field, $sort);
        }

    }
}