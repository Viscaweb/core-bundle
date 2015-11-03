<?php

namespace Visca\Bundle\CoreBundle\Sorter\Interfaces;

/**
 * Interface SorterInterface.
 */
interface SorterInterface
{
    /**
     * Sort a collection and return the result.
     *
     * @param mixed $collection The collection to sort
     *
     * @return mixed
     */
    public function sort($collection);
}
