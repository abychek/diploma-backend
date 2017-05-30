<?php

namespace AppBundle\Entity;

abstract class AbstractResourceEntity
{
    const STATUS_AVAILABLE = 'available';
    const STATUS_UNAVAILABLE = 'unavailable';

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $status;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return array
     */
    abstract public function toArray();
}