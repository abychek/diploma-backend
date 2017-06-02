<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 02.06.17
 * Time: 16:28
 */

namespace TechnologiesBundle\Entity;


use AppBundle\Entity\AbstractResourceEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TechnologiesBundle\Repository\TechnologyRepository")
 * @ORM\Table(name="technologies")
 */
class Technology extends AbstractResourceEntity
{
    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Technology
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle()
        ];
    }
}