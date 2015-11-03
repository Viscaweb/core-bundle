<?php

namespace Visca\Bundle\CoreBundle\Tests\UnitTest\Sorter;

use DateTime;

/**
 * Class Car.
 *
 * Object use for testing purpose
 */
class Car
{
    /**
     * @var int
     */
    private $horsePower;

    /**
     * @var int
     */
    private $price;

    /**
     * @var DateTime
     */
    private $releasedDate;

    /**
     * @return int
     */
    public function getHorsePower()
    {
        return $this->horsePower;
    }

    /**
     * @param int $horsePower
     *
     * @return Car
     */
    public function setHorsePower($horsePower)
    {
        $this->horsePower = $horsePower;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     *
     * @return Car
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getReleasedDate()
    {
        return $this->releasedDate;
    }

    /**
     * @param DateTime $releasedDate
     *
     * @return Car
     */
    public function setReleasedDate($releasedDate)
    {
        $this->releasedDate = $releasedDate;

        return $this;
    }
}
