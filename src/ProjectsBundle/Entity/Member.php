<?php

namespace ProjectsBundle\Entity;


use AppBundle\Entity\AbstractResourceEntity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use StaffBundle\Entity\Employee;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Member
 * @package ProjectsBundle\Entity
 * @ORM\Entity(repositoryClass="ProjectsBundle\Repository\MemberRepository")
 * @ORM\Table(name="Members")
 */
class Member extends AbstractResourceEntity
{
    /**
     * @var Employee
     * @OneToMany(targetEntity="StaffBundle\Entity\Employee", mappedBy="memberships")
     */
    private $employee;

    /**
     * @var ProjectRole
     * @OneToMany(targetEntity="ProjectsBundle\Entity\ProjectRole", mappedBy="id")
     */
    private $role;

    /**
     * @var Project
     * @ManyToOne(targetEntity="ProjectsBundle\Entity\Project", inversedBy="members")
     * @JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
     * @return Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @param Employee $employee
     * @return Member
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;
        return $this;
    }

    /**
     * @return ProjectRole
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param ProjectRole $role
     * @return Member
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     * @return Member
     */
    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id'       => $this->getId(),
            'employee' => [
                'id'       => $this->getEmployee()->getId(),
                'name'     => $this->getEmployee()->getName(),
                'position' => $this->getEmployee()->getPosition()->getName()
            ],
            'role'     => $this->getRole()->getRoleName()
        ];
    }
}