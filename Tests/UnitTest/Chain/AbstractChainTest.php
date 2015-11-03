<?php

namespace Visca\Bundle\CoreBundle\Tests\UnitTest\Chain;

use DateTime;
use InvalidArgumentException;
use OutOfRangeException;

/**
 * Class AbstractChainTest.
 */
abstract class AbstractChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DateTimeDemoChain
     */
    private $chain;

    /**
     * {@inheritdoc}
     */
    public function setup()
    {
        $this->chain = new DateTimeDemoChain();
    }

    /**
     * Test that an exception is thrown if an invalid id is used.
     *
     * @expectedException InvalidArgumentException
     */
    public function testAnExceptionIsThrownIfAnInvalidIdIsUsed()
    {
        /*
         * Use null instead of a valid string
         */
        $this->chain->attach(null, new DateTime());
    }

    /**
     * Test that an exception is thrown when you want to get an item
     * that does not exist.
     *
     * @expectedException OutOfRangeException
     */
    public function testAnExceptionIsThrownIfTheItemDoesNotExistsWhenGetIt()
    {
        $this->chain->get('foo');
    }

    /**
     * Test to add an item.
     */
    public function testAddAndGet()
    {
        $id = 'foo';
        $value = new DateTime();

        $this->chain->attach($id, $value);

        $this->assertEquals($value, $this->chain->get($id));
    }

    /**
     * Test that when we remove an item, it's not deleted but just removed.
     */
    public function testRemoveDoesNotDeleteTheItem()
    {
        $id = 'foo';
        $value = new DateTime();

        $this->chain->attach($id, $value);

        $this->chain->detach($id);

        $this->assertInstanceOf('\DateTime', $value);
    }
}
