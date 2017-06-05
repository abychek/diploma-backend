<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 01.06.17
 * Time: 9:49
 */

namespace ProjectsBundle\DataFixtures\ORM;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ProjectsBundle\Entity\Member;
use ProjectsBundle\Entity\Project;
use ProjectsBundle\Entity\ProjectRole;
use StaffBundle\Entity\Employee;
use StaffBundle\Entity\Position;

class ProjectsDataLoader implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist($yandex = $this->createProject('Yandex', 'Yandex search engine'));
        $manager->flush();

        $manager->persist($google = $this->createProject('Google', 'Google search engine'));
        $manager->flush();

        $manager->persist($javaDeveloper = $this->createPosition('Java Developer.'));
        $manager->flush();

        $manager->persist($MartinFowler = $this->createEmployee('Martin Fowler', $javaDeveloper));
        $manager->flush();

        $manager->persist($lead = $this->createProjectRole('Lead'));
        $manager->flush();

        $manager->persist($designer = $this->createPosition('Designer.'));
        $manager->flush();

        $manager->persist($SteveJobs = $this->createEmployee('Steve Jobs', $designer));
        $manager->flush();

        $manager->persist($developer = $this->createProjectRole('Developer'));
        $manager->flush();

        $manager->persist($leadMember = $this->createMember($MartinFowler, $lead, $google));
        $manager->flush();

        $manager->persist($developerMember = $this->createMember($SteveJobs, $developer, $google));
        $manager->flush();

        $google->getMembers()->add($leadMember);
        $google->getMembers()->add($developerMember);

        $technologies = $manager->getRepository('TechnologiesBundle:Technology')->findAll();
        $google->setTechnologies($technologies);
        $manager->persist($google);
        $manager->flush();

    }

    /**
     * @param string $title
     * @param string $description
     * @return Project
     */
    private function createProject($title, $description)
    {
        $project = new Project();
        $project
            ->setTitle($title)
            ->setDescription($description)
            ->setStartDate(new \DateTime())
            ->setStatus(Project::STATUS_AVAILABLE);

        return $project;
    }

    /**
     * @param Employee $employee
     * @param ProjectRole $role
     * @param Project $project
     * @return Member
     */
    private function createMember(Employee $employee, ProjectRole $role, Project $project)
    {
        $member = new Member();
        $member
            ->setEmployee($employee)
            ->setRole($role)
            ->setProject($project)
            ->setStatus(Member::STATUS_AVAILABLE);

        return $member;
    }

    /**
     * @param $name
     * @param $position
     * @return Employee
     */
    private function createEmployee($name, $position)
    {
        $employee = new Employee();
        $employee
            ->setName($name)
            ->setPosition($position)
            ->setStatus(Employee::STATUS_AVAILABLE);

        return $employee;
    }

    /**
     * @param $name
     * @return Position
     */
    private function createPosition($name)
    {
        $position = new Position();
        $position
            ->setName($name)
            ->setStatus(Position::STATUS_AVAILABLE);

        return $position;
    }

    /**
     * @param $roleName
     * @return ProjectRole
     */
    private function createProjectRole($roleName)
    {
        $role = new ProjectRole();
        $role
            ->setRoleName($roleName)
            ->setStatus(ProjectRole::STATUS_AVAILABLE);

        return $role;
    }
}