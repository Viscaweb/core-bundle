<?php

namespace Visca\Bundle\CoreBundle\Sorter\Criteria;

/**
 * Class Criteria.
 */
class Criteria
{
    const DIRECTION_ASC = 1;
    const DIRECTION_DESC = 2;

    /**
     * @var String
     */
    private $propertyName;

    /**
     * @var int DIRECTION_*
     */
    private $direction;

    /**
     * @param string $propertyName The name of the object property
     * @param int    $direction    Direction to sort DIRECTION_*
     */
    public function __construct($propertyName, $direction)
    {
        $this->propertyName = $propertyName;
        $this->direction = $direction;
    }

    /**
     * @return String
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @return int
     */
    public function getDirection()
    {
        return $this->direction;
    }
}
