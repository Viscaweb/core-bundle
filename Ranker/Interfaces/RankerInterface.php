<?php

namespace Visca\Bundle\CoreBundle\Ranker\Interfaces;

use Doctrine\Common\Collections\Collection;

/**
 * Interface RankerInterface.
 *
 * A ranker is used to define the rank of each items of a collection
 */
interface RankerInterface
{
    /**
     * Rank a collection of RankableItemInterface.
     *
     * @param Collection $collection The collection to rank its items
     *
     * @return Collection
     */
    public function rank(Collection $collection);
}
