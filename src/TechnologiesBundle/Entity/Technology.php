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
use Doctrine\ORM\Mapping\ManyToMany;
use ProjectsBundle\Entity\Project;
use StaffBundle\Entity\Employee;

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
     * @var Employee[];
     * @ManyToMany(targetEntity="StaffBundle\Entity\Employee", mappedBy="technologies")
     */
    private $employees;

    /**
     * @var Project[]
     */
    private $projects;

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
     * @return Employee[]
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * @param Employee[] $employees
     * @return Technology
     */
    public function setEmployees($employees)
    {
        $this->employees = $employees;
        return $this;
    }

    /**
     * @return Project[]
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @param Project[] $projects
     */
    public function setProjects($projects)
    {
        $this->projects = $projects;
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