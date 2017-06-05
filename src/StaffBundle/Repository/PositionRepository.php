<?php

namespace StaffBundle\Repository;


use AppBundle\Entity\AbstractResourceEntity;
use AppBundle\Repository\AbstractRepository;

class PositionRepository extends AbstractRepository
{
    const FIELD_NAME = 'name';

    /**
     * @param array $options
     * @return AbstractResourceEntity[]
     */
    public function getSortedByName(array $options)
    {
        $builder = $this->createQueryBuilder('p');
        $builder
            ->where($builder->expr()->like('p.name', ':name'))
            ->setParameter(':name', $options[self::FIELD_NAME])
        ;
        $this->paginationWrapper($builder, $options);
        $this->sortingWrapper($builder, $options);

        return $builder->getQuery()->getResult();
    }
}