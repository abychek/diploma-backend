<?php

namespace StaffBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use StaffBundle\Entity\Employee;

class EmployeesDataLoader implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createEmployee('Oleksii Bychek', Employee::STATUS_AVAILABLE));
        $manager->persist($this->createEmployee('Grygory Reshetnyak', Employee::STATUS_AVAILABLE));
        $manager->flush();
    }

    private function createEmployee($name, $status)
    {
        return (new Employee())->setName($name)->setStatus($status);
    }
}