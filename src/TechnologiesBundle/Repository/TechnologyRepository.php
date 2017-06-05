<?php

namespace TechnologiesBundle\Repository;


use AppBundle\Repository\AbstractRepository;

class TechnologyRepository extends AbstractRepository
{
    const FIELD_TITLE = 'title';

    /**
     * @param array $options
     * @return \AppBundle\Entity\AbstractResourceEntity[]
     */
    public function getSortedByTitle(array $options)
    {
        $builder = $this->createQueryBuilder('t');
        $builder
            ->where($builder->expr()->like('t.title', ':title'))
            ->setParameter(':title', $options[self::FIELD_TITLE])
        ;
        $this->paginationWrapper($builder, $options);

        return $builder->getQuery()->getResult();
    }
}