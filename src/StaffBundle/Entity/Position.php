<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 30.05.17
 * Time: 10:29
 */

namespace StaffBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Exception\InvalidDataException;
use AppBundle\Entity\ResourceEntityInterface;
use AppBundle\Entity\SerializableInterface;

/**
 * @ORM\Entity(repositoryClass="StaffBundle\Repository\EmployeeRepository")
 * @ORM\Table(name="positions")
 */
class Position implements ResourceEntityInterface
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
     * @return Position
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
     * @return Position
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
     * @return Position
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
            'status' => $this->getStatus()
        ];
    }

    /**
     * @param $json
     * @return SerializableInterface
     * @throws InvalidDataException
     */
    public static function toObject($json)
    {
        try {
            return (new self())->setId($json['id'])->setName($json['name'])->setStatus($json['status']);
        } catch (\Exception $exception) {
            throw new InvalidDataException($exception->getMessage());
        }
    }
}