<?php

namespace Visca\Bundle\CoreBundle\Tests\UnitTest\Ranker;

use Visca\Bundle\CoreBundle\Ranker\Item\Interfaces\RankableItemInterface;
use Visca\Bundle\CoreBundle\Tests\UnitTest\Sorter\Car;

/**
 * Class RankableCar.
 *
 * Object use for testing purpose
 */
class RankableCar extends Car implements RankableItemInterface
{
    /**
     * @var int
     */
    private $rank;

    /**
     * {@inheritdoc}
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    /**
     * {@inheritdoc}
     */
    public function getRank()
    {
        return $this->rank;
    }
}
