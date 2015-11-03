<?php

namespace Visca\Bundle\CoreBundle\Collection;

use InvalidArgumentException;

/**
 * Class ObjectCollectionProperties.
 */
class ObjectCollectionProperties
{
    /**
     * Get an array of properties or method from objects in a collection
     * Similar to array_column.
     *
     * @param mixed  $collection       The collection from where you want the properties
     * @param string $propertyOrMethod The property name or method name
     *
     * @return array The array of value extracted from the property or method
     */
    public static function get($collection, $propertyOrMethod)
    {
        $result = [];

        array_walk(
            $collection,
            function ($object) use (&$result, $propertyOrMethod) {
                if (method_exists($object, $propertyOrMethod)) {
                    $result[] = $object->$propertyOrMethod();
                } elseif (property_exists($object, $propertyOrMethod)) {
                    $result[] = $object->$propertyOrMethod;
                } else {
                    throw new InvalidArgumentException(
                        sprintf(
                            'No properties or method "%s" found',
                            $propertyOrMethod
                        )
                    );
                }
            }
        );

        return $result;
    }
}
