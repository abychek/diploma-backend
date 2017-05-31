<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 30.05.17
 * Time: 10:29
 */

namespace StaffBundle\Entity;


use AppBundle\Entity\AbstractResourceEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="StaffBundle\Repository\PositionRepository")
 * @ORM\Table(name="positions")
 */
class Position extends AbstractResourceEntity
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

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
}