<?php

namespace StaffBundle\Entity;

use AppBundle\Entity\AbstractResourceEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use ProjectsBundle\Entity\Member;
use TechnologiesBundle\Entity\Technology;


/**
 * @ORM\Entity(repositoryClass="StaffBundle\Repository\EmployeeRepository")
 * @ORM\Table(name="employees")
 */
class Employee extends AbstractResourceEntity
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @var Position
     * @ManyToOne(targetEntity="StaffBundle\Entity\Position")
     * @JoinColumn(name="position_id", referencedColumnName="id")
     */
    private $position;

    /**
     * @var Member[]
     * @OneToMany(targetEntity="ProjectsBundle\Entity\Member", mappedBy="employee")
     */
    private $memberships;

    /**
     * @var ArrayCollection|Technology[]
     * @ORM\ManyToMany(targetEntity="TechnologiesBundle\Entity\Technology", inversedBy="employees")
     * @JoinTable(name="employee_technologies")
     */
    private $technologies;

    /**
     * Employee constructor.
     */
    public function __construct()
    {
        $this->memberships = new ArrayCollection();
        $this->technologies = new ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Employee
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param Position $position
     * @return Employee
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return Member[]
     */
    public function getMemberships()
    {
        return $this->memberships;
    }

    /**
     * @param Member[] $memberships
     * @return Employee
     */
    public function setMemberships($memberships)
    {
        $this->memberships = $memberships;
        return $this;
    }

    /**
     * @return ArrayCollection|Technology[]
     */
    public function getTechnologies()
    {
        return $this->technologies;
    }

    /**
     * @param ArrayCollection|Technology[] $technologies
     * @return Employee
     */
    public function setTechnologies($technologies)
    {
        $this->technologies = $technologies;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $technologies = [];
        foreach ($this->getTechnologies() as $technology) {
            $technologies[] = $technology->toArray();
        }

        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'position' => $this->getPosition()->getName(),
            'skills' => $technologies,
            'status' => $this->getStatus()
        ];
    }
}