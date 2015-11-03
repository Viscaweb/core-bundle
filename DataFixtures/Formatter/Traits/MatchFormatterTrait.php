<?php

namespace Visca\Bundle\CoreBundle\DataFixtures\Formatter\Traits;

/**
 * Class MatchFormatterTrait.
 */
trait MatchFormatterTrait
{
    /**
     * Sum all arguments.
     *
     * @return int
     */
    public function sum()
    {
        return array_sum(func_get_args());
    }
}
