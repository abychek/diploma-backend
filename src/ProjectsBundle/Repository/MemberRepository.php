<?php

namespace ProjectsBundle\Repository;


use AppBundle\Repository\AbstractRepository;
use ProjectsBundle\Entity\Member;
use ProjectsBundle\Entity\Project;

class MemberRepository extends AbstractRepository
{

    /**
     * @param Project $project
     * @param array $options
     * @return Member[]
     */
    public function getByProject(Project $project, array $options)
    {
        $builder = $this->createQueryBuilder('m');
        $builder
            ->where('m.project = :project')
            ->setParameter(':project', $project);
        $this->paginationWrapper($builder, $options);

        return $builder->getQuery()->getResult();
    }
}