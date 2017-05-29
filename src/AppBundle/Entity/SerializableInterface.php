<?php

namespace AppBundle\Entity;


use AppBundle\Entity\Exception\InvalidDataException;

interface SerializableInterface
{
    /**
     * @return array
     */
    public function toArray();

    /**
     * @param $json
     * @return SerializableInterface
     * @throws InvalidDataException
     */
    public static function toObject($json);
}