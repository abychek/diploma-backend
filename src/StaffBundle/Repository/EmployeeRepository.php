<?php

namespace StaffBundle\Repository;


use AppBundle\Entity\AbstractResourceEntity;
use AppBundle\Repository\AbstractRepository;

class EmployeeRepository extends AbstractRepository
{
    const FIELD_NAME = 'name';
    const FIELD_SKILLS = 'skills';
    const FIELD_PROJECTS = 'projects';

    /**
     * @param array $options
     * @return AbstractResourceEntity[]
     */
    public function getSortedByName(array $options)
    {
        $builder = $this->createQueryBuilder('e');
        $builder
            ->where($builder->expr()->like('e.name', ':name'))
            ->setParameter(':name', $options[self::FIELD_NAME])
        ;
        if (array_key_exists(self::FIELD_PROJECTS, $options)) {
            $builder
                ->join('e.memberships', 'm')
                ->join('m.project', 'p')
                ->andWhere($builder->expr()->in('p.id', $options[self::FIELD_PROJECTS]));
        }
        if (array_key_exists(self::FIELD_SKILLS, $options)) {
            $builder
                ->join('e.technologies', 't')
                ->andWhere($builder->expr()->in('t.id', $options[self::FIELD_SKILLS]));
        }
        $this->paginationWrapper($builder, $options);

        return $builder->getQuery()->getResult();
    }
}