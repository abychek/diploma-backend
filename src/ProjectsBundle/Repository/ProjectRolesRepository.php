<?php

namespace ProjectsBundle\Repository;


use AppBundle\Repository\AbstractRepository;

class ProjectRolesRepository extends AbstractRepository
{
    const FIELD_ROLE_NAME = 'roleName';

    /**
     * @param array $options
     * @return \AppBundle\Entity\AbstractResourceEntity[]
     */
    public function getSortedByRoleName(array $options)
    {
        return $this->getByOptions(self::FIELD_ROLE_NAME, $options);
    }
}