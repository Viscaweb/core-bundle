<?php

namespace Visca\Bundle\CoreBundle\Tests\UnitTest\Collection;

use DateTime;
use Visca\Bundle\CoreBundle\Collection\ObjectCollectionProperties;

/**
 * Class ObjectCollectionPropertiesTest.
 */
class ObjectCollectionPropertiesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getProperties return the expected values.
     */
    public function testGetPropertiesReturnExpectedValues()
    {
        $collection = [
            new DateTime('2000-02-01'),
            new DateTime('2000-01-01'),
        ];

        $expectedValues = [
            949359600,
            946681200,
        ];

        $actualValues = ObjectCollectionProperties::get(
            $collection,
            'getTimestamp'
        );

        $this->assertEquals($expectedValues, $actualValues);
    }
}
