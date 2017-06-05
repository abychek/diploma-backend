<?php

namespace ProjectsBundle\Entity;


use AppBundle\Entity\AbstractResourceEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\OneToMany;
use TechnologiesBundle\Entity\Technology;

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
     * @var Technology[]
     * @ORM\ManyToMany(targetEntity="TechnologiesBundle\Entity\Technology", inversedBy="projects")
     * @JoinTable(name="project_technologies")
     */
    private $technologies;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finishDate;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->technologies = new ArrayCollection();
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
     * @return Technology[]
     */
    public function getTechnologies()
    {
        return $this->technologies;
    }

    /**
     * @param Technology[] $technologies
     * @return Project
     */
    public function setTechnologies($technologies)
    {
        $this->technologies = $technologies;
        return $this;
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        return $this->startDate->format('d.m.Y');
    }

    /**
     * @param mixed $startDate
     * @return Project
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getFinishDate()
    {
        return $this->finishDate ? $this->finishDate->format('d.m.Y') : '';
    }

    /**
     * @param mixed $finishDate
     * @return Project
     */
    public function setFinishDate($finishDate)
    {
        $this->finishDate = $finishDate;
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

        $technologies = [];
        foreach ($this->getTechnologies() as $technology) {
            $technologies[] = $technology->toArray();
        }

        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'members' => $members,
            'technologies' => $technologies,
            'started_at' => $this->getStartDate(),
            'finished_at' => $this->getFinishDate()
        ];
    }
}