<?php

namespace Visca\Bundle\CoreBundle\Entity\Traits;

use DateTime;

/**
 * trait for DateTimePeriod common variables and methods.
 */
trait DateTimePeriodTrait
{
    /**
     * @var DateTime
     *
     * Start at
     */
    protected $startAt;

    /**
     * @var DateTime
     *
     * End at
     */
    protected $endAt;

    /**
     * @return DateTime
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * @param DateTime $endAt
     *
     * @return $this
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param DateTime $startAt
     *
     * @return $this
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;

        return $this;
    }
}
