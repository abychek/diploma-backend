<?php

namespace StaffBundle\Entity;

use AppBundle\Entity\AbstractResourceEntity;
use AppBundle\Entity\Exception\InvalidDataException;
use AppBundle\Entity\ResourceEntityInterface;
use AppBundle\Entity\SerializableInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use ProjectsBundle\Entity\Member;


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
     * @ManyToOne(targetEntity="ProjectsBundle\Entity\Member", inversedBy="employee")
     * @JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $memberships;

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
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'position' => $this->getPosition()->getName(),
            'status' => $this->getStatus()
        ];
    }
}