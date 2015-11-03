<?php

namespace Visca\Bundle\CoreBundle\Ranker\Item\Interfaces;

/**
 * Interface RankableItemInterface.
 */
interface RankableItemInterface
{
    /**
     * @param int $rank The rank
     */
    public function setRank($rank);

    /**
     * @return int
     */
    public function getRank();
}
