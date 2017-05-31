<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 31.05.17
 * Time: 20:52
 */

namespace ProjectsBundle\Entity;


use AppBundle\Entity\AbstractResourceEntity;
use Doctrine\ORM\Mapping as ORM;

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