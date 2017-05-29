<?php

namespace StaffBundle\Entity;

use AppBundle\Entity\Exception\InvalidDataException;
use AppBundle\Entity\SerializableInterface;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="StaffBundle\Entity\EmployeeRepository")
 * @ORM\Table(name="employees")
 */
class Employee implements SerializableInterface
{
    const STATUS_AVAILABLE = 'available';
    const STATUS_UNAVAILABLE = 'unavailable';

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
     * @ORM\Column(type="string", length=20)
     */
    private $status;

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
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Employee
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return json_encode([
            'id' => $this->getId(),
            'name' => $this->getName(),
            'status' => $this->getStatus()
        ]);
    }


    /**
     * @param $json
     * @return SerializableInterface
     * @throws InvalidDataException
     */
    public static function deserialize($json)
    {
        try {
            return (new self())
                ->setId($json['id'])
                ->setName($json['name'])
                ->setStatus($json['status']);
        } catch (\Exception $exception) {
            throw new InvalidDataException($exception->getMessage());
        }
    }
}