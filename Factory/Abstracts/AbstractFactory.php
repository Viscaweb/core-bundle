<?php

namespace Visca\Bundle\CoreBundle\Factory\Abstracts;

/**
 * Class AbstractFactory.
 */
abstract class AbstractFactory
{
    /**
     * Creates an instance of an entity.
     *
     * @return Object Empty entity
     */
    abstract public function create();
}
