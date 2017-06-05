<?php

namespace ProjectsBundle\Repository;


use AppBundle\Entity\AbstractResourceEntity;
use AppBundle\Repository\AbstractRepository;
use Doctrine\ORM\QueryBuilder;

class ProjectRepository extends AbstractRepository
{
    const FIELD_TITLE = 'title';
    const FIELD_TECHNOLOGIES = 'technologies';
    const FIELD_MEMBERS = 'members';

    /**
     * @param array $options
     * @return AbstractResourceEntity[]
     */
    public function getByOptions(array $options)
    {
        $builder = $this->createQueryBuilder('p');
        $builder
            ->where($builder->expr()->like('p.title', ':title'))
            ->setParameter(':title', $options[self::FIELD_TITLE])
        ;
        if (array_key_exists(self::FIELD_MEMBERS, $options)) {
            $builder
                ->join('p.members', 'm')
                ->join('m.employee', 'e')
                ->andWhere($builder->expr()->in('e.id', $options[self::FIELD_MEMBERS]));
        }
        if (array_key_exists(self::FIELD_TECHNOLOGIES, $options)) {
            $builder
                ->join('p.technologies', 't')
                ->andWhere($builder->expr()->in('t.id', $options[self::FIELD_TECHNOLOGIES]));
        }
        $this->paginationWrapper($builder, $options);

        return $builder->getQuery()->getResult();
    }
}