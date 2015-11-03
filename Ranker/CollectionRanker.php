<?php

namespace Visca\Bundle\CoreBundle\Ranker;

use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use Visca\Bundle\CoreBundle\Ranker\Interfaces\RankerInterface;
use Visca\Bundle\CoreBundle\Ranker\Item\Interfaces\RankableItemInterface;
use Visca\Bundle\CoreBundle\Sorter\Criteria\Criteria;

/**
 * Class CollectionRanker.
 */
class CollectionRanker implements RankerInterface
{
    /**
     * @var Criteria[]
     */
    private $criteria;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->criteria = [];
    }

    /**
     * Set the list of criteria.
     *
     * @param Criteria[] $criteria List of criteria
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * {@inheritdoc}
     */
    public function rank(Collection $collection)
    {
        $this->applyRanks($collection);

        return $collection;
    }

    /**
     * Set the rank properties of each collection item.
     *
     * @param Collection $collection
     *
     * @throws InvalidArgumentException
     */
    private function applyRanks(Collection $collection)
    {
        $currentRank = 1;

        /** @var RankableItemInterface $previousItem */
        $previousItem = null;

        foreach ($collection as $nextItem) {
            /** @var RankableItemInterface $nextItem */
            if (!$nextItem instanceof RankableItemInterface) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Collection items must implement RankableItemInterface'
                    )
                );
            }

            if ($previousItem !== null) {
                $areItemsExAequo = $this->areItemsExAequo(
                    $previousItem,
                    $nextItem
                );
            } else {
                $areItemsExAequo = false;
            }

            if ($areItemsExAequo) {
                $nextItem->setRank($previousItem->getRank());
            } else {
                $nextItem->setRank($currentRank);
            }

            $previousItem = $nextItem;
            ++$currentRank;
        }
    }

    /**
     * Check that both item are ex aequo.
     *
     * @param RankableItemInterface $firstItem
     * @param RankableItemInterface $secondItem
     *
     * @return bool True if they are ex aequo otherwise false
     */
    private function areItemsExAequo(
        RankableItemInterface $firstItem,
        RankableItemInterface $secondItem
    ) {
        foreach ($this->criteria as $nextCriteria) {
            $getterMethod = 'get'.$nextCriteria->getPropertyName();

            $propertiesEqual = $firstItem->$getterMethod()
                == $secondItem->$getterMethod();

            if (!$propertiesEqual) {
                return false;
            }
        }

        return true;
    }
}
