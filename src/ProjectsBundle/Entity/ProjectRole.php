<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 31.05.17
 * Time: 20:52
 */

namespace ProjectsBundle\Entity;


use AppBundle\Entity\AbstractResourceEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass="ProjectsBundle\Repository\ProjectRolesRepository")
 * @ORM\Table(name="project_roles")
 */
class ProjectRole extends AbstractResourceEntity
{
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $roleName;

    /**
     * @var ArrayCollection|Member[]
     * @OneToMany(targetEntity="ProjectsBundle\Entity\Member", mappedBy="project_roles")
     */
    private $memberships;

    /**
     * @return string
     */
    public function getRoleName()
    {
        return $this->roleName;
    }

    /**
     * @param string $roleName
     * @return ProjectRole
     */
    public function setRoleName($roleName)
    {
        $this->roleName = $roleName;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id'   => $this->getId(),
            'name' => $this->getRoleName()
        ];
    }
}