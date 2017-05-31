<?php

namespace ProjectsBundle\Repository;


use AppBundle\Entity\AbstractResourceEntity;
use AppBundle\Repository\AbstractRepository;

class ProjectRepository extends AbstractRepository
{
    const FIELD_TITLE = 'title';

    /**
     * @param array $options
     * @return AbstractResourceEntity[]
     */
    public function getSortedByTitle(array $options)
    {
        return $this->getSortedBy(self::FIELD_TITLE, $options);
    }
}