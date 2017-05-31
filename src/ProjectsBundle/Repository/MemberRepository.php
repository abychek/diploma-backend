<?php

namespace ProjectsBundle\Repository;


use AppBundle\Repository\AbstractRepository;
use ProjectsBundle\Entity\Project;

class MemberRepository extends AbstractRepository
{
    public function getByProject(Project $project, array $options)
    {
        $builder = $this->createQueryBuilder('m');
    }
}