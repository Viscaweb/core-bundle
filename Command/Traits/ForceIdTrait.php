<?php

namespace Visca\Bundle\CoreBundle\Command\Traits;

use ReflectionClass;

/**
 * Class ForceIdTrait.
 */
trait ForceIdTrait
{
    /**
     * @param object       $class
     * @param int          $value
     * @param EntityManger $em
     * @param string       $property
     */
    public function setId($class, $value, $em, $property = 'id')
    {
        $reflectionClass = new ReflectionClass(get_class($class));
        $reflectionProperty = $reflectionClass->getProperty($property);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($class, $value);

        $metadata = $em->getClassMetaData(get_class($class));
        $metadata->setIdGeneratorType(
            \Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE
        );
    }
}
