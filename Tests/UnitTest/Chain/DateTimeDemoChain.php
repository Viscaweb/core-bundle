<?php

namespace Visca\Bundle\CoreBundle\Tests\UnitTest\Chain;

use DateTime;
use Visca\Bundle\CoreBundle\Chain\Abstracts\AbstractChain;

/**
 * Class DateTimeDemoChain.
 */
class DateTimeDemoChain extends AbstractChain
{
    /**
     * @param string   $id
     * @param DateTime $date
     */
    public function attach($id, DateTime $date)
    {
        $this->addItem($id, $date);
    }

    /**
     * @param string $id
     */
    public function detach($id)
    {
        $this->removeItem($id);
    }

    /**
     * @param string $id
     */
    public function get($id)
    {
        return $this->getItem($id);
    }
}
