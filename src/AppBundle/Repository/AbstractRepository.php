<?php

namespace AppBundle\Repository;


use AppBundle\Entity\AbstractResourceEntity;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractRepository extends EntityRepository implements ResourceRepository
{
    /**
     * @param $field
     * @param array $options
     * @return AbstractResourceEntity[]
     */
    public function getSortedBy($field, array $options)
    {
        $builder = $this->createQueryBuilder('e');

        $builder->orderBy('e.'.$field, 'ASC');
        $this->paginationWrapper($builder, $options);

        return $builder->getQuery()->getResult();
    }

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