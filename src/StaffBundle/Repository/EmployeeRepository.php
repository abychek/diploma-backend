<?php

namespace StaffBundle\Repository;


use AppBundle\Repository\ResourceRepository;
use Doctrine\ORM\EntityRepository;
use StaffBundle\Entity\Employee;

class EmployeeRepository extends EntityRepository implements ResourceRepository
{
    /**
     * @param array $options
     * @return Employee[]
     */
    public function getSortedByName(array $options)
    {
        $builder = $this->createQueryBuilder('e');

        $builder
            ->orderBy('e.name', 'ASC')
            ->setFirstResult($options[self::OPTION_FROM])
            ->setMaxResults($options[self::OPTION_FROM] + $options[self::OPTION_SIZE]);

        return $builder->getQuery()->getResult();
    }
}