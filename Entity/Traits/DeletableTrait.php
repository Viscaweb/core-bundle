<?php

namespace Visca\Bundle\CoreBundle\Entity\Traits;

/**
 * Provide soft deletion support.
 */
trait DeletableTrait
{
    /**
     * @var string (values = yes | no)
     */
    protected $del;

    /**
     * @return string
     */
    public function getDel()
    {
        return $this->del;
    }

    /**
     * @param string $del
     *
     * @return $this
     */
    public function setDel($del)
    {
        $this->del = $del;

        return $this;
    }
}
