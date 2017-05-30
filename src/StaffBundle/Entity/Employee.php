<?php

namespace StaffBundle\Entity;

use AppBundle\Entity\AbstractResourceEntity;
use AppBundle\Entity\Exception\InvalidDataException;
use AppBundle\Entity\ResourceEntityInterface;
use AppBundle\Entity\SerializableInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;


/**
 * @ORM\Entity(repositoryClass="StaffBundle\Repository\EmployeeRepository")
 * @ORM\Table(name="employees")
 */
class Employee extends AbstractResourceEntity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Employee
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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