<?php

namespace AppBundle\Entity;


use AppBundle\Entity\Exception\InvalidDataException;

interface SerializableInterface
{
    /**
     * @return string
     */
    public function serialize();

    /**
     * @param $json
     * @return SerializableInterface
     * @throws InvalidDataException
     */
    public static function deserialize($json);
}