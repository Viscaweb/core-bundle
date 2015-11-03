<?php

namespace Visca\Bundle\CoreBundle\Tests\UnitTest\Log\Traits;

use Visca\Bundle\CoreBundle\Log\Traits\OptionalLoggerTrait;

/**
 * Class ClassUsingOptionalTrait.
 */
class ClassUsingOptionalTrait
{
    use OptionalLoggerTrait;

    /**
     * Log a dummy message.
     */
    public function foo($logLevel, $logMessage)
    {
        $this->log($logLevel, $logMessage);
    }
}
