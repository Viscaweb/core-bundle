<?php

namespace Visca\Bundle\CoreBundle\Tests\UnitTest\ValueObject;

use PHPUnit_Framework_TestCase;

/**
 * Class EnumTest.
 */
class EnumTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test that the method "is" is working.
     */
    public function testMethodIs()
    {
        $male = new Gender(Gender::MALE);
        $this->assertTrue($male->is(Gender::MALE));
        $this->assertFalse($male->is(Gender::FEMALE));
    }

    /**
     * Test that values method return all the possible values.
     */
    public function testValuesMethodReturnAllPossibleValues()
    {
        $expectedValues = [
            new Gender(Gender::MALE),
            new Gender(Gender::FEMALE),
        ];
        $this->assertEquals($expectedValues, array_values(Gender::values()));
    }

    /**
     * Test that an exception is thrown when trying to create an invalid object.
     *
     * @expectedException \UnexpectedValueException
     */
    public function testAnExceptionIsThrownWhenCreatingAnInvalidObject()
    {
        new Gender(8);
    }

    /**
     * Try to create an object from thr raw value.
     */
    public function testToCreateAnObjectFromStringRepresentation()
    {
        $male = new Gender(Gender::MALE);
        $maleStr = (string) $male;

        /* @var Gender $alsoMale */
        $maleConst = Gender::constFromValue($maleStr);
        $alsoMale = Gender::$maleConst();
        $this->assertEquals($male, $alsoMale);
    }
}
