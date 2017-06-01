<?php

namespace ProjectsBundle\Entity;


use AppBundle\Entity\AbstractResourceEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass="ProjectsBundle\Repository\ProjectRepository")
 * @ORM\Table(name="projects")
 */
class Project extends AbstractResourceEntity
{
    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var Member[]
     * @OneToMany(targetEntity="ProjectsBundle\Entity\Member", mappedBy="project")
     */
    private $members;

    /**
     * @var
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @var
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finishDate;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Project
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return ArrayCollection|Member[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param ArrayCollection|Member[] $members
     * @return Project
     */
    public function setMembers($members)
    {
        $this->members = $members;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $members = [];
        foreach ($this->getMembers() as $member) {
            $members[] = $member->toArray();
        }

        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'members' => $members
        ];
    }
}