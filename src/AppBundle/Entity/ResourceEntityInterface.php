<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 30.05.17
 * Time: 10:31
 */

namespace AppBundle\Entity;


interface ResourceEntityInterface extends SerializableInterface
{
    const STATUS_AVAILABLE = 'available';
    const STATUS_UNAVAILABLE = 'unavailable';
}