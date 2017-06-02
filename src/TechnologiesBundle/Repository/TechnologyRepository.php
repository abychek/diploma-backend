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
        return $this->getSortedBy(self::FIELD_TITLE, $options);
    }
}