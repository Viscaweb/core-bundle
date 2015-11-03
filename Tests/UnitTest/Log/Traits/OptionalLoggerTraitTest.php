<?php

namespace Visca\Bundle\CoreBundle\Tests\UnitTest\Log\Traits;

use PHPUnit_Framework_MockObject_MockObject;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class OptionalLoggerTraitTest.
 */
class OptionalLoggerTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the log method is called with the right level and message.
     */
    public function testLoggerUseLogWithTheLevelAndMessage()
    {
        $logLevel = LogLevel::INFO;
        $logMessage = 'Foo bar';

        /** @var LoggerInterface|PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = $this->getMock('\Psr\Log\LoggerInterface');
        $logger
            ->expects($this->once())
            ->method('log')
            ->with(
                $this->equalTo($logLevel),
                $this->stringContains($logMessage)
            );

        $demoClass = new ClassUsingOptionalTrait();
        $demoClass->setLogger($logger);

        $demoClass->foo($logLevel, $logMessage);
    }
}
