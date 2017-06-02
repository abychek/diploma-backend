<?php

namespace TechnologiesBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TechnologiesBundle\Entity\Technology;

class TechnologiesDataLoader implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createTechnology('DDD'));
        $manager->persist($this->createTechnology('SCRUM'));
        $manager->persist($this->createTechnology('TDD'));

        $manager->flush();
    }

    /**
     * @param $title
     * @return Technology
     */
    private function createTechnology($title)
    {
        $technology = new Technology();
        $technology->setTitle($title)->setStatus(Technology::STATUS_AVAILABLE);

        return $technology;
    }
}