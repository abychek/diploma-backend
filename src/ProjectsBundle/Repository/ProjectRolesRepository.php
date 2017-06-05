<?php

namespace ProjectsBundle\Repository;


use AppBundle\Repository\AbstractRepository;

class ProjectRolesRepository extends AbstractRepository
{
    const FIELD_ROLE_NAME = 'name';

    /**
     * @param array $options
     * @return \AppBundle\Entity\AbstractResourceEntity[]
     */
    public function getByRoleName(array $options)
    {
        $builder = $this->createQueryBuilder('pr');
        $builder
            ->where($builder->expr()->like('pr.roleName', ':role'))
            ->setParameter(':role', $options[self::FIELD_ROLE_NAME])
        ;
        $this->paginationWrapper($builder, $options);
        $this->sortingWrapper($builder, $options);

        return $builder->getQuery()->getResult();
    }
}