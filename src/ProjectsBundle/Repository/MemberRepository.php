<?php

namespace ProjectsBundle\Repository;


use AppBundle\Repository\AbstractRepository;
use ProjectsBundle\Entity\Member;
use ProjectsBundle\Entity\Project;

class MemberRepository extends AbstractRepository
{
    const FIELD_EMPLOYEE_NAME = 'name';

    /**
     * @param Project $project
     * @param array $options
     * @return Member[]
     */
    public function getByProject(Project $project, array $options)
    {
        $builder = $this->createQueryBuilder('m');
        $builder
            ->join('m.employee', 'e')
            ->where('m.project = :project')
            ->andWhere($builder->expr()->like('e.name', ':employee'))
            ->setParameter(':project', $project)
            ->setParameter(':employee', $options[self::FIELD_EMPLOYEE_NAME]);
        ;
        $this->paginationWrapper($builder, $options);
        $this->sortingWrapper($builder, $options, 'e');

        return $builder->getQuery()->getResult();
    }
}