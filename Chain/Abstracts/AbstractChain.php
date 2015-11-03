<?php

namespace Visca\Bundle\CoreBundle\Chain\Abstracts;

use InvalidArgumentException;
use OutOfRangeException;

/**
 * Class AbstractChain.
 *
 * Provide basic features to create a Chain
 */
abstract class AbstractChain
{
    /**
     * @var array
     */
    protected $items;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->items = [];
    }

    /**
     * @param string $id
     *
     * @throws OutOfRangeException
     */
    private function checkItemExists($id)
    {
        $this->assertIdIsValid($id);

        if (!isset($this->items[$id])) {
            throw new OutOfRangeException(
                sprintf('No item found with the given id : "%s".', $id)
            );
        }
    }

    /**
     * Check that the id is valid.
     *
     * @param mixed $id
     *
     * @throws InvalidArgumentException
     */
    private function assertIdIsValid($id)
    {
        if (!is_string($id)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid id. A string is expected but "%s" was given.',
                    gettype($id)
                )
            );
        }
    }

    /**
     * @param string $id
     * @param mixed  $object
     */
    protected function addItem($id, $object)
    {
        $this->assertIdIsValid($id);

        $this->items[$id] = $object;
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    protected function getItem($id)
    {
        $this->checkItemExists($id);

        return $this->items[$id];
    }

    /**
     * @param string $id
     */
    protected function removeItem($id)
    {
        $this->checkItemExists($id);

        unset($this->items[$id]);
    }
}
