<?php

namespace Visca\Bundle\CoreBundle\Tests\UnitTest\Sorter;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use InvalidArgumentException;
use Visca\Bundle\CoreBundle\Sorter\ArrayCollectionSorter;
use Visca\Bundle\CoreBundle\Sorter\Criteria\Criteria;

/**
 * Class ArrayCollectionSorterTest.
 */
class ArrayCollectionSorterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Car
     */
    private $ferrari;

    /**
     * @var Car
     */
    private $fordFiesta;

    /**
     * @var Car
     */
    private $seatLeon;

    /**
     * @var ArrayCollection
     */
    private $collection;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        // Expensive car with a lot of horse power
        $this->ferrari = new Car();
        $this->ferrari->setHorsePower(100);
        $this->ferrari->setPrice(100000000);
        $this->ferrari->setReleasedDate(new DateTime('2000-01-01'));

        // Cheaper car with less horse power
        $this->fordFiesta = new Car();
        $this->fordFiesta->setHorsePower(5);
        $this->fordFiesta->setPrice(10000);
        $this->fordFiesta->setReleasedDate(new DateTime('2001-01-01'));

        // Same horse price than a Fiesta but with more horse power
        $this->seatLeon = new Car();
        $this->seatLeon->setHorsePower(10);
        $this->seatLeon->setPrice(10000);
        $this->seatLeon->setReleasedDate(new DateTime('2010-01-01'));

        $this->collection = new ArrayCollection();
        $this->collection->add($this->ferrari);
        $this->collection->add($this->fordFiesta);
        $this->collection->add($this->seatLeon);
    }

    /**
     * Test the collection is sorted using DESC criteria.
     */
    public function testTheCollectionIsSortedUsingOneDescCriteria()
    {
        $criteria = new Criteria('horsePower', Criteria::DIRECTION_DESC);

        $sorter = new ArrayCollectionSorter();
        $sorter->addCriteria($criteria);

        $sortedCollection = $sorter->sort($this->collection);

        $this->assertEquals($this->ferrari, $sortedCollection->get(0));
        $this->assertEquals($this->seatLeon, $sortedCollection->get(1));
    }

    /**
     * Test the collection is sorted using ASC criteria.
     */
    public function testTheCollectionIsSortedUsingOneAscCriteria()
    {
        $criteria = new Criteria('horsePower', Criteria::DIRECTION_ASC);

        $sorter = new ArrayCollectionSorter();
        $sorter->addCriteria($criteria);

        $sortedCollection = $sorter->sort($this->collection);

        $this->assertEquals($this->fordFiesta, $sortedCollection->get(0));
        $this->assertEquals($this->seatLeon, $sortedCollection->get(1));
    }

    /**
     * Test that an Exception is throw if not Criteria are provided.
     *
     * @expectedException Exception
     */
    public function testAnExceptionIsThrownIfThereAreNoCriteria()
    {
        $sorter = new ArrayCollectionSorter();
        $sorter->sort($this->collection);
    }

    /**
     * Test that an InvalidArgumentException is thrown if a property
     * described in a Criteria does not exist.
     *
     * @expectedException InvalidArgumentException
     */
    public function testAnExceptionIsThrownIfPropertyNotAvailable()
    {
        $criteria = new Criteria('thisPropertyDoesNotExist', Criteria::DIRECTION_ASC);

        $sorter = new ArrayCollectionSorter();
        $sorter->addCriteria($criteria);

        $sorter->sort($this->collection);
    }

    /**
     * Test that an InvalidArgumentException is thrown if the direction
     * is invalid.
     *
     * @expectedException InvalidArgumentException
     */
    public function testAnExceptionIsThrownIfTheDirectionIsInvalid()
    {
        $invalidDirection = -1;
        $criteria = new Criteria('horsePower', $invalidDirection);

        $sorter = new ArrayCollectionSorter();
        $sorter->addCriteria($criteria);

        $sorter->sort($this->collection);
    }

    /**
     * Test sorting with more than one criteria.
     */
    public function testSortWithMultiCriteria()
    {
        $priceCriteria = new Criteria('price', Criteria::DIRECTION_ASC);
        $horsePowerCriteria = new Criteria('horsePower', Criteria::DIRECTION_DESC);

        // Make a sorter to find the cheapest car with the more horse power
        $sorter = new ArrayCollectionSorter();
        $sorter->addCriteria($priceCriteria);
        $sorter->addCriteria($horsePowerCriteria);

        $sortedCollection = $sorter->sort($this->collection);

        $this->assertEquals($this->seatLeon, $sortedCollection->get(0));
    }

    /**
     * Test that an InvalidArgumentException is thrown if the collection
     * is not an ArrayCollection.
     *
     * @expectedException InvalidArgumentException
     */
    public function testAnExceptionIsThrownIfSomethingElseThanCollectionGiven()
    {
        $priceCriteria = new Criteria('price', Criteria::DIRECTION_ASC);

        $sorter = new ArrayCollectionSorter();
        $sorter->addCriteria($priceCriteria);

        $array = [
            $this->ferrari,
            $this->seatLeon,
        ];

        $sorter->sort($array);
    }

    /**
     * Test to sort with dates DESC.
     */
    public function testSortingWithDateDesc()
    {
        $dateCriteria = new Criteria('releasedDate', Criteria::DIRECTION_DESC);

        $sorter = new ArrayCollectionSorter();
        $sorter->addCriteria($dateCriteria);

        $sortedCollection = $sorter->sort($this->collection);

        $this->assertEquals($this->seatLeon, $sortedCollection->get(0));
    }

    /**
     * Test to sort with dates ASC.
     */
    public function testSortingWithDateAsc()
    {
        $dateCriteria = new Criteria('releasedDate', Criteria::DIRECTION_ASC);

        $sorter = new ArrayCollectionSorter();
        $sorter->addCriteria($dateCriteria);

        $sortedCollection = $sorter->sort($this->collection);

        $this->assertEquals($this->ferrari, $sortedCollection->get(0));
    }
}
