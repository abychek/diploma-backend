<?php

namespace StaffBundle\DataFixtures\ORM;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use StaffBundle\Entity\Employee;
use StaffBundle\Entity\Position;
use TechnologiesBundle\Entity\Technology;

class EmployeesDataLoader implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist($phpDeveloper = $this->createPosition('PHP Developer', Position::STATUS_AVAILABLE));
        $manager->persist($frontendDeveloper = $this->createPosition('Frontend Developer', Position::STATUS_AVAILABLE));

        $manager->persist($php = $this->createTechnology('PHP'));
        $manager->persist($js = $this->createTechnology('JavaScript'));

        $manager->persist($this->createEmployee('Oleksii Bychek', $phpDeveloper, $php));
        $manager->persist($this->createEmployee('Grygory Reshetnyak', $frontendDeveloper, $js));
        $manager->flush();
    }

    /**
     * @param $name
     * @param Position $position
     * @param Technology $technology
     * @return Employee
     */
    private function createEmployee($name, Position $position, Technology $technology)
    {
        $employee = new Employee();
        $employee
            ->setName($name)
            ->setPosition($position)
            ->setStatus(Employee::STATUS_AVAILABLE);
        $employee->getTechnologies()->add($technology);

        return $employee;
    }

    /**
     * @param $name
     * @param $status
     * @return Position
     */
    private function createPosition($name, $status)
    {
        $position = new Position();
        $position->setName($name)->setStatus($status);

        return $position;
    }

    private function createTechnology($title)
    {
        $technology = new Technology();
        $technology->setTitle($title)->setStatus(Technology::STATUS_AVAILABLE);

        return $technology;
    }
}