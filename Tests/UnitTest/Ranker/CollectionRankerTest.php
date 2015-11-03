<?php

namespace Visca\Bundle\CoreBundle\Tests\UnitTest\Ranker;

use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;
use Visca\Bundle\CoreBundle\Ranker\CollectionRanker;
use Visca\Bundle\CoreBundle\Sorter\Criteria\Criteria;
use Visca\Bundle\CoreBundle\Tests\UnitTest\Sorter\Car;

/**
 * Class CollectionRankerTest.
 */
class CollectionRankerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RankableCar
     */
    private $ferrari;

    /**
     * @var RankableCar
     */
    private $fordFiesta;

    /**
     * @var RankableCar
     */
    private $seatLeon;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        // Expensive car with a lot of horse power
        $this->ferrari = new RankableCar();
        $this->ferrari->setHorsePower(100);
        $this->ferrari->setPrice(100000000);

        // Cheaper car with less horse power
        $this->fordFiesta = new RankableCar();
        $this->fordFiesta->setHorsePower(5);
        $this->fordFiesta->setPrice(10000);

        // Same horse price than a Fiesta but with more horse power
        $this->seatLeon = new RankableCar();
        $this->seatLeon->setHorsePower(10);
        $this->seatLeon->setPrice(10000);
    }

    /**
     * Test the ranker with a single criteria.
     */
    public function testRankerWithSingleCriteria()
    {
        $criteria = [];
        $criteria[] = new Criteria('price', Criteria::DIRECTION_DESC);

        // Collections sort by price DESC - Already sorted
        $collection = new ArrayCollection();
        $collection->add($this->ferrari);
        $collection->add($this->fordFiesta);
        $collection->add($this->seatLeon);

        $ranker = new CollectionRanker();
        $ranker->setCriteria($criteria);

        $rankedCollection = $ranker->rank($collection);

        $this->assertEquals(1, $rankedCollection->get(0)->getRank());
        $this->assertEquals(2, $rankedCollection->get(1)->getRank());
        $this->assertEquals(2, $rankedCollection->get(2)->getRank());
    }

    /**
     * Test ranker throw an exception if items are not implementing
     * the RankableItemInterface.
     *
     * @expectedException InvalidArgumentException
     */
    public function testRankerThrowAnExceptionIfItemsAreNotImplementingTheInterface()
    {
        $carWithoutTheInterface = new Car();

        $collection = new ArrayCollection();
        $collection->add($carWithoutTheInterface);

        $criteria = [];
        $criteria[] = new Criteria('price', Criteria::DIRECTION_DESC);

        $ranker = new CollectionRanker();
        $ranker->setCriteria($criteria);

        $ranker->rank($collection);
    }
}
