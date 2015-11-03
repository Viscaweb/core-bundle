<?php

namespace Visca\Bundle\CoreBundle\Sorter;

use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use InvalidArgumentException;
use Visca\Bundle\CoreBundle\Sorter\Criteria\Criteria;
use Visca\Bundle\CoreBundle\Sorter\Interfaces\SorterInterface;

/**
 * Class ArrayCollectionSorter.
 */
final class ArrayCollectionSorter implements SorterInterface
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
     * Add a new criteria. Order matter !
     * The first added criteria will be evaluated first.
     *
     * @param Criteria $criteria The criteria to add
     */
    public function addCriteria(Criteria $criteria)
    {
        $this->criteria[] = $criteria;
    }

    /**
     * @return Criteria[]
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param ArrayCollection $collection The collection to sort
     *
     * @return ArrayCollection
     *
     * @throws InvalidArgumentException
     */
    public function sort($collection)
    {
        if (!$collection instanceof ArrayCollection) {
            throw new InvalidArgumentException();
        }

        $iterator = $collection->getIterator();
        $iterator->uasort(
            function ($firstElement, $secondElement) {
                return $this->compare($firstElement, $secondElement);
            }
        );

        $sortedCollection = new ArrayCollection(
            iterator_to_array($iterator, false)
        );

        return $sortedCollection;
    }

    /**
     * The comparison function return an integer
     *  - less than,
     *  - equal to,
     *  - or greater
     * than zero if the first argument is considered to be respectively
     *  - less than,
     *  - equal to,
     *  - or greater
     * than the second.
     *
     * @param Object $firstElement
     * @param Object $secondElement
     *
     * @return int
     *
     * @throws Exception
     * @throws InvalidArgumentException
     */
    private function compare($firstElement, $secondElement)
    {
        if (count($this->criteria) == 0) {
            throw new Exception('No criteria are setup');
        }

        foreach ($this->criteria as $criteria) {
            $getterMethod = 'get'.$criteria->getPropertyName();

            /*
             * Check that the property getter exists
             */
            foreach ([$firstElement, $secondElement] as $element) {
                if (!method_exists($element, $getterMethod)) {
                    throw new InvalidArgumentException(
                        sprintf(
                            'One collection element does not contain getter method "%s"',
                            $getterMethod
                        )
                    );
                }
            }

            $firstValue = $firstElement->$getterMethod();
            $secondValue = $secondElement->$getterMethod();

            switch ($criteria->getDirection()) {
                case Criteria::DIRECTION_ASC:
                    $diff = $this->diff($firstValue, $secondValue);
                    break;
                case Criteria::DIRECTION_DESC:
                    $diff = $this->diff($firstValue, $secondValue) * -1;
                    break;
                default:
                    throw new InvalidArgumentException(
                        sprintf(
                            'Invalid criteria direction "%s" given',
                            $criteria->getDirection()
                        )
                    );
            }

            if ($diff != 0) {
                return $diff;
            }
        }

        return 0;
    }

    /**
     * @param $firstElement
     * @param $secondElement
     *
     * @return int
     */
    private function diff($firstElement, $secondElement)
    {
        if ($firstElement < $secondElement) {
            return -1;
        } elseif ($firstElement > $secondElement) {
            return 1;
        } else {
            return 0;
        }
    }
}
