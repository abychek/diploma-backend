<?php

namespace StaffBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use StaffBundle\Entity\Employee;
use StaffBundle\Entity\Position;

class EmployeesDataLoader implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist($phpDeveloper = $this->createPosition('php-developer', Position::STATUS_AVAILABLE));
        $manager->persist($frontendDeveloper = $this->createPosition('frontend-developer', Position::STATUS_AVAILABLE));

        $manager->persist($this->createEmployee('Oleksii Bychek', $phpDeveloper, Employee::STATUS_AVAILABLE));
        $manager->persist($this->createEmployee('Grygory Reshetnyak', $frontendDeveloper, Employee::STATUS_AVAILABLE));
        $manager->flush();
    }

    /**
     * @param $name
     * @param Position $position
     * @param $status
     * @return Employee
     */
    private function createEmployee($name, Position $position, $status)
    {
        return (new Employee())
            ->setName($name)
            ->setPosition($position)
            ->setStatus($status);
    }

    /**
     * @param $name
     * @param $status
     * @return Position
     */
    private function createPosition($name, $status)
    {
        return (new Position())->setName($name)->setStatus($status);
    }
}